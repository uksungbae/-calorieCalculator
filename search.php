<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["searchTerm"])) {
    $searchTerm = $_POST["searchTerm"];

    $servername = "localhost";
    $username = "root";
    $password = "1669";
    $dbname = "fat";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM food WHERE name LIKE '%$searchTerm%'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $name = $row["name"];
            $type = $row["type"];
            $total = $row["total"];
            $kcal = $row["kcal"];
            $crb = $row["crb"];
            $prt = $row["prt"];
            $fat = $row["fat"];

            echo '<div class="search-result-item">';
            echo '-------------------------------------------------------------------------------------------------------------------------';
            echo '<br>';
            echo '<input type="checkbox" name="selected-item" value="' . htmlspecialchars(json_encode($row)) . '">';
            echo '<label>' . $name . '<b> | </b>' . $type . '<b> | </b>식품중량: ' . $total . 'g<b> | </b>칼로리: ' . $kcal . 'g<b> | </b>탄수화물: ' . $crb . 'g<b> | </b>단백질: ' . $prt . 'g<b> | </b>지방: ' . $fat . 'g</label>';
            echo '</div>';
        }
    } else {
        echo "No results found.";
    }

    $conn->close();
}
?>
