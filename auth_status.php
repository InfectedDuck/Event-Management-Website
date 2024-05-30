<?php
// Начало сессии для доступа к сохраненным пользовательским данным
session_start();

// Инициализация массива ответа с параметром 'loggedIn' установленным в false по умолчанию
$response = array('loggedIn' => false);

// Проверка, установлен ли флаг 'loggedIn' в сессии и равен ли он true
if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] === true) {
    // Если пользователь авторизован, установить 'loggedIn' в true
    $response['loggedIn'] = true;
}

// Установка заголовка ответа как JSON
header('Content-Type: application/json');
// Кодирование массива ответа в формат JSON и вывод
echo json_encode($response);
?>
