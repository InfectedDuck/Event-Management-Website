<?php
// Загружаем библиотеку Google API PHP Client
require_once 'vendor/autoload.php';

// Начинаем сеанс
session_start();

// Создаем новый объект Google клиента
$client = new Google_Client();

// Устанавливаем идентификатор клиента, полученный из Google API Console
$client->setClientId('715192178283-r1ov01jv7t6ke5236tllcujcnv1r85ds.apps.googleusercontent.com');

// Устанавливаем клиентский секрет, полученный из Google API Console
$client->setClientSecret('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');

// Устанавливаем URI перенаправления, который обработает обратный вызов OAuth
$client->setRedirectUri('http://localhost/your_project_path/google-callback.php');

// Если код авторизации присутствует в URL
if (isset($_GET['code'])) {
    // Аутентифицируем пользователя с предоставленным кодом авторизации
    $client->authenticate($_GET['code']);
    
    // Сохраняем токен доступа в сеансе
    $_SESSION['access_token'] = $client->getAccessToken();
    
    // Перенаправляем на скрипт, который добавляет события в Google Календарь
    header('Location: add-events.php');
    exit();
}

// Если токен доступа отсутствует в сеансе
if (!isset($_SESSION['access_token'])) {
    // Перенаправляем на скрипт аутентификации Google
    header('Location: google-auth.php');
    exit();
}

// Устанавливаем токен доступа в объекте клиента
$client->setAccessToken($_SESSION['access_token']);

// Перенаправляем на скрипт, который добавляет события в Google Календарь
header('Location: add-events.php');
exit();
?>
