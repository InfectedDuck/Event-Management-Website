<?php
// Начало сессии для сохранения пользовательских данных
session_start();

// Включение файла конфигурации для подключения к базе данных
include 'config.php';

// Проверка, был ли запрос методом POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получение введенных данных из формы
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Подготовка SQL-запроса для получения данных пользователя по email
    $sql = "SELECT id, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email); // Привязка параметра email
    $stmt->execute(); // Выполнение запроса
    $stmt->bind_result($id, $hashedPassword); // Привязка результатов запроса к переменным

    // Проверка, был ли найден пользователь и совпадает ли пароль
    if ($stmt->fetch() && password_verify($password, $hashedPassword)) {
        // Установка переменных сессии при успешной аутентификации
        $_SESSION['userId'] = $id;
        $_SESSION['loggedIn'] = true; // Установка флага авторизации
        header("Location: profile.php"); // Перенаправление на страницу профиля
    } else {
        // Вывод сообщения об ошибке при неверных данных
        echo "Invalid email or password.";
    }

    // Закрытие подготовленного запроса
    $stmt->close();
    // Закрытие подключения к базе данных
    $conn->close();
}
?>
