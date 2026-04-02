<?php

// имя файла CSV где хранятся лиды, например leadsss.csv, если не указывать, то лиды не будут сохраняться в csv
define('CSV_FILENAME', '');

// email для отправки заявок и лидов
define('EMAIL', ''); //если оставить пустым то не будет отправлять 

// Telegram 
define('TG_TOKEN', ''); //если оставить пустым то не будет отправлять в телеграм чат лид 
define('TG_CHAT_ID', ''); //айди чата в котором ваш бот имеет права отправлять сообщения

// Max messenger
define('MAX_TOKEN', '');  //если оставить пустым то не будет отправлять в макс чат лид 
define('MAX_CHAT_ID', ''); //айди чата в котором ваш бот имеет права отправлять сообщения

// Метрика для срабатывания целей метрики и передачи дополнительных данных о лиде
define('YANDEX_METRIKA_ID', 0);

// Яндекс Капча
define('YANDEX_CAPTCHA_SITEKEY', 'ysc1_xMdq3FfSBu2BfbKKt94GDjJfTdhh7bRtystAxzIK3d548870');



/* ============================= */
/* ОБРАБОТЧИК данных из ФОРМЫ    */
/* ============================= */



if(isset($_POST["ajaxx_phone"])) {

	//сначала проверка на спам
	if($_POST["url"] =='') { exit('Ошибка 718: пустой url'); }; 

	// обработка входящих данных

	$phone   = (isset($_POST["ajaxx_phone"])) ? htmlspecialchars($_POST["ajaxx_phone"]) : '';
	$url 	 = (isset($_POST["url"])) ? htmlspecialchars($_POST["url"]) : '';
	$formname 	 = (isset($_POST["formname"])) ? htmlspecialchars($_POST["formname"]) : '';

	// обработка допполей
	$adds=[];
	foreach ($_POST as $key => $value) {
		if (stripos($key, 'ajaxx_name_') === 0) {
			$adds[$key]['value']= (isset($_POST[str_replace('_name', '', $key)])) ? htmlspecialchars($_POST[str_replace('_name', '', $key)]) : '';
			$adds[$key]['name']= htmlspecialchars($value);

		}
	}

	
	// сохраняем в csv, если в настройках задано имя файла
	if(CSV_FILENAME != '') {
		$data['phone']=$phone;
		$data['adds']='';
		foreach ($adds as $key => $value) {
			$data['adds'] .= $value['name'].":  ".$value['value'].', ';
		}
	
		$data['source']="URL: ".$url." Имя формы: ".$formname." ";
	
		insert_lead($data); 
	}

	// отправка в телеграм чат, если указан токен и ID чата 
	if (TG_TOKEN != '' && TG_CHAT_ID != '' ) {
		$textMessage = "Новый лид\r\n";
	
		$textMessage .= "Телефон:  ".$phone."\r\n";
		foreach ($adds as $key => $value) {
			$textMessage .= $value['name'].":  ".$value['value']."\r\n";
		}
		$textMessage .= "Имя формы:  ".$formname."\r\n";
		$textMessage .= "URL:  ".$url."\r\n";
	
		sendToTelegram($textMessage); 
	}

	// отправка в макс чат, если указан токен и ID чата 
	if (MAX_TOKEN != '' && MAX_CHAT_ID != '' ) {
		$textMessage = "Новый лид\r\n";
	
		$textMessage .= "Телефон:  ".$phone."\r\n";
		foreach ($adds as $key => $value) {
			$textMessage .= $value['name'].":  ".$value['value']."\r\n";
		}
		$textMessage .= "Имя формы:  ".$formname."\r\n";
		$textMessage .= "URL:  ".$url."\r\n";
	
		sendToMax($textMessage); 
	}
	
	// отправка на email
	if (EMAIL != '') {
		$textMessage = "Новый лид\r\n";
	
		$textMessage .= "Телефон:  ".$phone."\r\n";
		foreach ($adds as $key => $value) {
			$textMessage .= $value['name'].":  ".$value['value']."\r\n";
		}
		$textMessage .= "Имя формы:  ".$formname."\r\n";
		$textMessage .= "URL:  ".$url."\r\n";
	
		mailto(EMAIL, 'Новый лид ' . date('Y-m-d H:i'), $textMessage) ; 
	}
	
	
}


/* =============================      */
/* ОТПРАВКА СООБЩЕНИЯ В ТЕЛЕГРАММ чат */
/* =============================      */
function sendToTelegram($message) {
	$message = urlencode($message);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://api.telegram.org/bot".TG_TOKEN."/sendMessage?chat_id=".TG_CHAT_ID."&parse_mode=html&text={$message}");
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	$result = curl_exec($curl);
	//var_dump($result); // ответ от api сервера если нужен будет
	if($result == false){     
		echo "Ошибка отправки запроса: " . curl_error($curl);
		return false;
	}
	else{
		return true;
	}
}


/* =============================      */
/* ОТПРАВКА СООБЩЕНИЯ В МАКС чат */
/* =============================      */
function sendToMax($message) {
	$url = 'https://platform-api.max.ru/messages?chat_id='.MAX_CHAT_ID;
	$ch = curl_init($url);
	// Параметры POST-запроса
	$data = [ 
	    'text' => $message
	];
	
	// Настройки запроса
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
	curl_setopt($ch, CURLOPT_HTTPHEADER, [
	    'Authorization: ' . MAX_TOKEN,
	    'Content-Type: application/json'
	]);
	
	// Выполнение запроса
	$response = curl_exec($ch);
	//var_dump($response);
	$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_exec($ch);// Закрытие соединения

	if ($http_code == 200) {
	   return true;
	} else {
	    echo "Ошибка: не удалось отправить сообщение. HTTP-код: " . $http_code ."\nОтвет сервера: " . $response;
		return false;
	}
}


/* =============================      */
/* отправка заявки на почту EMAIL   */
/* =============================      */

function mailto($to, $subject, $message, $from="Заявка", $fromAddr=EMAIL) {

    mb_internal_encoding('UTF-8');

    $headers = "Date: ".date("r")."\r\n";
    $headers.= "From: =?UTF-8?B?".base64_encode($from)."?= <".$fromAddr.">\r\n";
    $headers.= "MIME-Version: 1.0\r\n";

    $subject = "=?UTF-8?B?".base64_encode($subject)."?=";
    $msgType = (preg_match('@<br|</@uis', $message))  ? "text/html" :"text/plain";
    

    $headers .= "Content-Type: $msgType; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: 8bit\r\n";
    $headers .= "\r\n";
    $body = $message;
    
    return mail($to, $subject, $body, $headers);
}



/* =============================      */
/* вставка данных лида в CSV */
/* =============================      */


function insert_lead($data){
	$fp = fopen(CSV_FILENAME,'a');
	
	$fields[]=date('Y-m-d H:i');
	$fields[]=$data['phone'];
	$fields[]=$data['adds'];
	$fields[]=$data['source'];
	
	fputcsv($fp, $fields,';');
	fclose($fp);
	
}    

