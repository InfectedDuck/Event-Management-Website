<?php
session_start(); // Запуск сессии для хранения данных пользователя между запросами
include 'config.php'; // Подключение файла конфигурации для доступа к базе данных

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['userId'])) {
    echo 'You are not logged in. <a href="registration.php">Login Here</a>'; // Вывод сообщения, если пользователь не авторизован
    exit; // Прекращение выполнения скрипта
}

$userId = $_SESSION['userId']; // Получение идентификатора пользователя из сессии
$ticketId = $_GET['ticketId']; // Получение идентификатора билета из параметров запроса

// Проверка, принадлежит ли билет авторизованному пользователю
$query = $conn->prepare("SELECT * FROM tickets WHERE id = ? AND userId = ?");
$query->bind_param("ii", $ticketId, $userId); // Привязка параметров запроса
$query->execute(); // Выполнение запроса
$result = $query->get_result(); // Получение результата запроса
$ticket = $result->fetch_assoc(); // Извлечение ассоциативного массива с данными билета

if (!$ticket) {
    echo "Ticket not found or you don't have permission to delete this ticket."; // Сообщение, если билет не найден или не принадлежит пользователю
    exit; // Прекращение выполнения скрипта
}

// Удаление билета
$deleteQuery = $conn->prepare("DELETE FROM tickets WHERE id = ?");
$deleteQuery->bind_param("i", $ticketId); // Привязка параметра запроса
if ($deleteQuery->execute()) {
    echo "Ticket deleted successfully. <a href='profile.php'>Go back to profile</a>"; // Сообщение об успешном удалении билета и ссылка для возврата в профиль
} else {
    echo "Error deleting ticket: " . $conn->error; // Сообщение об ошибке удаления билета
}
?>
