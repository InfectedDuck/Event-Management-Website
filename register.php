<?php
session_start(); // Начинаем сессию
include 'config.php'; // Подключаем файл конфигурации для соединения с базой данных

if ($_SERVER["REQUEST_METHOD"] == "POST") { // Проверяем, был ли запрос отправлен методом POST
    $firstName = $_POST['firstName']; // Получаем значение поля firstName из формы
    $lastName = $_POST['lastName']; // Получаем значение поля lastName из формы
    $age = $_POST['age']; // Получаем значение поля age из формы
    $email = $_POST['email']; // Получаем значение поля email из формы
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хэшируем пароль для безопасного хранения

    // Подготавливаем SQL-запрос для вставки нового пользователя в базу данных
    $sql = "INSERT INTO users (firstName, lastName, age, email, password) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql); // Подготавливаем SQL-запрос
    $stmt->bind_param("ssiss", $firstName, $lastName, $age, $email, $password); // Привязываем параметры к запросу
    
    if ($stmt->execute()) { // Выполняем запрос и проверяем, выполнен ли он успешно
        $_SESSION['userId'] = $stmt->insert_id; // Сохраняем ID нового пользователя в сессии
        $_SESSION['loggedIn'] = true; // Устанавливаем флаг loggedIn в сессии
        header("Location: profile.php"); // Перенаправляем пользователя на страницу профиля
    } else {
        echo "Error: " . $conn->error; // Если произошла ошибка, выводим сообщение об ошибке
    }
    
    $stmt->close(); // Закрываем подготовленный запрос
    $conn->close(); // Закрываем соединение с базой данных
}
?>
