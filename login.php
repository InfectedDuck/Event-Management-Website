<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashedPassword = hash('sha256', $password);

    $stmt = $conn->prepare("SELECT id, firstName, lastName, age, email FROM Users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $hashedPassword);

    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        session_start();
        $_SESSION['user'] = $result->fetch_assoc();
        echo "Login successful!";
    } else {
        echo "Invalid email or password!";
    }

    $stmt->close();
}
$conn->close();
?>
