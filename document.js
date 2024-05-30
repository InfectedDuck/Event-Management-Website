document.addEventListener('DOMContentLoaded', function() {
    const carousels = document.querySelectorAll('.carousel'); // Получаем все элементы карусели
    const prevButtons = document.querySelectorAll('.carousel-button.prev'); // Получаем все кнопки "предыдущий" для каруселей
    const nextButtons = document.querySelectorAll('.carousel-button.next'); // Получаем все кнопки "следующий" для каруселей
    const dateButtons = document.querySelectorAll('.date-button'); // Получаем все кнопки для выбора даты
    const eventList = document.querySelector('.event-list'); // Получаем элемент для списка событий

    let eventsData = {}; // Объект для хранения данных всех событий

    // Сбор данных всех событий
    document.querySelectorAll('article.event').forEach(event => {
        const eventId = event.getAttribute('data-id'); // Получаем ID события
        eventsData[eventId] = {
            id: eventId,
            date: event.getAttribute('data-date'), // Получаем дату события
            time: event.getAttribute('data-time'), // Получаем время события
            location: event.getAttribute('data-location'), // Получаем место события
            price: event.getAttribute('data-price'), // Получаем цену события
            title: event.querySelector('.event-title').textContent, // Получаем заголовок события
            image: event.querySelector('img').src, // Получаем URL изображения события
            description: event.querySelector('.event-info h3').textContent // Получаем описание события
        };
    });

    localStorage.setItem('eventsData', JSON.stringify(eventsData)); // Сохраняем данные событий в localStorage

    carousels.forEach((carousel, index) => {
        const events = carousel.querySelectorAll('.event'); // Получаем все события в текущей карусели
        const totalEvents = events.length; // Общее количество событий
        const eventsPerView = 3; // Количество событий для отображения одновременно
        let currentIndex = 0; // Текущий индекс карусели

        function updateCarousel() {
            const translateValue = -currentIndex * (100 / eventsPerView); // Вычисляем значение для смещения карусели
            carousel.style.transform = `translateX(${translateValue}%)`; // Применяем смещение к карусели
        }

        function goToNext(event) {
            event.preventDefault(); // Предотвращаем действие по умолчанию (например, переход по ссылке)
            if (currentIndex < totalEvents - eventsPerView) {
                currentIndex++; // Увеличиваем текущий индекс
            } else {
                currentIndex = 0; // Возвращаемся к началу карусели
            }
            updateCarousel(); // Обновляем карусель
        }

        function goToPrev(event) {
            event.preventDefault(); // Предотвращаем действие по умолчанию
            if (currentIndex > 0) {
                currentIndex--; // Уменьшаем текущий индекс
            } else {
                currentIndex = totalEvents - eventsPerView; // Переходим к концу карусели
            }
            updateCarousel(); // Обновляем карусель
        }

        nextButtons[index].addEventListener('click', goToNext); // Добавляем обработчик клика на кнопку "следующий"
        prevButtons[index].addEventListener('click', goToPrev); // Добавляем обработчик клика на кнопку "предыдущий"

        setInterval(goToNext, 5000); // Автоматическая прокрутка карусели каждые 5 секунд
    });

    dateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const selectedDate = this.getAttribute('data-date'); // Получаем выбранную дату
            showEventsForDate(selectedDate); // Показываем события для выбранной даты
        });
    });

    function showEventsForDate(date) {
        eventList.innerHTML = ''; // Очищаем список предыдущих событий
        const displayedEvents = new Set(); // Множество для отслеживания отображаемых событий

        for (let eventId in eventsData) {
            const event = eventsData[eventId];
            if (event.date === date && !displayedEvents.has(event.title)) { // Проверяем дату события и его уникальность
                const eventElement = createEventElement(event); // Создаем элемент события
                eventList.appendChild(eventElement); // Добавляем элемент события в список
                displayedEvents.add(event.title); // Добавляем событие в множество отображаемых событий
            }
        }

        if (eventList.innerHTML === '') { // Если для выбранной даты событий нет
            eventList.innerHTML = '<p>No events found for this date.</p>'; // Отображаем сообщение об отсутствии событий
        }
    }

    function createEventElement(event) {
        const article = document.createElement('article'); // Создаем элемент <article>
        article.className = 'event'; // Присваиваем класс "event"
        article.setAttribute('data-id', event.id); // Устанавливаем атрибут data-id
        article.innerHTML = `
            <a href="event-details.html?id=${event.id}">
                <img src="${event.image}" alt="${event.title}" class="event-image">
                <div class="event-overlay">
                    <div class="event-title">${event.title}</div>
                    <button class="event-button">Купить билеты</button>
                </div>
                <div class="event-info">
                    <h3>${event.description}</h3>
                    <p><strong>Дата:</strong> ${event.date}</p>
                    <p><strong>Время:</strong> ${event.time}</p>
                    <p><strong>Место:</strong> ${event.location}</p>
                    <p><strong>Цена:</strong> ${event.price}</p>
                </div>
            </a>
        `; // Устанавливаем HTML-содержимое элемента
        return article; // Возвращаем созданный элемент
    }

    // Добавление звезд на звездное поле
    const starField = document.querySelector('.star-field'); // Получаем элемент звездного поля
    const stars = 100; // Количество звезд

    for (let i = 0; i < stars; i++) {
        const star = document.createElement('div'); // Создаем элемент <div> для звезды
        star.className = 'star'; // Присваиваем класс "star"
        star.style.left = `${Math.random() * 100}vw`; // Устанавливаем случайное горизонтальное положение
        star.style.top = `${Math.random() * 100}vh`; // Устанавливаем случайное вертикальное положение
        star.style.animationDuration = `${Math.random() * 10 + 5}s`; // Устанавливаем случайную продолжительность анимации
        starField.appendChild(star); // Добавляем звезду на звездное поле
    }
});
