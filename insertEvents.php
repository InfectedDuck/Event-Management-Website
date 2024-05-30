<?php
include 'config.php'; // Подключение к файлу конфигурации для соединения с базой данных

// Проверка, был ли запрос методом POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получение данных событий из тела запроса
    $eventsData = json_decode(file_get_contents('php://input'), true);

    // Проверка, были ли предоставлены данные
    if (empty($eventsData)) {
        // Отправка ответа с сообщением об ошибке, если данные не предоставлены
        echo json_encode(['success' => false, 'message' => 'No data provided.']);
        exit;
    }

    // Подготовка SQL-запроса для вставки данных о событиях
    $stmt = $conn->prepare("INSERT INTO events (event_name, location, event_date_time, price) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        // Отправка ответа с сообщением об ошибке, если произошла ошибка при подготовке запроса
        echo json_encode(['success' => false, 'message' => 'Database error: ' . $conn->error]);
        exit;
    }

    $success = true; // Переменная для отслеживания успеха вставки данных

    // Проход по каждому событию и выполнение вставки данных
    foreach ($eventsData as $event) {
        // Привязка параметров к SQL-запросу
        $stmt->bind_param(
            "ssss",
            $event['event_name'],
            $event['location'],
            $event['event_date_time'],
            $event['price']
        );

        // Выполнение SQL-запроса и проверка результата
        if (!$stmt->execute()) {
            $success = false; // Если вставка не удалась, устанавливаем переменную успеха в false
            break; // Прекращаем цикл
        }
    }

    // Отправка ответа в зависимости от успеха вставки данных
    if ($success) {
        echo json_encode(['success' => true, 'message' => 'Events inserted successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to insert events.', 'error' => $stmt->error]);
    }

    $stmt->close(); // Закрытие подготовленного запроса
    $conn->close(); // Закрытие соединения с базой данных
} else {
    // Отправка ответа с сообщением об ошибке, если запрос был не методом POST
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
