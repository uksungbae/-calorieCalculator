<!DOCTYPE html>
<html>
<head>
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;

        }
        h1 {
            text-align: center;
            color: #f9f9f9;
            border: 2px solid #ccc;
            background: #1690A7;
            border-radius: 16px;
        }
        .user-info {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            float: right;
            width: 50%;
        }
        .user-info p {
            margin: 5px 0;
        }
        .error {
            color: red;
        }

        p{
            text-align: center;
        }

        .info{
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f9f9f9;
            float: left;
            width:  50%;
        }

        #bt{
            float: left;
            width: 100%;
        }

        h3{
            text-align: center;
        }

        #right{
            height: 327px;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <img src="logo.jpg" alt="" width="50" height="40">
        <a class="navbar-brand" href="#">식단도우미</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="cal_test.php">대사량 계산기</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="food.php">식단 검색 & 저장 </a>
                </li>
            </ul>
            <form class="d-flex">
                <button type="button" class="btn btn-outline-success" onclick="location.href='myp.php'">마이페이지</button>
                <button type="button" class="btn btn-outline-success" onclick="location.href='log_main.php?logout'">로그아웃</button>
            </form>
        </div>
    </div>
</nav>
<h1>마이페이지</h1>
<div class="info">
    <h2>유저 정보</h2>
    <form action="update_user.php" method="POST"> 
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "1669";
        $dbname = "fat";

        session_start();

        try {
            $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $conn->prepare("SELECT * FROM users WHERE id = :user_id");
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo "<ul>";
                foreach ($user as $columnName => $value) {
                   
                    $editableColumns = ['password', 'email', 'key', 'weight', 'height'];
                    if (in_array($columnName, $editableColumns)) {
                        
                        echo "<li><strong>$columnName:</strong> <input type='text' name='$columnName' value='$value'></li>";
                    } else {
                       
                        echo "<li><strong>$columnName:</strong> $value</li>";
                    }
                }
                echo "</ul>";

                
                echo "<button type='submit' name='submit'>수정</button>";
            } else {
                echo "<p class='error'>User not found.</p>";
            }
        } catch(PDOException $e) {
            echo "<p class='error'>Connection failed: " . $e->getMessage() . "</p>";
        }
        ?>
    </form>
    </div>
<div class="info" id="right">
    <h2>개인별 하루섭취량</h2>
    <?php
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stm = $conn->prepare("SELECT * FROM bmi WHERE id = :user_id");
    $stm->bindParam(':user_id', $_SESSION['user_id']);
    $stm->execute();

    $bmi = $stm->fetch(PDO::FETCH_ASSOC);

    if ($bmi) {
        echo "<ul>";
        echo "<li>다이어트 칼로리: " . $bmi['d_kcal'] . " kcal </li>";
        echo "<li>다이어트 탄수화물: " . $bmi['d_crb'] . " g </li>";
        echo "<li> 다이어트 단백질: " . $bmi['d_prt'] . " g</li>";
        echo "<li>다이어트 지방: " . $bmi['d_fat'] . " g</li>";
        echo "<li>벌크업 칼로리: " . $bmi['b_kcal'] . " kcal </li>";
        echo "<li>벌크업 탄수화물: " . $bmi['b_crb'] . " g</li>";
        echo "<li>벌크업 프로틴: " . $bmi['b_prt'] . " g</li>";
        echo "<li>벌크업 지방: " . $bmi['b_fat'] . " g</li>";
        echo "</ul>";
    } else {
        echo "<p class='error'>BMI information not found.</p>";
    }
} catch(PDOException $e) {
    echo "<p class='error'>Connection failed: " . $e->getMessage() . "</p>";
}
?>
</div>

<div class="info" id="bt">
    <h3>저장된 식단</h3>
    <?php
    try {
        $stmt = $conn->prepare("SELECT * FROM food_save WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->execute();

        $foodSave = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($foodSave) {
            echo "<table class='table'>";
            echo "<thead><tr><th>식품이름</th><th>칼로리</th><th>탄수화물</th><th>단백질</th><th>지방</th></tr></thead>";
            echo "<tbody>";
            $kcalSum = 0;
            $crbSum = 0;
            $prtSum = 0;
            $fatSum = 0;
            foreach ($foodSave as $row) {
                echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['kcal'] . " kcal</td>";
                echo "<td>" . $row['crb'] . " g</td>";
                echo "<td>" . $row['prt'] . " g</td>";
                echo "<td>" . $row['fat'] . " g</td>";
                echo "</tr>";

                $kcalSum += $row['kcal'];
                $crbSum += $row['crb'];
                $prtSum += $row['prt'];
                $fatSum += $row['fat'];
            }
            echo "</tbody>";
            echo "</table>";

            echo "<p><strong>총 칼로리:</strong> " . $kcalSum . " kcal</p>";
            echo "<p><strong>총 탄수화물:</strong> " . $crbSum . " g</p>";
            echo "<p><strong>총 단백질:</strong> " . $prtSum . " g</p>";
            echo "<p><strong>총 지방:</strong> " . $fatSum . " g</p>";
        } else {
            echo "<p class='error'>저장된 식품정보가 없습니다.</p>";
        }
    } catch(PDOException $e) {
        echo "<p class='error'>Connection failed: " . $e->getMessage() . "</p>";
    }
    ?>

</
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
