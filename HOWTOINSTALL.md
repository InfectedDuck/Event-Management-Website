# Installing Kametay Events Management System on a local computer

This guide will help you install and run Kametay Events Management System on your local computer. We'll use XAMPP to set up the web server and database, and Composer to manage PHP dependencies.

## Step 1: Install XAMPP

### 1. Download XAMPP

Go to [XAMPP official website](https://www.apachefriends.org/index.html) and download the latest version of XAMPP for your operating system (Windows, macOS, Linux).

### 2. Install XAMPP

Run the installation file and follow the installation wizard instructions. Make sure you select the Apache and MySQL components as they are required for the Kametay Events Management System to function.

### 3. Launch XAMPP

After installation, launch XAMPP Control Panel and run the following modules:
- Apache
- MySQL

## Step 2: Setting up the database

### 1. Open phpMyAdmin

In XAMPP Control Panel, click on the "Admin" button next to MySQL to open phpMyAdmin in the browser.

### 2. Create a new database

In phpMyAdmin, create a new database for your project. Call it `event_management`.

### 3. Create the necessary tables

In phpMyAdmin, go to the created database and enter:
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
Then,
```
CREATE TABLE events (
 id INT AUTO_INCREMENT PRIMARY KEY,
 event_name VARCHAR(255) NOT NULL,
 location VARCHAR(255) NOT NULL,
 event_date_time DATETIME NOT NULL,
 price DECIMAL(10, 2) NOT NULL,
);
```
Then,
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
Congratulations, the tables are now created!)
## Step 3: Install Composer

### 1. Download and install Composer

Go to [Composer official website](https://getcomposer.org/) and download the installation file for your operating system. Follow the installation instructions.

### 2. Check installation

Open a command line (terminal) and enter the command:

```sh
composer --version
```
### 3. Install Google calendar api:
All this needs to be written in the terminal of your compiler. For example, in Vs code the Terminal is at the top and you have to click on it, then on "New Terminal"
Go to your project path:
```
cd path/to/your/project
```
Installing the Google API client library:
```
composer require google/apiclient:^2.0
```
## Step 4: Download and unpack files:
### 1. Download all project files
Download all project files and place them in one folder.
### 2. Move the files to the XAMPP folder
Go to your XAMPP root folder (usually C:\xampp\htdocs on Windows) and copy your project folder to this directory.
## Step 5: Opening the website and setting up:
### 1. Filling in the data in the events table
Once XAMPP is running, go to http://localhost/your_folder_name/eventtodatabase.html, then click on the "Insert Event Data" button to insert data into the events table.
### 2. Website launch
Go to http://localhost/your_folder_name/EventManagementSystem.html to open the site. If you have any questions or problems, please email kametaytoktar@gmail.com.
## Conclusion:
By following these steps, you will be able to install and run Kametay Events Management System on your local computer. If you have any questions or concerns, please contact us for further assistance.
