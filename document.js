document.addEventListener('DOMContentLoaded', function() {
    const carousels = document.querySelectorAll('.carousel');
    const prevButtons = document.querySelectorAll('.carousel-button.prev');
    const nextButtons = document.querySelectorAll('.carousel-button.next');
    const dateButtons = document.querySelectorAll('.date-button');
    const eventList = document.querySelector('.event-list');

    let eventsData = {};

    // Collect all events data
    document.querySelectorAll('article.event').forEach(event => {
        const eventId = event.getAttribute('data-id');
        eventsData[eventId] = {
            id: eventId,
            date: event.getAttribute('data-date'),
            time: event.getAttribute('data-time'),
            location: event.getAttribute('data-location'),
            price: event.getAttribute('data-price'),
            title: event.querySelector('.event-title').textContent,
            image: event.querySelector('img').src,
            description: event.querySelector('.event-info h3').textContent
        };
    });

    localStorage.setItem('eventsData', JSON.stringify(eventsData));

    carousels.forEach((carousel, index) => {
        const events = carousel.querySelectorAll('.event');
        const totalEvents = events.length;
        const eventsPerView = 3; // Number of events to display at a time
        let currentIndex = 0;

        function updateCarousel() {
            const translateValue = -currentIndex * (100 / eventsPerView);
            carousel.style.transform = `translateX(${translateValue}%)`;
        }

        function goToNext(event) {
            event.preventDefault();
            if (currentIndex < totalEvents - eventsPerView) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateCarousel();
        }

        function goToPrev(event) {
            event.preventDefault();
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = totalEvents - eventsPerView;
            }
            updateCarousel();
        }

        nextButtons[index].addEventListener('click', goToNext);
        prevButtons[index].addEventListener('click', goToPrev);

        setInterval(goToNext, 5000); // Auto-scroll every 5 seconds
    });

    dateButtons.forEach(button => {
        button.addEventListener('click', function() {
            const selectedDate = this.getAttribute('data-date');
            showEventsForDate(selectedDate);
        });
    });

    function showEventsForDate(date) {
        eventList.innerHTML = ''; // Clear the previous events
        const displayedEvents = new Set();

        for (let eventId in eventsData) {
            const event = eventsData[eventId];
            if (event.date === date && !displayedEvents.has(event.title)) {
                const eventElement = createEventElement(event);
                eventList.appendChild(eventElement);
                displayedEvents.add(event.title);
            }
        }

        if (eventList.innerHTML === '') {
            eventList.innerHTML = '<p>No events found for this date.</p>';
        }
    }

    function createEventElement(event) {
        const article = document.createElement('article');
        article.className = 'event';
        article.setAttribute('data-id', event.id);
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
        `;
        return article;
    }

    // Add stars to the star field
    const starField = document.querySelector('.star-field');
    const stars = 100; // Number of stars

    for (let i = 0; i < stars; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        star.style.left = `${Math.random() * 100}vw`;
        star.style.top = `${Math.random() * 100}vh`;
        star.style.animationDuration = `${Math.random() * 10 + 5}s`;
        starField.appendChild(star);
    }
});
