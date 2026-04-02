<?php

define('MAX_TOKEN', '');


if( ! MAX_TOKEN) exit('Укажите токен max');

getChatsIDs();

/* =============================      */
/* получаем айди чатов */
/* =============================      */
function getChatsIDs() {
	
    
	/*делаем CURL запрос*/
	$response = parser("https://platform-api.max.ru/chats");
	//echo '<pre>';print_r($response); 
	$data = json_decode($response,true);
	//echo '<pre>';print_r($data); echo '</pre>';
    if (isset($data['chats']) && !empty($data['chats'])) {
        echo "ID чатов, в которых состоит бот:<br>";
        foreach ($data['chats'] as $chat) {
            echo $chat['chat_id'] . " - <b>".$chat['title'] ."</b><br>";
        }
    } else {
        echo "Бот не состоит ни в одном чате или список чатов пуст.";
    }
}


/* =============================      */
/* CURL функция собранная */
/* =============================      */


function parser($url){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Authorization: ' . MAX_TOKEN
    ]);

	$result = curl_exec($curl);
	//$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
	//echo '<pre>';print_r($result); // ответ от api сервера если нужен будет
	if($result == false){     
		echo "Ошибка отправки запроса: " . curl_error($curl);
		return false;
	}
	else{
		return $result ;
	}
}




?>