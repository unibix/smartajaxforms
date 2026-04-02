<?php

// имя файла CSV где хранятся лиды, например leadsss.csv, если не указывать, то лиды не будут сохраняться в csv
define('CSV_FILENAME', '');

// Яндекс Капча
define('YANDEX_CAPTCHA_SITEKEY', 'ysc1_xMdq3FfSBu2BfbKKt94GDjJfTdhh7bRtystAxzIK3d548870');

// Telegram 
define('TG_TOKEN', ''); //если оставить пустым то не будет отправлять в телеграм чат лид 
define('TG_CHAT_ID', ''); //айди чата в котором ваш бот имеет права отправлять сообщения

// Метрика для срабатывания целей метрики и передачи дополнительных данных о лиде
define('YANDEX_METRIKA_ID', 0);




?>
