<?php
$servername = "localhost";
$username = "root";
$password = "1669";
$dbname = "fat";

session_start();

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if (isset($_POST['submit'])) {
        $userId = $_SESSION['user_id'];
        $updatedValues = $_POST;

        $weight = isset($updatedValues['weight']) ? floatval($updatedValues['weight']) : null;

        $height = isset($updatedValues['height']) ? intval($updatedValues['height']) : null;

        if ($weight !== null && $height !== null) {
            $sql = "UPDATE users SET ";

            foreach ($updatedValues as $columnName => $value) {
                if ($columnName != 'submit') {
                    $editableColumns = ['password', 'email', 'weight', 'height', 'gender'];

                    if (in_array($columnName, $editableColumns)) {
                        $sql .= "$columnName = :$columnName, ";
                    }
                }
            }

            $sql = rtrim($sql, ", ");

            $sql .= " WHERE id = :user_id";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $userId);

            foreach ($updatedValues as $columnName => $value) {
                if ($columnName != 'submit') {
                    $editableColumns = ['password', 'email', 'weight', 'height', 'gender'];

                    if (in_array($columnName, $editableColumns)) {
                        if ($columnName == 'weight') {
                            $stmt->bindValue(":$columnName", $weight, PDO::PARAM_STR);
                        }
                        elseif ($columnName == 'height') {
                            $stmt->bindValue(":$columnName", $height, PDO::PARAM_INT);
                        }
                        elseif ($columnName == 'password') {
                            $stmt->bindValue(":$columnName", $value, PDO::PARAM_STR);
                        }
                        elseif ($columnName == 'email') {
                            $stmt->bindValue(":$columnName", $value, PDO::PARAM_STR);
                        }
                        else {
                            $stmt->bindParam(":$columnName", $value);
                        }
                    }
                }
            }

            $stmt->execute();

            header("Location: myp.php");
            exit();
        } else {
            echo "<p class='error'>Invalid 'weight' or 'height' value.</p>";
        }
    }

    $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo "<form method='post' action='update_user.php'>";
        echo "<ul>";
        foreach ($user as $columnName => $value) {
            $editableColumns = ['password', 'email', 'weight', 'height', 'gender'];
            if (in_array($columnName, $editableColumns)) {
                echo "<li><strong>$columnName:</strong> <input type='text' name='$columnName' value='$value'></li>";
            } else {
                echo "<li><strong>$columnName:</strong> $value</li>";
            }
        }
        echo "</ul>";

        echo "<button type='submit' name='submit'>Save Changes</button>";
        echo "</form>";
    } else {
        echo "<p class='error'>User not found.</p>";
    }
} catch(PDOException $e) {
    echo "<p class='error'>Connection failed: " . $e->getMessage() . "</p>";
}
?>