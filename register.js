// Добавляем слушатель события на форму с id "login-form"
document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault(); // Предотвращаем отправку формы через браузер

    // Создаем объект FormData, содержащий данные формы
    var formData = new FormData(document.getElementById('login-form'));

    // Отправляем POST-запрос на сервер с данными формы
    fetch('login.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text()) // Преобразуем ответ в текст
    .then(data => {
        if (data === 'success') {
            // Если вход успешен, перенаправляем на страницу профиля
            window.location.href = 'profile.php';
        } else {
            // Если вход не успешен, отображаем сообщение об ошибке
            document.getElementById('login-error').textContent = 'Неверный логин или пароль';
        }
    })
    .catch(error => {
        // Обрабатываем ошибки запроса
        console.error('Error:', error);
    });
});
