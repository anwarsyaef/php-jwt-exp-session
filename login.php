<?php
require_once 'vendor/autoload.php';
require_once 'conn_db.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Periksa apakah username ada di database
    $check_query = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($check_query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && password_verify($password, $row['password'])) {
        // Jika password cocok, buatkan token JWT
        $secret_key = 'paijo';
        $payload = array(
            'user_id' => $row['id'],
            'username' => $row['username'],
            'exp' => time() + (60 * 60) // Token akan kedaluwarsa dalam 1 jam (60 detik * 60 menit)
        );

        // Buat token JWT menggunakan library Firebase\JWT\JWT
        $token = \Firebase\JWT\JWT::encode($payload, $secret_key, 'HS512', null, ['kid' => 'my_key_1']);

        // Simpan token dalam session
        $_SESSION['jwt_token'] = $token;

        // Redirect ke halaman index.php
        header("Location: index.php");
        exit();
    } else {
        echo "Login gagal.";
    }
}
?>

<!-- Form Login -->
<form action="login.php" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <input type="submit" value="Login">
</form>
