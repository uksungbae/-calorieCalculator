<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "1669";
$dbname = "FAT";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
      
        header("Location: cal_test.php"); 
    } else {
       
        $id = $_POST['id'];
        $password = $_POST['password'];

       
        $sql = "SELECT * FROM users WHERE id = '$id' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $_SESSION['loggedIn'] = true;
            $_SESSION['user_id'] = $id; 

            
            header("Location: cal_test.php");
            exit();
        } else {
           
            $error = "ID 또는 암호가 잘못되었습니다. 다시 시도하십시오.";
            echo "<script>alert('$error');</script>";
        }
    }
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy(); // Destroy the session
    header("Location: log_main.php"); // Redirect to the login page
    exit();
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Page</title>
    <style>
        body {
            background: #1690A7;
            justify-content: left;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        form {
            width: 300px;
            padding: 40px;
            background: #fff;
            border-radius: 22px;
            text-align: center;
            border: 3px solid #ccc;
            margin: 0 auto;
        }

        label {
            display: block;
            margin-bottom: 10px;
            color: #555;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            display: block;
            width: 50%;
            padding: 10px;
            background-color: #0096c6;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #4aa8d8;
        }

        button {
            float: right;
            background: #4aa8d8;
            padding: 10px 15px;
            color: #fff;
            border-radius: 5px;
            margin-right: 10px;
            border: none;
        }

        button:hover {
            opacity: .7;
        }

        img {
            padding-left: 660px;
        }
    </style>
</head>
<body>
    <img src="logo2.jpg" alt="Logo" style="width: 10%;"><br><br>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="text" placeholder="ID" id="id" name="id" required><br><br>
        <input type="password" placeholder="비밀번호" id="password" name="password" required><br><br>
        <button onclick="location.href='members.php'">회원가입</button>
        <input type="submit" value="로그인">
    </form>

    <script>
  
        <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true): ?>
            alert("이미 로그인되었습니다."); 
            window.location.href = "cal_test.php"; 
        <?php endif; ?>
    </script>
</body>
</html>
