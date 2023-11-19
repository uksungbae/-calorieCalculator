<?php
session_start();


if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: log_main.php");
    exit();
}


$servername = "localhost";
$username = "root";
$password = "1669";
$dbname = "fat";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selectedItems"]) && isset($_POST["delete"])) {
    $selectedItems = $_POST["selectedItems"];

    foreach ($selectedItems as $itemId) {
        $deleteSql = "DELETE FROM food_save WHERE id = '$itemId' AND user_id = '" . $_SESSION['user_id'] . "'";
        $conn->query($deleteSql);
    }
} else if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["selectedItems"])) {
    $selectedItems = $_POST["selectedItems"];


    foreach ($selectedItems as $item) {
        $name = $item["name"];
        $type = $item["type"];
        $total = $item["total"];
        $kcal = $item["kcal"];
        $crb = $item["crb"];
        $prt = $item["prt"];
        $fat = $item["fat"];

        $insertSql = "INSERT INTO food_save (user_id, name, type, total, kcal, crb, prt, fat, created_at)
                      VALUES ('" . $_SESSION['user_id'] . "', '$name', '$type', $total, $kcal, $crb, $prt, $fat, CURRENT_TIMESTAMP)";
        $conn->query($insertSql);
    }
}


$sql = "SELECT DISTINCT id, name, type, total, kcal, crb, prt, fat
        FROM food_save
        WHERE user_id = '" . $_SESSION['user_id'] . "'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  
    while ($row = $result->fetch_assoc()) {
      echo '<div class="storage-item">';
      echo '---------------------------------------------------------------------------------';
      echo '<br>';
      echo '<input type="checkbox" name="selected-storage-item" value="' . htmlspecialchars($row["id"]) . '">';
      echo $row["name"] . '<b> | </b>' . $row["type"] . '<b> | </b>식품중량: ' . $row["total"] . 'g<b> | </b>칼로리: ' . $row["kcal"] . 'g<b> | </b>탄수화물: ' . $row["crb"] . 'g<b> | </b>단백질: ' . $row["prt"] . 'g<b> | </b>지방: ' . $row["fat"].'g';
      echo '</div>';
    }
} else {
    echo "No items found in food storage.";
}

$conn->close();
?>
