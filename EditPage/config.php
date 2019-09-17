<?php
	$db_host = "localhost";
	$db_name = "u292849034_place";
	$db_password = "xrazer";
	$db_login = "u292849034_xraz";
	

	$link = mysqli_connect( 
            $db_host,  /* Хост, к которому мы подключаемся */ 
            $db_login,       /* Имя пользователя */ 
            $db_password,   /* Используемый пароль */ 
            $db_name);     /* База данных для запросов по умолчанию */


	mysqli_set_charset ($link, "utf-8");


	if (!$link) { 
  		printf("Невозможно подключиться к базе данных. Код ошибки: %s\n", mysqli_connect_error()); 
   		exit; 
	} else  {
		
}

	
?>