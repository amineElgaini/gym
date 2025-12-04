<?php
session_start();
include './../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password_hash, username FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $password_hash, $username);
        $stmt->fetch();
        if (password_verify($password, $password_hash)) {
            // login success
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            echo "Invalid credentials.";
        }
    } else {
        echo "Invalid credentials.";
    }
}
?>

<!DOCTYPE html>
<html>

<body>
    <h2>Login</h2>
    <form method="post">
        Email: <input type="text" name="username" /><br />
        Password: <input type="password" name="password" /><br />
        <button type="submit">Login</button>
    </form>
    <p>No account? <a href="register.php">Register here</a></p>
</body>

</html>