<?session_start();
  require "config.php";


  $data = $_POST;
  if ( isset($data['do_login'])) // Если нажата кнопка входа
  {
    $errors = array(); // Создание массива с ошибками
    if($result = mysqli_query($link, "SELECT * FROM `admins` WHERE `login` = ".$data['username'])) // проверка введённого логина
    {
      $user = mysqli_fetch_assoc($result);
      if ( $user )
      {
        // Логин существует
        if( md5(md5($data['password'])) == $user['password']) // Сравнение введённого пароля в двойном шифровании с паролем в бд
        {
          header('Location:logged.php');
          $_SESSION['logged_user'] = $user; // создание элемента logged_user
        }else 
      {
        $errors[] = 'Неверно введён пароль!';
      }
      }else 
      {
        $errors[] = 'Неверно введён логин!';
      }

      if ( !empty($errors) )
      {
        echo '<div style="color: red;">'.array_shift($errors).'</div><hr>'; // Вывод ошибки
      }
    }
   

  }
?>
<!DOCTYPE html>
  <html>
  <head>
    <title>Расписание</title>
    <meta charset="utf-8">
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
      <script src="jquery-3.3.1.min.js"></script>
      <script src="send_data.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="style.css">
    <link href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">
    

  </head>
  <body class="authorization">


    <content>
      <div class="form" method="POST">
        <form class="form-horizontal" role="form" method="POST">
          <div class="form-group">
            <div class="form-group">
            <label for="inputEmail3" class="col-sm-2 control-label">Username</label>
            <div class="col-sm-10">
              <input type="text" class="form-control" placeholder="Username" name="username">
            </div>
          </div>
          <div class="form-group">
            <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
            <div class="col-sm-10">
              <input type="password" class="form-control" placeholder="Password" name="password">
            </div>
          </div>
          <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <button type="submit" name="do_login" class="enter btn btn-default btn-sm">Log in</button>
            </div>
          </div>  
        </form>
      </div><!-- form  --> 
    </content>



  

  

  </body>
</html>