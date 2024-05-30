<?php
session_start();
include 'config.php';

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['userId'])) {
    echo 'You are not logged in. <a href="registration.php">Login Here</a>';
    exit;
}

$userId = $_SESSION['userId'];

// Получение данных пользователя
$query = $conn->prepare("SELECT firstName, lastName, email FROM users WHERE id = ?");
$query->bind_param("i", $userId);
$query->execute();
$queryResult = $query->get_result();
$userData = $queryResult->fetch_assoc();

if (!$userData) {
    echo "User details not found.";
    exit;
}

// Получение билетов пользователя
$ticketsQuery = $conn->prepare("
    SELECT tickets.id as ticket_id, tickets.eventId, tickets.seat_number, events.event_name, events.location, events.event_date_time
    FROM tickets
    JOIN events ON tickets.eventId = events.id
    WHERE tickets.userId = ?
");
$ticketsQuery->bind_param("i", $userId);
$ticketsQuery->execute();
$ticketsResult = $ticketsQuery->get_result();

if (!$ticketsResult) {
    echo "Error fetching tickets: " . $conn->error;
    exit;
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <link rel="stylesheet" href="profile.css">
    <script>
        // Функция для подтверждения удаления билета
        function confirmDelete(ticketId) {
            const confirmation = confirm("Точно удалить билет? Вы получите только 75 процентов суммы.");
            if (confirmation) {
                window.location.href = 'delete_ticket.php?ticketId=' + ticketId;
            }
        }

        // Функция для изменения места на мероприятии
        function changeSeat(ticketId, eventId) {
            window.location.href = 'change_seat.php?ticketId=' + ticketId + '&eventId=' + eventId;
        }
    </script>
</head>
<body>
<header>
    <nav>
        <div class="logo">Kametay Events</div>
        <ul>
            <li><a href="Event%20Management%20System.html">Главная</a></li>
            <li><a href="About Us.html">О Нас</a></li>
            <li><a href="logout.php">Выйти</a></li>
        </ul>
    </nav>
</header>

<main>
    <div class="profile-container">
        <h1>Профиль пользователя</h1>
        <div class="user-details">
            <p><strong>Имя:</strong> <?php echo htmlspecialchars($userData['firstName']); ?></p>
            <p><strong>Фамилия:</strong> <?php echo htmlspecialchars($userData['lastName']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
        </div>
        <a href="google-auth.php">Sync Events with Google Calendar</a>
        <h2>Ваши билеты</h2>
        <div class="tickets-list">
            <?php
            if ($ticketsResult->num_rows > 0) {
                echo "<ul>";
                while ($ticket = $ticketsResult->fetch_assoc()) {
                    echo "<li>
                        Мероприятие: " . htmlspecialchars($ticket['event_name']) . " - 
                        Место: " . htmlspecialchars($ticket['seat_number']) . " - 
                        Дата и время: " . htmlspecialchars($ticket['event_date_time']) . " - 
                        Локация: " . htmlspecialchars($ticket['location']) . "
                        <button onclick='confirmDelete(" . $ticket['ticket_id'] . ")'>Убрать</button>
                        <button onclick='changeSeat(" . $ticket['ticket_id'] . ", " . $ticket['eventId'] . ")'>Поменять место</button>
                    </li>";
                }
                echo "</ul>";
            } else {
                echo "<p>Нет купленных билетов.</p>";
            }
            ?>
        </div>
    </div>
</main>
<footer>
    <p>&copy; 2024 Система управления мероприятиями. Все права защищены.</p>
</footer>
</body>
</html>
