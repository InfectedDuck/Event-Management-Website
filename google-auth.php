<?php
// Подключаем библиотеку Google API PHP Client
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

// Добавляем необходимую область для доступа к Google Календарю
$client->addScope(Google_Service_Calendar::CALENDAR);

// Проверяем, есть ли токен доступа в сеансе и нет ли кода авторизации в URL
if (!isset($_SESSION['access_token']) && !isset($_GET['code'])) {
    // Создаем URL авторизации и перенаправляем пользователя на сервер OAuth 2.0 Google
    $authUrl = $client->createAuthUrl();
    header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
    exit();
} 
// Проверяем, есть ли код авторизации в URL
elseif (isset($_GET['code'])) {
    // Аутентифицируем пользователя с предоставленным кодом авторизации
    $client->authenticate($_GET['code']);
    
    // Сохраняем токен доступа в сеансе
    $_SESSION['access_token'] = $client->getAccessToken();
    
    // Перенаправляем на этот скрипт, чтобы очистить код авторизации из URL
    header('Location: google-auth.php');
    exit();
} 
// Проверяем, есть ли токен доступа в сеансе
else {
    // Устанавливаем токен доступа в объекте клиента
    $client->setAccessToken($_SESSION['access_token']);
    
    // Перенаправляем на скрипт, который добавляет события в Google Календарь
    header('Location: add-events.php');
    exit();
}
?>
