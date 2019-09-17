<? session_start();
	
	    if (isset($_GET['exit'])){
         session_destroy();
         header("Location: index.php");
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
    
    	<?php 
			require "save_data.php";

			$result_even 	= mysqli_query($link, "SELECT * FROM `weeks` WHERE `num` = 'even'");
			$result_odd 	= mysqli_query($link, "SELECT * FROM `weeks` WHERE `num` = 'odd'");



    	?>

	</head>
	<body>

		<?php if ( isset($_SESSION['logged_user']) ) : ?>

		<a href="?exit" class="exit">Выход</a>

		<content>
			<h1 align="center">Изменить расписание</h1>

			<h2 class="even_header">Чётная неделя</h2>
			<h2 class="odd_header">Нечётная неделя</h2>
			
			<div class="clear"></div>
			

			<!-- Форма для чётной недели -->

			<form class="even_form form_edit form-horizontal" action="save_data.php" method="POST">


			

				<?php

					
					while($row = mysqli_fetch_assoc($result_even)){
						echo "				


				<div class='day'>


				<!-- Schedule for monday -->
			
			
					<h4>".$row['weekday_rus']."</h4>


					<!-- first lesson --> 


					<div class='lessons ".$row['weekday']."'>
						<h5>".$row['lessons_num']." пара</h5>

						<!-- first subGroup -->


						<span class='group_header'>".$row['subGroup']." Подгруппа</span>

						<!-- Teacher name input -->

						<div class='teacher_input form-group'>
						    <label for='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher1_input' class='col-sm-2 control-label'>Преподаватель 1</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher1_input' id='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher1_input' placeholder='Преподаватель' value='".$array_data['even'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['teacher']['T1']."'>
						    </div>
						</div>


						<div class='teacher_input form-group'>
						    <label for='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher2_input' class='col-sm-2 control-label'>Преподаватель 2</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher2_input' id='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher2_input' placeholder='Преподаватель' value='".$array_data['even'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['teacher']['T2']."'>
						    </div>
						</div>

						<!-- Object input -->



						<div class='object_input form-group'>
						    <label for='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_object_input' class='col-sm-2 control-label'>Предмет</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_object_input' id='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_object_input' placeholder='Предмет' value='".$array_data['even'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['object']."'>
						    </div>
						</div>

						<!-- Number of audience input -->

						<div class='audience_input form-group'>
						    <label for='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_audience_input' class='col-sm-2 control-label'>Аудитория</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_audience_input' id='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_audience_input' placeholder='Аудитория' value='".$array_data['even'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['audience']."'>
						    </div>
						</div>

						<!-- Time of lesson begin input -->

						<div class='time_input form-group'>
						    <label for='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_time_input' class='col-sm-2 control-label'>Время</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_time_input' id='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_time_input' placeholder='Время' value='".$array_data['even'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['time']."'>
						    </div>
						</div>
						<div class='status_input form-group'>
						    <label for='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_status_input' class='col-sm-2 control-label'>Статус</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_status_input' id='even_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_status_input' placeholder='Статус' value='".$array_data['even'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['status']."'>
						    </div>
						</div>
					</div>
				</div>

			";

				}
?>

			<button type='submit' class='save_left btn btn-default' id="even_save_but">Сохранить</button>
			</form>		

			<!-- Формирование формы нечётной недели с данными из базы данных -->

			<form class='odd_form form_edit form-horizontal' action='test.php' method='POST'>

			


			<?php

					while($row = mysqli_fetch_assoc($result_odd)){
						echo "				


				<div class='day'>


				<!-- Schedule for monday -->
			
			
					<h4>".$row['weekday_rus']."</h4>


					<!-- first lesson --> 


					<div class='lessons ".$row['weekday']."'>
						<h5>".$row['lessons_num']." пара</h5>

						<!-- first subGroup -->


						<span class='group_header'>".$row['subGroup']." Подгруппа</span>

						<!-- Teacher name input -->

						<div class='teacher_input form-group'>
						    <label for='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher1_input' class='col-sm-2 control-label'>Преподаватель 1</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher1_input' id='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher1_input' placeholder='Преподаватель' value='".$array_data['odd'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['teacher']['T1']."'>
						    </div>
						</div>


						<div class='teacher_input form-group'>
						    <label for='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher2_input' class='col-sm-2 control-label'>Преподаватель 2</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher2_input' id='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_teacher2_input' placeholder='Преподаватель' value='".$array_data['odd'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['teacher']['T2']."'>
						    </div>
						</div>

						<!-- Object input -->



						<div class='object_input form-group'>
						    <label for='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_object_input' class='col-sm-2 control-label'>Предмет</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_object_input' id='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_object_input' placeholder='Предмет' value='".$array_data['odd'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['object']."'>
						    </div>
						</div>

						<!-- Number of audience input -->

						<div class='audience_input form-group'>
						    <label for='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_audience_input' class='col-sm-2 control-label'>Аудитория</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_audience_input' id='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_audience_input' placeholder='Аудитория' value='".$array_data['odd'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['audience']."'>
						    </div>
						</div>

						<!-- Time of lesson begin input -->

						<div class='time_input form-group'>
						    <label for='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_time_input' class='col-sm-2 control-label'>Время</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_time_input' id='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_time_input' placeholder='Время' value='".$array_data['odd'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['time']."'>
						    </div>
						</div>
						<div class='status_input form-group'>
						    <label for='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_status_input' class='col-sm-2 control-label'>Статус</label>
						    <div class='col-sm-10'>
						      <input type='text' class='form-control' name='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_status_input' id='odd_".$row['weekday_eng']."_subGroup".$row['subGroup']."_lesson".$row['lessons_num']."_status_input' placeholder='Статус' value='".$array_data['even'][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['status']."'>
						    </div>
						</div>
					</div>
				</div>

			";
	
					}

			?>



		<button type='submit' class='save_right btn btn-default'>Сохранить</button>

		</form>





		</content>
		<?php else : ?>
		<div class="notAuth">
			<span>Вы не авторизованы!</span>
			<a href="index.php">Авторизоваться</a>
		</div>
		<?php endif ?>




	

	

	</body>
</html>