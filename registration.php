<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Войти или Регистрация</title>
    <link rel="stylesheet" href="login.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav>
            <div class="logo">Kametay Events</div> <!-- Логотип сайта -->
            <ul>
                <li><a href="Event%20Management%20System.html">Главная</a></li> <!-- Ссылка на главную страницу -->
                <li><a href="About Us.html">О Нас</a></li> <!-- Ссылка на страницу "О нас" -->
                <li><a href="profile.php" target="_blank">Профиль</a></li> <!-- Ссылка на профиль пользователя -->
            </ul>
        </nav>
        <h1>Добро пожаловать!</h1> <!-- Заголовок приветствия -->
    </header>
    <main>
        <div class="form-container">
            <form id="login-form" action="login.php" method="post"> <!-- Форма для входа -->
                <h2>Войти</h2>
                <div class="input-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required /> <!-- Поле для ввода email -->
                </div>
                <div class="input-group">
                    <label for="password">Пароль:</label>
                    <input type="password" id="password" name="password" required /> <!-- Поле для ввода пароля -->
                </div>
                <button type="submit" class="button">ВОЙТИ</button> <!-- Кнопка для отправки формы входа -->
                <p>Нет аккаунта? <a href="#" id="show-register-form">Регистрация</a></p> <!-- Ссылка для переключения на форму регистрации -->
            </form>
            <form id="register-form" action="register.php" method="post" style="display: none;"> <!-- Форма для регистрации -->
                <h2>Регистрация</h2>
                <div class="input-group">
                    <label for="firstName">Имя:</label>
                    <input type="text" id="firstName" name="firstName" required /> <!-- Поле для ввода имени -->
                </div>
                <div class="input-group">
                    <label for="lastName">Фамилия:</label>
                    <input type="text" id="lastName" name="lastName" required /> <!-- Поле для ввода фамилии -->
                </div>
                <div class="input-group">
                    <label for="age">Возраст:</label>
                    <input type="number" id="age" name="age" required /> <!-- Поле для ввода возраста -->
                </div>
                <div class="input-group">
                    <label for="register-email">Email:</label>
                    <input type="email" id="register-email" name="email" required /> <!-- Поле для ввода email -->
                </div>
                <div class="input-group">
                    <label for="register-password">Пароль:</label>
                    <input type="password" id="register-password" name="password" required /> <!-- Поле для ввода пароля -->
                </div>
                <div class="input-group">
                    <label for="confirm-password">Подтвердите пароль:</label>
                    <input type="password" id="confirm-password" name="confirm-password" required /> <!-- Поле для ввода подтверждения пароля -->
                </div>
                <button type="submit" class="button">Регистрация</button> <!-- Кнопка для отправки формы регистрации -->
                <p>Уже есть аккаунт? <a href="#" id="show-login-form">Войти</a></p> <!-- Ссылка для переключения на форму входа -->
            </form>
        </div>
    </main>
    <footer>
        <p>&copy; 2024 Система управления мероприятиями. Все права защищены.</p> <!-- Подвал сайта -->
    </footer>
    <script>
        // Скрипт для переключения между формами входа и регистрации
        document.getElementById('show-register-form').addEventListener('click', function(event) {
            event.preventDefault(); // Отмена стандартного действия ссылки
            document.getElementById('login-form').style.display = 'none'; // Скрыть форму входа
            document.getElementById('register-form').style.display = 'block'; // Показать форму регистрации
        });

        document.getElementById('show-login-form').addEventListener('click', function(event) {
            event.preventDefault(); // Отмена стандартного действия ссылки
            document.getElementById('login-form').style.display = 'block'; // Показать форму входа
            document.getElementById('register-form').style.display = 'none'; // Скрыть форму регистрации
        });
    </script>
</body>
</html>
