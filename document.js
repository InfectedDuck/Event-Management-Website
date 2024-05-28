document.addEventListener('DOMContentLoaded', function() {
    const carousels = document.querySelectorAll('.carousel');
    const prevButtons = document.querySelectorAll('.carousel-button.prev');
    const nextButtons = document.querySelectorAll('.carousel-button.next');
    const dateButtons = document.querySelectorAll('.date-button');
    const eventList = document.querySelector('.event-list');
    carousels.forEach((carousel, index) => {
        const events = carousel.querySelectorAll('.event');
        const totalEvents = events.length;
        const eventsPerView = 3; // Number of events to display at a time
        const starField = document.querySelector('.star-field');
        const stars = 100; // Number of stars
        let currentIndex = 0;

        function updateCarousel() {
            carousel.style.transform = `translateX(-${currentIndex * (100 / eventsPerView)}%)`;
        }

        function goToNext() {
            if (currentIndex < totalEvents - eventsPerView) {
                currentIndex++;
            } else {
                currentIndex = 0;
            }
            updateCarousel();
        }

        function goToPrev() {
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
        const allEvents = document.querySelectorAll('article.event');
        eventList.innerHTML = ''; // Clear the previous events
        const displayedEvents = new Set();

        allEvents.forEach(event => {
            const eventTitle = event.querySelector('h3').textContent;

            if (event.getAttribute('data-date') === date && !displayedEvents.has(eventTitle)) {
                const eventClone = event.cloneNode(true);
                eventList.appendChild(eventClone);
                displayedEvents.add(eventTitle);
            }
        });

        if (eventList.innerHTML === '') {
            eventList.innerHTML = '<p>No events found for this date.</p>';
        }
    }
    for (let i = 0; i < stars; i++) {
        const star = document.createElement('div');
        star.className = 'star';
        star.style.left = `${Math.random() * 100}vw`;
        star.style.top = `${Math.random() * 100}vh`;
        star.style.animationDuration = `${Math.random() * 10 + 5}s`;
        starField.appendChild(star);
    }
    
});
