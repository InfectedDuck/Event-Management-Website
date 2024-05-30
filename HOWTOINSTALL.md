# Установка Kametay Events Management System на локальный компьютер

Этот гид поможет вам установить и запустить Kametay Events Management System на вашем локальном компьютере. Мы будем использовать XAMPP для настройки веб-сервера и базы данных, а также Composer для управления зависимостями PHP.

## Шаг 1: Установка XAMPP

### 1. Скачайте XAMPP

Перейдите на [официальный сайт XAMPP](https://www.apachefriends.org/index.html) и скачайте последнюю версию XAMPP для вашей операционной системы (Windows, macOS, Linux).

### 2. Установите XAMPP

Запустите установочный файл и следуйте инструкциям мастера установки. Убедитесь, что вы выбрали компоненты Apache и MySQL, так как они необходимы для работы Kametay Events Management System.

### 3. Запустите XAMPP

После установки запустите XAMPP Control Panel и запустите следующие модули:
- Apache
- MySQL

## Шаг 2: Настройка базы данных

### 1. Откройте phpMyAdmin

В XAMPP Control Panel нажмите на кнопку "Admin" рядом с MySQL, чтобы открыть phpMyAdmin в браузере.

### 2. Создайте новую базу данных

В phpMyAdmin создайте новую базу данных для вашего проекта. Назовите ее `event_management`.

### 3. Создайте нужные таблица

В phpMyAdmin зайдите на созданную датабазу и введите:
```
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(255) NOT NULL,
    lastName VARCHAR(255) NOT NULL,
    age INT NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
);
```
Затем,
```
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    event_date_time DATETIME NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
);
```
Затем,
```
CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    userId INT NOT NULL,
    eventId INT NOT NULL,
    seat_number VARCHAR(255) NOT NULL,
    FOREIGN KEY (userId) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (eventId) REFERENCES events(id) ON DELETE CASCADE
);
```
Поздравляю теперь таблицы созданы!)
## Шаг 3: Установка Composer

### 1. Скачайте и установите Composer

Перейдите на [официальный сайт Composer](https://getcomposer.org/) и скачайте установочный файл для вашей операционной системы. Следуйте инструкциям по установке.

### 2. Проверьте установку

Откройте командную строку (терминал) и введите команду:

```sh
composer --version
```
### 3. Установите Google calendar api:
Это всё нужно писать в терминале вашего компилятора. Например, в Vs code Терминал находится сверху и вы должны нажать на него, затем на "Новый терминал"
Перейдите в путь вашего проекта:
```
cd path/to/your/project
```
Установитие клиентскую библиотеку Google API:
```
composer require google/apiclient:^2.0
```
## Шаг 4: Скачивание и распаковка файлов:
### 1. Скачайте все файлы проекта
Скачайте все файлы проекта и поместите их в одну папку.
### 2. Переместите файлы в папку XAMPP
Перейдите в корневую папку XAMPP (обычно это C:\xampp\htdocs на Windows) и скопируйте вашу папку с проектом в эту директорию.
## Шаг 5: Открытие вебсайта и настройка:
### 1. Заполнение данных в таблице events
После того как XAMPP запущен, перейдите по ссылке http://localhost/название_вашей_папки/eventtodatabase.html, затем нажмите на кнопку "Insert Event Data", чтобы залить данные в таблицу events.
### 2. Запуск сайта
Перейдите по ссылке http://localhost/название_вашей_папки/EventManagementSystem.html, чтобы открыть сайт. Если у вас возникли вопросы или проблемы, напишите на почту kametaytoktar@gmail.com.
## Заключение:
Следуя этим шагам, вы сможете установить и запустить Kametay Events Management System на вашем локальном компьютере. Если у вас возникнут какие-либо вопросы или проблемы, пожалуйста, свяжитесь с нами для получения дополнительной помощи.
