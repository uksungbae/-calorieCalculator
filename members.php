<?php

$servername = "localhost";
$username = "root";
$password = "1669";
$dbname = "fat";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$create_db_sql = "CREATE DATABASE IF NOT EXISTS $dbname";
$conn->query($create_db_sql);
$conn->select_db($dbname);


$create_table_sql = "CREATE TABLE IF NOT EXISTS users (
    id VARCHAR(255) PRIMARY KEY,
    password VARCHAR(255),
    name VARCHAR(255),
    email VARCHAR(255),
    birthday DATE,
    gender char(10),
    height int,
    weight int
)";
$conn->query($create_table_sql);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = $_POST['id'];
    $password = $_POST['password'];
    $verify_password = $_POST['verify_password'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $birthday = $_POST['birthday'];
    $gender = $_POST['gender'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];

    $check_id_sql = "SELECT id FROM users WHERE id = '$id'";
    $check_id_result = $conn->query($check_id_sql);

    if ($check_id_result->num_rows > 0) {
        echo "<script>alert('ID가 이미 존재합니다. 다른 ID를 입력하십시오.');</script>";
    } elseif (!preg_match('/^[A-Za-z0-9]+$/', $id)) {
        echo "<script>alert('ID는 영문자와 숫자만 포함해야 합니다.');</script>";
    } elseif ($password !== $verify_password) {
        echo "<script>alert('암호와 암호 확인이 일치하지 않습니다.');</script>";
    } elseif (strlen($password) < 6 || !preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
        echo "<script>alert('암호는 6자 이상이어야 하며 영문자와 숫자의 조합을 포함해야 합니다.');</script>";
    } elseif (!checkDateOfBirth($birthday)) {
        echo "<script>alert('생년월일이 잘못되었습니다. 올바른 날짜를 입력하십시오.'); window.history.back();</script>";
    } else {
        $insert_sql = "INSERT INTO users (id, password, name, email, birthday, gender, height, weight) VALUES ('$id', '$password', '$name', '$email', '$birthday','$gender', '$height', '$weight')";

        if ($conn->query($insert_sql) === TRUE) {
            echo "<script>alert('등록에 성공했습니다. 환영합니다, $name!'); window.location.href = 'log_main.php';</script>";
            exit;
        } else {
            echo "Error: " . $insert_sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();

function checkDateOfBirth($date)
{
    $dateArr = explode("-", $date);
    $year = (int)$dateArr[0];
    $month = (int)$dateArr[1];
    $day = (int)$dateArr[2];
    return checkdate($month, $day, $year);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Membership Sign-Up</title>
    <style>
        body {
            background :#1690A7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;

        }

        form {
            width: 500px;
            border: 2px solid #ccc;
            padding: 50px;
            background: #fff;
            border-radius: 15px;
            max-width: 400px;
            margin: 0 auto;
        }

        input[type="text"],
        input[type="password"],
        input[type="email"],
        input[type="date"],
        input[type="number"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"],
        input[type="button"] {
            background-color: #0096c6;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover,
        input[type="button"]:hover {
            background-color: #4aa8d8;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        p.id{
            color: red;
            font-size: 11px;
            font-weight: 200;
        }
    </style>
</head>
<body>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <p class="id">* ID는 영문자와 숫자만 포함해야 합니다. </p>
        <input type="text" placeholder="ID" name="id" id="id" pattern="[A-Za-z0-9]+" required>
        <input type="password" placeholder="비밀번호" name="password" id="password" required>
        <p class="id">* 암호는 6자 이상이어야 하며 영문자와 숫자의 조합을 포함해야 합니다.</p>
        <input type="password" placeholder="비밀번호 확인" name="verify_password" id="verify_password" required>

        <input type="text" placeholder="이름" name="name" id="name" required>

        <input type="email" placeholder="Email" name="email" id="email" required>

        <input type="date" name="birthday" id="birthday" required>


        <input type="radio" name="gender" value="male" required> 남성
        <input type="radio" name="gender" value="female" required> 여성<br><br>

        <input type="number" placeholder="키(cm)" name="height" id="height" min="1" required>


        <input type="number" placeholder="몸무게(kg)" name="weight" id="weight" min="1" required>

        <input type="submit" value="가입" onclick="sho">
        <input type="button" value="취소" onclick="window.location.href='log_main.php'">
    </form>
</body>
</html>
