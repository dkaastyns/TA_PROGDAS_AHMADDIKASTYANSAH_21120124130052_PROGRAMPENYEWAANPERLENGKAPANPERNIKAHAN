<?php
session_start();
include 'config/db.php';

$db = new Database();
$conn = $db->connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: homepage.php");
        exit();
    } else {
        $error = "Username atau password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas Akhir - Login</title>
    <link rel="stylesheet" href="css/login.css"> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 
</head>
<body>
    <div class="container" id="container">
        <div class="left">
            <h2>Somebody To You Rent</h2>
            <p>Ga punya akun?</p>
            <button onclick="window.location.href='Daftar.php'">Daftar Dulu</button>
        </div>
        <div class="right">
            <h2>Login</h2>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>

            <form method="POST" action="login.php">
                <div class="input-group">
                    <input type="text" name="username" placeholder="Username" required>
                    <i class="fas fa-user"></i>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                    <i class="fas fa-lock"></i>
                </div>
                <div class="verified">
                    <input type="checkbox" id="verified" name="verified" required>
                    <label for="verified">Ceklis dulu biar bisa masuk >.<</label>
                </div>
                <button type="submit" id="loginBtn" >Login</button>
            </form>
        </div>
    </div>
    <script>
        const verifiedCheckbox = document.getElementById('verified');
        const loginBtn = document.getElementById('loginBtn');

        verifiedCheckbox.addEventListener('change', function() {
            loginBtn.disabled = !this.checked;
        });
    </script>
</body>
</html>