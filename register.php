<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];

    if ($password === $confirmPassword) {
        $hashedPassword = hash('sha256', $password);

        $stmt = $conn->prepare("INSERT INTO Users (firstName, lastName, age, email, password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiss", $firstName, $lastName, $age, $email, $hashedPassword);

        if ($stmt->execute()) {
            echo "Registration successful!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Passwords do not match!";
    }
}
$conn->close();
?>
