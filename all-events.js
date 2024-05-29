document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const type = urlParams.get('type');

    const eventsContainer = document.getElementById('events-container');
    const eventsTitle = document.getElementById('events-title');

    const eventsData = JSON.parse(localStorage.getItem('eventsData')) || {};

    function createEventElement(event) {
        const article = document.createElement('article');
        article.className = 'event';
        article.innerHTML = `
            <a href="event-details.html?id=${event.id}">
                <img src="${event.image}" alt="${event.title}" class="event-image">
                <div class="event-info">
                    <h3>${event.title}</h3>
                    <p><strong>Дата:</strong> ${event.date}</p>
                    <p><strong>Время:</strong> ${event.time}</p>
                    <p><strong>Место:</strong> ${event.location}</p>
                    <p><strong>Цена:</strong> ${event.price}</p>
                </div>
            </a>
        `;
        return article;
    }

    function populateEvents(events) {
        eventsContainer.innerHTML = '';
        events.forEach(event => {
            const eventElement = createEventElement(event);
            eventsContainer.appendChild(eventElement);
        });
    }

    let filteredEvents = [];
    switch (type) {
        case 'popular':
            eventsTitle.textContent = 'Популярное';
            filteredEvents = Object.values(eventsData).filter(event => event.id <= 5);
            break;
        case 'new':
            eventsTitle.textContent = 'Новое';
            filteredEvents = Object.values(eventsData).filter(event => event.id >= 6 && event.id <= 10);
            break;
        case 'all':
        default:
            eventsTitle.textContent = 'Все события';
            filteredEvents = Object.values(eventsData);
            break;
    }

    populateEvents(filteredEvents);
});
