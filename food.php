<?php
session_start();

if (!isset($_SESSION['loggedIn']) || $_SESSION['loggedIn'] !== true) {
    header("Location: log_main.php");
    exit();
}


if (isset($_POST["delete"]) && $_POST["delete"] === true) {

    exit();
}

if (isset($_POST["selectedItems"])) {


    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Food Management System</title>
    <style>

       #left{
        width: 880px;
        float: left;
        border-right: 3px solid #ccc;
        padding-left: 30px;
        }

        #right{
        width: 500px;
        float: left;
        padding-left: 30px;
        }

        .big{
            width: 300px;
            height: 500px;
        }

        .small{
            width: 200px;
            height: 300px;
        }

        input#search{
            width: 600px;
            height: 50px;
            padding-left: 15px;
            border-radius: 16px;
            text-align: center;
        }

        button#delete-button{
            float: right;
        }

    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
           
            function performSearch() {
                var searchTerm = $('#search').val();
                $.ajax({
                    url: 'search.php',
                    type: 'POST',
                    data: {searchTerm: searchTerm},
                    success: function(response) {
                        $('#search-results').html(response);
                    }
                });
            }

            
            $('#search-button').click(function() {
                performSearch();
            });

            
            $('#search').keypress(function(event) {
                if (event.which === 13) {
                    performSearch();
                }
            });

            
            $('#add-button').click(function() {
                var selectedItems = [];
                $('input[name="selected-item"]:checked').each(function() {
                    selectedItems.push(JSON.parse($(this).val()));
                });
                $.ajax({
                    url: 'food_save.php',
                    type: 'POST',
                    data: {selectedItems: selectedItems},
                    success: function(response) {
                        $('#food-storage').append(response);
                    }
                });
            });

            
            $('#delete-button').click(function() {
                var selectedItems = [];
                $('input[name="selected-storage-item"]:checked').each(function() {
                    selectedItems.push($(this).val());
                });
                $.ajax({
                    url: 'food_save.php',
                    type: 'POST',
                    data: {selectedItems: selectedItems, delete: true},
                    success: function() {
                        $('input[name="selected-storage-item"]:checked').parent().remove();
                    }
                });
            });
        });
    </script>

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
          <a class="nav-link" href="cal_test.php">대사량 계산기</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" aria-current="page" href="food.php">식단 검색 & 저장</a>
        </li>

      </ul>
      <form class="d-flex">
        <button type="button" class="btn btn-outline-success" onclick="location.href='myp.php?<?php echo htmlspecialchars(SID); ?>'">마이페이지</button>
        <button type="button" class="btn btn-outline-success" onclick="logout()">로그아웃</button>
        <script>
            function logout() {
                if (confirm("로그아웃 하시겠습니까?")) {
                    window.location.href = "log_main.php?logout";
                }
            }
        </script>


      </form>


    </div>
  </div>
</nav>
    <div id="left" class="big">
    <h1>식품 검색</h1>

    <input type="text" id="search" placeholder="식품명을 검색하시오.">
    <button id="search-button" class="btn btn-info">검색</button>
    <button id="add-button" class="btn btn-info">추가</button>
    <div id="search-results"></div>

    </div>

    <div id="right" class="small">
    <h1>저장한 식품</h1>
    <button id="delete-button" class="btn btn-info">삭제</button>
    <br><br>
    <div id="food-storage"></div>
    </div>

      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</body>
</html>
