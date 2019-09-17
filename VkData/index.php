<?php



      

    //$time_start = microtime(true) - $_SERVER["REQUEST_TIME_FLOAT"];


    // My api for send messages in VK

    class Bot{

        // Create your bot

        public function __construct($params){
          $this->access_token = $params['access_token']; // Pin access token to context
          $this->api_version = $params['api_version']; // Pin api version to context
        }


        // Method that send only text ( without any attachments )

        public function sendText($params){ // Takes user id and message that you want to send


            $ch = curl_init(); // Create connection

            curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/messages.send?access_token='.$this->access_token.'&v='.$this->api_version);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, build_query($params)); // Used custom function 'build_query' instead 'http_build_query'
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $curl_result = curl_exec($ch); 

            curl_close($ch); // close connection 

            echo 'ok'; // Send 'ok' to vk api
        }

        // Helper method for creating bufer for images. ( Because vk doesn't can send photo by url, only loaded images in directory )

        private function loadBufer($link, $id, $folder){ // Takes folder ( where you load images ), link ( Where you upload images ), image id ( for create unique image )



        $ch = curl_init(); // Create connection

        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL,$link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $result=curl_exec($ch);

        curl_close($ch); // Close connection 

        $src = $folder . $id . '.gif'; // Where to load image

    
        $savefile = fopen($src, 'w');
        fwrite($savefile, $result);
        fclose($savefile);

        return $src; // Return source of image



        }

        // Helper method for clear bufer after send image

        private function clearBufer($id, $folder){ // Takes id of image and folder ( where images stored )

          unlink($folder . $id . '.gif');

        }

        // Main method for send images

        public function sendImage($params){

          $image_id = $params['image_id']; // Image id
          $url = $params['url']; // Where you upload image
          $folder = $params['folder']; // Folder that contains your bufer
          $user_id = $params['user_id']; // User id
          $title = $params['title']; // Title of image


          $url = $this->loadBufer($url, $image_id, $folder);




        // Data of member

        $requestParameters = array(
          'access_token' => $this->access_token, 
          'type' => 'photo',
          'peer_id' => $user_id, 
          'v' => $this->api_version
        );
         
        $res_getUrl = json_decode(
          file_get_contents('https://api.vk.com/method/photos.getMessagesUploadServer?' . build_query($requestParameters)), true);  // Get url for uploading image



       



        if (!empty($res_getUrl['response']['upload_url'])) { // If url for uploading created

            // Send image to server.

            $upload_url = $res_getUrl['response']['upload_url']; // Url


            $ch = curl_init($upload_url);
            $curlfile = curl_file_create(__DIR__.DIRECTORY_SEPARATOR.$url); // Create file for sending to server
            $content = array("file"=>$curlfile);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);

            $upload_data = json_decode(curl_exec($ch)); // Data of sended image (server, hash, photo)
            curl_close($ch);


            if(!empty($upload_data->server)){ // If data have server


                $image = json_decode(file_get_contents('https://api.vk.com/method/photos.saveMessagesPhoto?access_token=' . $requestParameters['access_token'] . '&server=' . $upload_data->server . '&hash=' . $upload_data->hash . '&photo=' . $upload_data->photo . '&v=' . $requestParameters['v'] ), true); // Data of saved image (sizes, id)



        

              if (!empty($image['response'][0]['id'])) { // If image successfuly saved to server

                // Send message to member


                $request_params = array(
                    'user_id' => $params['user_id'],
                    'access_token' => constant("VK_ACCESS_TOKEN"),
                    'message' => $params['title'],
                    'from_group'   => '1', 
                    'owner_id'     => '-' . $params['user_id'],
                    'attachment'  => 'photo'. $params['user_id'] . '_' . $image['response'][0]['id'],
                    'v' => $this->api_version
                );



                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/messages.send?');
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, build_query($request_params));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                $curl_result = curl_exec($ch); 

                curl_close($ch);

                
                $this->clearBufer($image_id, $folder);


                echo 'ok';



              }

            }         
            
          }

        }
      } // Class bot







      





  // Math expressions solver. (WolframAlpha API used)

  function solver($expression, $bot, $data){

      

      include '../wolfram_alpha/wa_wrapper/WolframAlphaEngine.php'; // Include WolframAlpha engine

      $queryIsSet = isset($expression);
      if ($queryIsSet) {

      };



      $appID = '52872U-EQ6HQAEGJA';


      if (!$queryIsSet) die();

      $qArgs = array();
      

      // instantiate an engine object with your app id
      $engine = new WolframAlphaEngine( $appID );

      // we will construct a basic query to the api with the input 'pi'
      // only the bare minimum will be used
      $response = $engine->getResults( $expression, $qArgs);

      // getResults will send back a WAResponse object
      // this object has a parsed version of the wolfram alpha response
      // as well as the raw xml ($response->rawXML) 
      
      // we can check if there was an error from the response object
      if ( $response->isError() ) {

        return 'Вы ввели неправильный запрос!';


        die();
      }

      




    //<hr>


      // if there are any pods, display them
      if ( count($response->getPods()) > 0 ) {

        
        
        $files_names = []; // Ids of buffered images

        foreach ( $response->getPods() as $pod ) {

          
           
              $title = $pod->attributes['title']; // Name of section

            // each pod can contain multiple sub pods but must have at least one
            foreach ( $pod->getSubpods() as $subpod ) {
              // if format is an image, the subpod will contain a WAImage object


              $url = $subpod->image->attributes['src']; // src of image answer


              $id = substr($url, -50, -30); // Create unique identificator of buffered image


              
              $bot->sendImage(['user_id' => $data->object->user_id, 'url' => $url, 'folder' => 'temp_images/', 'title' => $title, 'image_id' => $id]); // Send image
              




              
              
            }
                      
     
        }
       

      }


  } // Wolfram alpha function



// Function instead http_build_query

function build_query($params) {
    $pice = array();
    foreach($params as $k=>$v) {
      $pice[] = $k.'='.urlencode($v);
    }
    return implode('&',$pice);
  }







if (!isset($_REQUEST)) {
    return;
}

require "../EditPage/config.php"; // Config for database with schedule

// Constants

define("VK_SECRET_TOKEN", "ASfsa8asf23a");
define("VK_CONFIRMATION_CODE", "c307e5ab");
define("VK_ACCESS_TOKEN", "fc223b6fcb4dda789039c2cda1d5b34c6b8ebc04032a288c1d6c4bddbc1de8950e4842af9156673c8e80c");



  // Create vk bot

  $bot = new Bot(['access_token' => constant("VK_ACCESS_TOKEN"), 'api_version' => '5.69']); 

  // Take data from vk

  $data = json_decode(file_get_contents('php://input')); 

  // Check for data availaible

  if(!$data){
    return "niok";
  }

    // Check for correct secret token

  if($data->secret !== constant("VK_SECRET_TOKEN") && $data->type !== 'confirmation' )
    return "niok";


    // Cases for interactions with bot

  switch ($data->type)
  {

    // Confirm code

    case 'confirmation':
      echo constant("VK_CONFIRMATION_CODE");
      break;
    


    // If bot takes new message

    case 'message_new':



      



    $user_message = $data->object->body; // Message from user








// Выбор из таблицы 'weeks'
$res = mysqli_query($link, "SELECT * FROM `weeks`");

// Создание массива с данными из бд
$array_data = [];

while ($row = mysqli_fetch_assoc($res)) {
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['object']     = $row['object'];
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['audience']     = $row['audience'];
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['time']       = $row['time'];
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['teacher']['T1'] = $row['teacher1'];
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['teacher']['T2'] = $row['teacher2'];
    $array_data[$row['num']][$row['weekday_eng']]['subGroup'.$row['subGroup']][$row['lesson']]['status']     = $row['status'];
}




$array_data_json = json_encode($array_data, true);


$array_data_from_json_to_array_back = json_decode(urldecode($array_data_json), true);








$request = $user_message; // Запрос



$operators = ['{', '}'];

// If request is math expression (message behind '{' and '}' symbols)

// if((substr($request, 0, 1)) === $operators[0] && (substr($request, -1) === $operators[1]))
// {
  




//   $math_expression =  str_replace($operators, '', $request); // Trim expression behind '{' and '}' 

//   $response = solver($math_expression, $bot, $data);


//   if(gettype($response) === 'string'){

//     $bot->sendText(['user_id' => $data->object->user_id, 'message' => $response]);

//   }




//     break;


//   }



 
  









// Массив для замены при выводе
$translateToHuman = array(
  'January' => 'января',
  'February' => 'февраля',
  'March' => 'марта',
  'April' => 'апреля',
  'May' => 'мая',
  'June' => 'июня',
  'July' => 'июля',
  'August' => 'августа',
  'September' => 'сентября',
  'October' => 'октября',
  'November' => 'ноября',
  'December' => 'декабря',
  'Monday' => 'Понедельник',
  'Tuesday' => 'Вторник',
  'Wednesday' => 'Среда',
  'Thursday' => 'Четверг',
  'Friday' => 'Пятница',
  'Saturday' => 'Суббота',
  'Sunday' => 'Воскресенье',
  'subGroup1' => 'I Подгруппа',
  'subGroup2' => 'II Подгруппа',
  'Object' => 'Предмет',
  'Audience' => 'Кабинет',
  'Time' => 'Время',
  'Teacher' => 'Преподаватель',
  'first_lesson' => '1. ',
  'second_lesson' => '2. ',
  'third_lesson' => '3. ',
  'fourth_lesson' => '4. ',
  'fifth_lesson' => '5. ',
  'sixth_lesson' => '6. '
);

// алиасы для дней недели
$synWeekOfDays = array(
  'Monday' =>  array('Понедельник','понедельник','пн','Пн','gy','Gy','панедельник','понидельник','Gjytltkmybr','gjytltkmybr'),
  'Tuesday' =>  array('Вторник','вторник','вт','Вт','dn','Dn','вторнек','Dnjhybr','dnjhybr'),
  'Wednesday' =>  array('Среда','среда','ср','Ср','ch','Ch','срида','Chtlf','chtlf'),
  'Thursday' =>  array('Четверг','четверг','чт','Чт','xn','Xn','читверг','четверк','Xtndthu','xtndthu'),
  'Friday' =>  array('Пятница','пятница','пт','Пт','gn','Gn','пятнеца','пятницца','Gznybwf','gznybwf'),
  'Saturday' =>  array('Суббота','суббота','сб','Сб','c,','C,','Субота','субота','Ce,,jnf','ce,,jnf'),
  'Sunday' =>  array('Воскресенье','воскресенье','вс','Вс','dc','Dc','Воскресение','воскресение','Djcrhtctymt','djcrhtctymt')
);

// Текущее время
//$time_unix = time() - 1 * 60 * 60; // в юникс
//$current_date = date('d-m-Y H:i:s', $time_unix ); // просто так

// Перевод запроса в день недели
$request_real_day = '';
foreach($synWeekOfDays as $day => $aliases)  {
  foreach($aliases as $alias)  {
    if($alias == $request)  {
      $request_real_day = $day;
    }
  }
  
}


//Проверка

if((isset($request_real_day))&&(!empty($request_real_day)))  {

  // Целевой день
  if ($request_real_day == date('l')) {
    $tarhet_day = date ("j", strtotime("now"));
    $tarhet_month = date ("F", strtotime("now"));
  } else{
    $tarhet_day = date ("j", strtotime("next $request_real_day"));
    $tarhet_month = date ("F", strtotime("next $request_real_day"));
    $tarhet_week   = date ("W", strtotime("next $request_real_day"));
  }


  // Проверка на чётность
  if(($tarhet_week % 2) == 0)  {
    $this_number_is = 'even';
  }  else  {
    $this_number_is = 'odd';
  }

  // Функция возвращает русский язык
  function translateToHuman($array_flip, $string)  {
    foreach($array_flip as $key => $value)  {
      if($string == $key)  {
        return $value;
      }
    }
  }



  // Перебор массива
  $textMessage = ''; // Определение переменной
  $textMessage .= translateToHuman($translateToHuman, $request_real_day) . ', ' . $tarhet_day . ' ' . translateToHuman($translateToHuman, $tarhet_month) . "\n" . 'КС-32' . "\n"; // Заголовки
  foreach($array_data_from_json_to_array_back as $dayType => $dayTypeData)  {
    if($dayType == $this_number_is)  {  // Только текущий тип
      foreach($dayTypeData as $dayOfWeek => $dayOfWeekData)  {
        if($dayOfWeek == $request_real_day)  { // Только нужный день недели
        $found_group1 = '0'; // Маркер поиска (группа) 
        $found_group2 = '0'; // Маркер поиска (группа) 
          foreach($dayOfWeekData as $groups => $groupsData)  { // Перебор основного
            if(($found_group1 == '0')&&($groups == 'subGroup1'))  { // Первая группа
              $textMessage .= '-------------------' . "\n" /*. translateToHuman($translateToHuman, $groups) . "\n"*/; // Добавление префикса
              foreach($groupsData as $lessons => $lessonsData)  { // Перебор занятий
                if ($lessonsData['status'] == 0) 
                {
                  $textMessage .= translateToHuman($translateToHuman, $lessons) . " Отсутствует\n";
                }else if($lessonsData['status'] == 2)
                {
                  $textMessage .= translateToHuman($translateToHuman, $lessons) . " Уточняйте у старосты группы\n";
                }else 
                {
                  $textMessage .= translateToHuman($translateToHuman, $lessons). ' ' . $lessonsData["object"] . ', кабинет ' . $lessonsData["audience"] . ', время ' . $lessonsData["time"] . '. Преподаватели: ' . $lessonsData["teacher"]["T1"] . ', ' . $lessonsData["teacher"]["T2"] . '.' . "\n"; // Добавление данных
                  $found_group1 = '1';
                }
                
              }
            }
            
/*            if(($found_group2 == '0')&&($groups == 'subGroup2'))  { // Вторая группа
              $textMessage .= '-------------------' . "\n" . translateToHuman($translateToHuman, $groups) . "\n"; // Добавление префикса
              foreach($groupsData as $lessons => $lessonsData)  { // Перебор занятий
                if ($lessonsData['status'] == 0) 
                {
                  $textMessage .= translateToHuman($translateToHuman, $lessons) . " Отсутствует\n";
                }else if($lessonsData['status'] == 2)
                {
                  $textMessage .= translateToHuman($translateToHuman, $lessons) . " Уточняйте у старосты группы\n";
                }else 
                {
                  $textMessage .= translateToHuman($translateToHuman, $lessons). ' ' . $lessonsData["object"] . ', кабинет ' . $lessonsData["audience"] . ', время ' . $lessonsData["time"] . '. Преподаватели: ' . $lessonsData["teacher"]["T1"] . ', ' . $lessonsData["teacher"]["T2"] . '.' . "\n"; // Добавление данных
                  $found_group2 = '2';
                }
                
              }
            }*/
          }
        }
      }
    }
  }
}


      $bot->sendText(['user_id' => $data->object->user_id, 'message' => $textMessage]);





      break;


      // If group have new post


    //   case 'wall_post_new':

    //   // Get all members from group
    //   $members = json_decode(file_get_contents("https://api.vk.com/method/groups.getMembers?group_id=159364616&v=5.16&count=1000&fields=sex,bdate,city,country,photo_200_orig,photo_max_orig&access_token=".constant("VK_ACCESS_TOKEN")),true);
    //   $users = '';
    //   $response_items = $members['response']['items']; // Get items included in members information (sex,city,country...)

        
    //   $from_text = $data->object->text; // Get text of post
    //   $from_id = $data->object->from_id; // Get id of post author

    //   if($from_id != "173283033" || $from_id != "382115923"){
    //     die();
    //   }

    //   $users_ids = []; // All group users array

    //  for ($i = 0; $i < count($response_items) + 1; $i++) {
    //     if($response_items[$i]['id'] == $from_id){ // Find name and surname of post author
    //       $from_name = $response_items[$i]['first_name']; // name
    //       $from_surname = $response_items[$i]['last_name']; // surname
    //     }
    //    array_push($users_ids, $response_items[$i]['id']); // push members ids into array
    //   }
      
    //   $message = "


      
    //   !НОВОЕ ОБЪЯВЛЕНИЕ! \n
      



    //   ".$from_name." ".$from_surname.":\n"."«".$from_text."»";  // Create message with post 

    //   //173283033

    //  foreach($users_ids as $id){ // Send post to all group members

    //       $params = array(
    //           'user_id' => $id,    
    //           'message' => $message,  
    //           'access_token' => constant("VK_ACCESS_TOKEN"),  
    //           'v' => '5.69',
    //       );

    //       //$bot->sendText(['user_id' => '173283033', 'message' => 'C ооп: '.round($time_start, 4)]);

    //     $ch = curl_init();

    //       curl_setopt($ch, CURLOPT_URL, 'https://api.vk.com/method/messages.send?');
    //       curl_setopt($ch, CURLOPT_POST, true);
    //       curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    //       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //       $curl_result = curl_exec($ch); 

    //       curl_close($ch);

          


          
    //   }

    //   echo 'ok';

      
      
    //   break;
     

  }

  

















