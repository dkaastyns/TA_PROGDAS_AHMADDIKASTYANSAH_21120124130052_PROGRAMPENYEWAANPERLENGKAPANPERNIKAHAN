<?php
session_start();
include 'config/db.php';

$db = new Database();
$conn = $db->connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $telepon = $_POST['telepon'];
    $password = $_POST['password'];
    $gender = $_POST['gender'];
    $umur = $_POST['umur'];

    if ($umur < 19) {
        $error = "Umur harus minimal 19 tahun.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertQuery = "INSERT INTO users (username, email, telepon, password, gender, umur) VALUES (:username, :email, :telepon, :password, :gender, :umur)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telepon', $telepon);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':umur', $umur);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $error = "Gagal mendaftar. Coba lagi!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Registrasi</title>
    <link rel="stylesheet" href="css/daftar.css">
</head>
<body>
<div class="container main-content">
    <h2>Form Registrasi</h2>
    
    <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
    
    <form method="POST" action="Daftar.php" onsubmit="return validateForm()">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Masukan username" required>
        </div>
        
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Masukan email" required>
        </div>
        
        <div class="form-group">
            <label for="telepon">Nomor Telepon</label>
            <input type="text" id="telepon" name="telepon" placeholder="Masukan nomor telepon" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Masukan password" required>
        </div>

        <div class="form-group">
            <label for="umur">Umur</label>
            <input type="number" id="umur" name="umur" placeholder="Masukan umur" required>
        </div>
        
        <div class="form-group radio-group">
            <label>Gender</label>
            <label><input type="radio" name="gender" value="pria" required> Pria</label>
            <label><input type="radio" name="gender" value="wanita"> Wanita</label>
            <label><input type="radio" name="gender" value="Hybird"> Hybird </label>
        </div>
        
        <button type="submit" class="btn">Daftar</button>
    </form>
    
    <button class="btn" onclick="window.location.href='login.php'" style="margin-top: 10px;">Kembali ke Halaman Login</button>
</div>

<script>
    function validateForm() {
        const telepon = document.getElementById('telepon').value;
        const password = document.getElementById('password').value;
        const umur = document.getElementById('umur').value;

        if (!/^\d+$/.test(telepon)) {
            alert("Nomor telepon hanya boleh berisi angka.");
            return false;
        }

        if (password.length < 8 || !/[A-Z]/.test(password) || !/[0-9]/.test(password) || !/[\W]/.test(password)) {
            alert("Password harus minimal 8 karakter, mengandung huruf kapital, angka, dan karakter unik.");
            return false;
        }

        if (umur < 19) {
            alert("Umur harus minimal 19 tahun.");
            return false;
        }

        return true;
    }
</script>
</body>
</html>
