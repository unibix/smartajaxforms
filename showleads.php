<?php
// Логин и пароль для доступа
$login = 'admin';
$password = '111222333';

// Проверяем, отправлены ли данные формы входа
if (isset($_POST['password'])) {
    if ($_POST['login'] === $login && $_POST['password'] === $password) {
        // Если данные верны, устанавливаем cookie и разрешаем доступ
        setcookie('authorized', md5($password), time() + 3600*24*90); // cookie действует 90 дней
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    } else {
        $error = 'Неверный логин или пароль!';
    }
}

// Проверяем наличие cookie и защифрованного пароля
if (!isset($_COOKIE['authorized']) || $_COOKIE['authorized'] !== md5($password)) {
    if (isset($error)) {
        echo '<p style="color: black;background: red;padding: 20px;">' . $error . '</p>';
    }
    // Показываем форму авторизации
    ?>
    <div class="login-box">
        <h2>Вход</h2>
        <form  method="post">
            <label for="username">Имя пользователя:</label>
            <input type="text" id="login" name="login" required>
    
            <label for="password">Пароль:</label>
            <input type="password" id="password" name="password" required>
    
            <button type="submit">Войти</button>
        </form>
    </div>
    <style>
        body {
            font-family: Verdana, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            font-size:20px;
        }
        .login-box {
            background: #fff;
            padding: 20px 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        .login-box h2 {
            margin-bottom: 20px;
            text-align: center;
        }
        .login-box label {
            display: block;
            margin-bottom: 5px;
        }
        .login-box input {
            width: 100%;
            padding: 8px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .login-box button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-box button:hover {
            background: #218838;
        }
    </style>
    <?php
    
    
    exit();
}
?><!doctype html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico" />
    <title>Список лидоFF</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>

    <?php

    // получаем массив лидов из csv

    $order = 1; // 0 - в прямом, 1- в обратном порядке

    require_once 'smartajaxformsconfig.php';


    $filename = (isset($_GET['filename'])) ? $_GET['filename'].'.csv' : CSV_FILENAME ; 
    if(!file_exists($filename)) exit('Указанного csv файла нет ');
    $leads = [];
     if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 3000, ";")) !== FALSE) {
          $data[4]=(!isset($data[4])) ? 'Неизвестно':$data[4];
          $data[5]=(!isset($data[5])) ? '-': '-';
          $leads[] = $data;
        }
      }
    fclose($handle);

   if ($order==1)  krsort($leads); // сортируем если надо 

    ?>
      
    <table class="table table-sm table-bordered table-dark">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Дата</th>
      <th scope="col">Телефон</th>
      <th scope="col">Допполя</th>
      <th scope="col" style="max-width: 40vw;">Источник</th>
      
    </tr>
  </thead>
  <tbody>
      
    <?php

    $i=1;

    foreach ($leads AS $lead) {

            ?> 
    <tr>
      <th scope="row"><?=$i?></th>
      <td><?=$lead[0]?></td>
      <td><?=$lead[1]?></td>
      <td><?=$lead[2]?></td>
      <td style="max-width: 40vw;overflow:hidden;"><?=$lead[3]?></td>
      
          
    </tr>
    <?php
    
    $i++;
        
    }
    
?>
      </tbody>
</table>

      
    <!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    -->
  </body>
</html>

