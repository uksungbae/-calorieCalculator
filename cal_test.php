<?php
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {

    header("Location: log_main.php");
    exit();
}

$_SESSION['loggedIn'] = true;

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <style>
    body 
    {   
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
    }
    h1{
      color: #7a7676;
      margin-left: 600px;
       width: 400px;
       border: 2px solid #ccc;
       padding: 10px;
       background: #f0f2f3;
       border-radius: 16px;
      
    }
   
    h2{
        color: #7a7676;
       margin-left: 600px;

       width: 400px;
       border: 2px solid #ccc;
       padding: 10px;
       background: red;
       border-radius: 16px;

    }
   
    h3 {
        color: #7a7676;
       margin-left: 600px;

       width: 400px;
       border: 2px solid #ccc;
       padding: 10px;
       background: #f0f2f3;
       border-radius: 16px;

    }

    form {
        background-color: #fff;
        padding: 20px;
        margin-top: 20px;
      
        margin-left: 600px;
       
        border-radius: 15px;
    
    }
    .d-flex{
        float: right;
        padding: 1px;
        margin-top: 1px;
    }
    label {
        display: inline-block;
        width: 120px;
        margin-bottom: 10px;
    }

    input[type="number"], select {
        width: 200px;
        padding: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    button[type="submit"] {
        padding: 10px 20px;
        background-color: #1690A7;
        border: none;
        color: #fff;
        cursor: pointer;
        border-radius: 4px;
    }


    
    p{
        margin-left: 600px;
        width: 400px;
        border: 2px solid #ccc;
        padding: 10px;
        background: #a1cde8;
        border-radius: 16px;
    }

    nav{
        height: 60px;
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
<div id="big" class="left">
<h1>활동 대사량 계산기</h1>
<form method="POST" action="">
        <label for="id">개인ID:</label>
        <input type="text" name="id" id="id" value="<?php echo $_SESSION['user_id']; ?>" readonly><br>
        <label for="gender">성별</label>
        <select name="gender" id="gender">
            <option value="male">남성</option>
            <option value="female">여성</option>
        </select><br>

        <label for="age">나이:</label>
        <input type="number" name="age" id="age" required><br>

        <label for="height">키 (cm):</label>
        <input type="number" name="height" id="height" required><br>

        <label for="weight">체중 (kg):</label>
        <input type="number" name="weight" id="weight" required><br>

        <label for="activity">하루 중 활동량:</label>
        <select name="activity" id="activity">
            <option value="sitting">앉아있는 업무</option>
            <option value="light">가벼운 운동 (주1~3회)</option>
            <option value="normal">적당한 운동 (주3~5회)</option>
            <option value="active">많은 운동량 (주5~7회)</option>
            <option value="veryactive">운동선수 활동량(하루 2회 운동)</option>
        </select><br>

        <button type="submit">계산 및 저장</button>
    </form>
    </div>
</div>
    <?php
    function calculateBMR($gender, $weight, $height, $age) {
        if ($gender === 'male') {
            $bmr = 88.362 + (13.397 * $weight) + (4.799 * $height) - (5.677 * $age);
        } elseif ($gender === 'female') {
            $bmr = 447.593 + (9.247 * $weight) + (3.098 * $height) - (4.330 * $age);
        } else {
            $bmr = 0;
        }

        return $bmr;
    }

    function calculateActivityMetabolicRate($bmr, $activityLevel) {
        $activityCoefficients = [
            'sitting' => 1.2,
            'light' => 1.375,
            'normal' => 1.55,
            'active' => 1.725,
            'veryactive' => 1.9
        ];

        if (isset($activityCoefficients[$activityLevel])) {
            $activityMetabolicRate = $bmr * $activityCoefficients[$activityLevel];
        } else {
            $activityMetabolicRate = 0;
        }

        return $activityMetabolicRate;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
        $gender = $_POST['gender'];
        $age = $_POST['age'];
        $height = $_POST['height'];
        $weight = $_POST['weight'];
        $activityLevel = $_POST['activity'];

        $bmr = calculateBMR($gender, $weight, $height, $age);

        $activityMetabolicRate = calculateActivityMetabolicRate($bmr, $activityLevel);

        echo '<br>';

        echo '<p>기초대사량: ' . $bmr . ' kcal</p>';
        echo '<p>활동 대사량: ' . $activityMetabolicRate . ' kcal</p>';
    }


    ?>




  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>
<?php
$servername = "localhost";
$username = "root";
$password = "1669";
$dbname = "fat";


$conn = mysqli_connect($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['gender'], $_POST['age'], $_POST['height'], $_POST['weight'], $_POST['activity']))  {

    $id = $_POST['id'];
    $gender = $_POST['gender'];
    $age = $_POST['age'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $activityLevel = $_POST['activity'];

    $bmr = calculateBMR($gender, $weight, $height, $age);


    $activityMetabolicRate = calculateActivityMetabolicRate($bmr, $activityLevel);


    $dietCalories = $activityMetabolicRate - 500;
    $bulkCalories = $activityMetabolicRate + 500;

    $carbohydrateDiet = round($dietCalories * 0.4 / 4);
    $proteinDiet = round($dietCalories * 0.4 / 4);
    $fatDiet = round($dietCalories * 0.2 / 9);

    $carbohydrateBulk = round($bulkCalories * 0.4 / 4);
    $proteinBulk = round($bulkCalories * 0.4 / 4);
    $fatBulk = round($bulkCalories * 0.2 / 9);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['gender'], $_POST['age'], $_POST['height'], $_POST['weight'], $_POST['activity'])) {

        $id = $_POST['id'];


        $deleteQuery = "DELETE FROM bmi WHERE id = '$id'";
        mysqli_query($conn, $deleteQuery);


    }

    $sql = "INSERT INTO bmi (id,d_kcal, d_crb, d_prt, d_fat, b_kcal, b_crb, b_prt, b_fat) VALUES ('$id','$dietCalories', '$carbohydrateDiet','$proteinDiet','$fatDiet',' $bulkCalories','$carbohydrateBulk','$proteinBulk','$fatBulk')";



    echo '<br>';
    echo '<h3>다이어트</h3>';
    echo '<p>일일 권장 칼로리: ' . $dietCalories . ' kcal</p>';
    echo '<p>탄수화물: ' . $carbohydrateDiet . 'g</p>';
    echo '<p>단백질: ' . $proteinDiet . 'g</p>';
    echo '<p>지방: ' . $fatDiet . 'g</p>';

    echo '<br>';
    echo '<h3>근육 증가</h3>';
    echo '<p>일일 권장 칼로리: ' . $bulkCalories . ' kcal</p>';
    echo '<p>탄수화물: ' . $carbohydrateBulk . 'g</p>';
    echo '<p>단백질: ' . $proteinBulk . 'g</p>';
    echo '<p>지방: ' . $fatBulk . 'g</p>';

    if (mysqli_query($conn, $sql)) {
?>


<?php
    } else {
        echo "에러임두: " . mysqli_error($conn);
    }

    mysqli_close($conn);

}
?>
