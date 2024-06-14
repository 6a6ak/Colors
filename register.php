<?php
require_once 'relation.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    if (empty($username) || empty($password) || empty($password_confirm)) {
        echo "<script>alert('All fields are required.');</script>";
    } elseif ($password !== $password_confirm) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

        if ($mysqli->connect_error) {
            die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
        }

        $stmt = $mysqli->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
        $stmt->bind_param('ss', $username, $hashed_password);

        if ($stmt->execute()) {
            echo "<script>alert('Registration successful.'); window.location.href = 'login.php';</script>";
        } else {
            echo "<script>alert('Username already taken.');</script>";
        }

        $stmt->close();
        $mysqli->close();
    }
}
?>

<?php include 'header.php'; ?>
    <div class="form-container">
        <h2>Register</h2>
        <form method="post" action="register.php">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="password_confirm">Confirm Password:</label>
                <input type="password" id="password_confirm" name="password_confirm" required>
            </div>
            <button type="submit">Register</button>
        </form>
    </div>
</div>
</body>
</html>
