<?php
include './../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // basic validation
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill all fields.";
    } else {
        // insert
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $password_hash);
        if ($stmt->execute()) {
            header('Location: login.php');
            exit;
        } else {
            echo "Error: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<body>
<h2>Register</h2>
<form method="post">
  Username: <input type="text" name="username" /><br/>
  Email:    <input type="email" name="email" /><br/>
  Password: <input type="password" name="password" /><br/>
  <button type="submit">Register</button>
</form>
<p>Already have an account? <a href="login.php">Login</a></p>
</body>
</html>
