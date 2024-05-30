<?php
require_once 'vendor/autoload.php'; // Подключение автозагрузчика Composer
include 'config.php'; // Включение файла конфигурации для подключения к базе данных

session_start(); // Запуск сессии

// Проверка, есть ли токен доступа в сессии. Если нет, перенаправление на страницу аутентификации Google.
if (!isset($_SESSION['access_token'])) {
    header('Location: google-auth.php');
    exit();
}

// Создание нового клиента Google и установка токена доступа из сессии
$client = new Google_Client();
$client->setAccessToken($_SESSION['access_token']);

// Создание сервиса Google Calendar
$service = new Google_Service_Calendar($client);

$userId = $_SESSION['userId']; // Получение идентификатора пользователя из сессии

// Подготовка и выполнение запроса к базе данных для получения билетов пользователя
$ticketsQuery = $conn->prepare("
    SELECT tickets.id as ticket_id, tickets.eventId, tickets.seat_number, events.event_name, events.location, events.event_date_time
    FROM tickets
    JOIN events ON tickets.eventId = events.id
    WHERE tickets.userId = ?
");
$ticketsQuery->bind_param("i", $userId); // Привязка параметра userId
$ticketsQuery->execute(); // Выполнение запроса
$ticketsResult = $ticketsQuery->get_result(); // Получение результата запроса

// Проверка, есть ли у пользователя билеты
if ($ticketsResult->num_rows > 0) {
    // Перебор всех билетов пользователя
    while ($ticket = $ticketsResult->fetch_assoc()) {
        // Создание события для каждого билета
        $event = new Google_Service_Calendar_Event([
            'summary' => $ticket['event_name'], // Название мероприятия
            'location' => $ticket['location'], // Место проведения мероприятия
            'description' => 'Seat Number: ' . $ticket['seat_number'], // Описание (номер места)
            'start' => [
                'dateTime' => date('c', strtotime($ticket['event_date_time'])), // Начало мероприятия
                'timeZone' => 'Your/Timezone', // Временная зона (необходимо настроить)
            ],
            'end' => [
                'dateTime' => date('c', strtotime($ticket['event_date_time'] . ' +1 hour')), // Окончание мероприятия (предположительно длится 1 час)
                'timeZone' => 'Your/Timezone', // Временная зона (необходимо настроить)
            ],
        ]);

        $calendarId = 'primary'; // Идентификатор календаря (primary - основной календарь)
        $event = $service->events->insert($calendarId, $event); // Вставка события в календарь
        echo 'Event created: ' . $event->htmlLink . '<br>'; // Вывод ссылки на созданное событие
    }
} else {
    echo "No tickets found."; // Сообщение, если у пользователя нет билетов
}
?>
