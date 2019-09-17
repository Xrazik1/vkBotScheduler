<?php

require "config.php";


// Выбор из таблицы 'weeks'
$res = mysqli_query($link, "SELECT * FROM `weeks`");

// Создание массива с данными из бд
$array_data = [];

while ($row = mysqli_fetch_assoc($res)) {
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['object'] 		= $row['object'];
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['audience'] 		= $row['audience'];
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['time'] 			= $row['time'];
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['teacher']['T1'] = $row['teacher1'];
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['teacher']['T2'] = $row['teacher2'];
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['status'] 		= $row['status'];
}
// htmlspecialchars($_POST['changed_values'], ENT_QUOTES,'UTF-8')
$changed_values = $_POST['changed_values'];

$result = mysqli_query($link, "SELECT * FROM `weeks`");

// Запись данных из формы в бд
if ($changed_values) {
	for ($i = 0; $i < count($changed_values); $i++) {
		// Создание ключей для записи
		$week_num 		= $changed_values[$i]["changed_values_split"][0];
		$week_day 		= $changed_values[$i]["changed_values_split"][1];
		$subGroup 		= substr($changed_values[$i]["changed_values_split"][2], -1); // Выбор только номера подгруппы у subGroup(i)
		$lesson_num 	= substr($changed_values[$i]["changed_values_split"][3], -1); // Выбор только номера урока у lesson(i)
		$input_name 	= $changed_values[$i]["changed_values_split"][4];
		// Значение для записи
		$input_value 	= $changed_values[$i]["input_value"];


		// Запись значения по ключам
	    mysqli_query($link, "UPDATE `weeks` SET `".$input_name."` = '".$input_value."' WHERE `num` = '".$week_num."' AND `weekday_eng` = '".$week_day."' AND `subGroup` = '".$subGroup."' AND `lessons_num` = '".$lesson_num."'");
	}
	
}else{
	
}

?>