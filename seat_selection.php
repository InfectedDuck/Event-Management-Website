<?php
session_start();
include 'config.php';

$ticketId = $_GET['ticketId'];
$eventId = $_GET['eventId'];
$openModal = isset($_GET['openModal']) ? $_GET['openModal'] : false;

// Получаем информацию о событии и отображаем интерфейс выбора места
// Добавьте сюда код выбора места
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Поменять место</title>
    <link rel="stylesheet" href="stylish.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Kametay Events</div>
            <ul>
                <li><a href="index.html">Главная</a></li>
                <li><a href="#">О Нас</a></li>
                <li><a href="#">Контакты</a></li>
                <li><a href="registration.php" class="button">ВОЙТИ</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section class="event-detail">
            <h1 id="event-title">Заголовок мероприятия</h1>
            <div class="event-content">
                <h1>Вы успешно поменяли место! Можете уходить!</h1>
            </div>
        </section>
    </main>
    <footer>
        <p>&copy; 2024 Система управления мероприятиями. Все права защищены.</p>
    </footer>

    <!-- Модальное окно для выбора места -->
    <div id="seatSelectionModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Выберите место</h2>
            <div class="stage">Сцена</div>
            <div class="price-legend">
                <div><span class="price-13000"></span>13 000</div>
                <div><span class="price-11000"></span>11 000</div>
                <div><span class="price-10000"></span>10 000</div>
                <div><span class="price-9000"></span>9 000</div>
            </div>
            <div class="seats-container" id="seats-container">
                <!-- Места будут динамически генерироваться здесь -->
            </div>
            <button id="confirmSelection">Подтвердить</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('seatSelectionModal');
            const seatsContainer = document.getElementById('seats-container');
            const confirmSelectionButton = document.getElementById('confirmSelection');
            const eventId = <?php echo json_encode($eventId); ?>;
            const ticketId = <?php echo json_encode($ticketId); ?>;
            const openModal = <?php echo json_encode($openModal); ?>;

            // Функция для открытия модального окна и генерации мест
            function openModalFunction(eventId) {
                modal.style.display = "block";
                generateSeats();
            }

            // Закрытие модального окна
            document.querySelector('.close').onclick = function() {
                modal.style.display = "none";
            };

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };

            // Генерация мест
            function generateSeats() {
                const rows = 15;
                const cols = 24;
                seatsContainer.innerHTML = ''; // Очистить предыдущие места

                const priceClasses = [
                    { priceClass: 'price-13000', rows: [1, 2] },
                    { priceClass: 'price-11000', rows: [3, 4] },
                    { priceClass: 'price-10000', rows: [5, 6, 7, 8] },
                    { priceClass: 'price-9000', rows: [9, 10, 11, 12, 13, 14, 15] },
                ];

                for (let row = 1; row <= rows; row++) {
                    for (let col = 1; col <= cols; col++) {
                        const seat = document.createElement('div');
                        seat.classList.add('seat');
                        seat.textContent = col;

                        const priceClass = priceClasses.find(p => p.rows.includes(row));
                        if (priceClass) {
                            seat.classList.add(priceClass.priceClass);
                        }

                        seat.addEventListener('click', () => {
                            if (!seat.classList.contains('taken')) {
                                seat.classList.toggle('selected');
                            }
                        });

                        seatsContainer.appendChild(seat);
                    }
                }
            }

            // Подтверждение выбора билета
            confirmSelectionButton.addEventListener('click', function() {
                const selectedSeats = document.querySelectorAll('.seat.selected');
                const seatNumbers = Array.from(selectedSeats).map(seat => seat.textContent);
                const userId = <?php echo json_encode($_SESSION['userId']); ?>; // Использование идентификатора пользователя из сессии

                if (seatNumbers.length > 0) {
                    const promises = seatNumbers.map(seatNumber => {
                        return fetch('purchaseTickets.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            body: `userId=${userId}&eventId=${eventId}&seatNumber=${seatNumber}&ticketId=${ticketId}`
                        })
                        .then(response => response.json())
                        .then(result => {
                            alert(result.message);  // Отображение сообщения о результате
                            if (result.status === 'success') {
                                const seat = Array.from(seatsContainer.children).find(s => s.textContent === seatNumber);
                                seat.classList.add('taken');
                                seat.classList.remove('selected');
                            } else {
                                alert(`Не удалось купить место ${seatNumber}: ${result.message}`);
                            }
                        });
                    });

                    Promise.all(promises)
                        .then(() => {
                            alert('Все выбранные места были обработаны.');
                            modal.style.display = 'none'; // Закрыть модальное окно после обработки
                            // Перенаправление на главную страницу
                            window.location.href = 'index.html';
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Произошла ошибка при обработке вашего запроса.');
                        });
                } else {
                    alert('Пожалуйста, выберите хотя бы одно место.');
                }
            });

            // Открытие модального окна, если openModal истинно
            if (openModal) {
                openModalFunction(eventId);
            }
        });
    </script>
</body>
</html>
