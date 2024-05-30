<?php
session_start();
include 'config.php';

// Получение данных из POST-запроса
$userId = $_POST['userId'];
$eventId = $_POST['eventId'];
$seatNumber = $_POST['seatNumber'];
$ticketId = isset($_POST['ticketId']) ? $_POST['ticketId'] : null;

// Начало транзакции
$conn->begin_transaction();

try {
    // Вставка нового билета
    $stmt = $conn->prepare("INSERT INTO tickets (userId, eventId, seat_number) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $userId, $eventId, $seatNumber);
    $stmt->execute();

    // Если передан ticketId, удалить старый билет
    if ($ticketId) {
        $deleteStmt = $conn->prepare("DELETE FROM tickets WHERE id = ?");
        $deleteStmt->bind_param("i", $ticketId);
        $deleteStmt->execute();
    }
    
    // Фиксация транзакции
    $conn->commit();
    
    // Ответ в случае успеха
    echo json_encode(['status' => 'success', 'message' => 'Ticket successfully processed.']);
} catch (Exception $e) {
    // Откат транзакции в случае ошибки
    $conn->rollback();
    // Ответ в случае ошибки
    echo json_encode(['status' => 'error', 'message' => 'Failed to process ticket: ' . $e->getMessage()]);
}
?>
