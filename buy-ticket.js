document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('seatSelectionModal'); // Получаем модальное окно по ID
    const seatsContainer = document.getElementById('seats-container'); // Получаем контейнер для мест по ID
    let selectedEventId = null; // Переменная для хранения ID выбранного события

    // Функция для открытия модального окна и генерации мест
    function openModal(eventId) {
        selectedEventId = eventId; // Сохраняем ID выбранного события
        modal.style.display = "block"; // Отображаем модальное окно
        generateSeats(); // Генерируем места
    }

    // Добавление обработчика события клика на кнопку "Купить билет"
    document.getElementById('buy-ticket-button').addEventListener('click', function(event) {
        event.preventDefault(); // Предотвращаем действие по умолчанию (перезагрузку страницы)
        const eventId = this.getAttribute('data-event-id'); // Получаем ID события из атрибута кнопки
        openModal(eventId); // Открываем модальное окно для выбранного события
    });

    // Закрытие модального окна при клике на элемент с классом "close"
    document.querySelector('.close').onclick = function() {
        modal.style.display = "none"; // Скрываем модальное окно
    };

    // Закрытие модального окна при клике вне его области
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none"; // Скрываем модальное окно
        }
    };

    // Функция для генерации мест
    function generateSeats() {
        const rows = 15; // Количество рядов
        const cols = 24; // Количество колонок
        seatsContainer.innerHTML = ''; // Очистка контейнера от предыдущих мест

        const priceClasses = [
            { priceClass: 'price-13000', rows: [1, 2] },
            { priceClass: 'price-11000', rows: [3, 4] },
            { priceClass: 'price-10000', rows: [5, 6, 7, 8] },
            { priceClass: 'price-9000', rows: [9, 10, 11, 12, 13, 14, 15] },
        ];

        // Генерация мест по рядам и колонкам
        for (let row = 1; row <= rows; row++) {
            for (let col = 1; col <= cols; col++) {
                const seat = document.createElement('div'); // Создание элемента для места
                seat.classList.add('seat'); // Добавление класса "seat"
                seat.textContent = col; // Установка номера места

                const priceClass = priceClasses.find(p => p.rows.includes(row)); // Определение ценовой категории для места
                if (priceClass) {
                    seat.classList.add(priceClass.priceClass); // Добавление ценовой категории к месту
                }

                // Добавление обработчика клика для выбора места
                seat.addEventListener('click', () => {
                    if (!seat.classList.contains('taken')) {
                        seat.classList.toggle('selected'); // Переключение класса "selected" для места
                    }
                });

                seatsContainer.appendChild(seat); // Добавление места в контейнер
            }
        }
    }

    // Подтверждение выбора мест
    document.getElementById('confirmSelection').addEventListener('click', function() {
        const selectedSeats = document.querySelectorAll('.seat.selected'); // Получение всех выбранных мест
        const seatNumbers = Array.from(selectedSeats).map(seat => seat.textContent); // Получение номеров выбранных мест
        const userId = 2; // Замените на динамический ID пользователя из вашего приложения

        if (seatNumbers.length > 0) {
            const promises = seatNumbers.map(seatNumber => {
                return fetch('purchaseTickets.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `userId=${userId}&eventId=${selectedEventId}&seatNumber=${seatNumber}`
                })
                .then(response => response.json())
                .then(result => {
                    alert(result.message);  // Отображение сообщения результата
                    if (result.status === 'success') {
                        const seat = Array.from(seatsContainer.children).find(s => s.textContent === seatNumber);
                        seat.classList.add('taken'); // Пометка места как занятого
                        seat.classList.remove('selected'); // Удаление класса "selected"
                    } else {
                        alert(`Failed to purchase seat ${seatNumber}: ${result.message}`);
                    }
                });
            });

            Promise.all(promises)
                .then(() => {
                    alert('All selected seats have been processed.'); // Уведомление о завершении обработки всех мест
                    modal.style.display = 'none'; // Закрытие модального окна после обработки
                })
                .catch(error => {
                    console.error('Error:', error); // Вывод ошибки в консоль
                    alert('An error occurred while processing your request.'); // Уведомление об ошибке
                });
        } else {
            alert('Please select at least one seat.'); // Уведомление, если не выбрано ни одного места
        }
    });
});
