<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Koneksi ke database (Ganti dengan informasi koneksi database Anda)
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'php-jwt';

    $conn = new mysqli($host, $username, $password, $database);
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Ambil data dari form
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

    // Periksa apakah username sudah digunakan
    $check_query = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($check_query);
    if ($result->num_rows > 0) {
        echo "Username sudah digunakan.";
        exit();
    }

    // Simpan data ke database
    $insert_query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if ($conn->query($insert_query) === TRUE) {
        echo "Registrasi berhasil.";
    } else {
        echo "Registrasi gagal: " . $conn->error;
    }

    $conn->close();
}
?>

<!-- Form Registrasi -->
<form action="register.php" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <input type="submit" value="Register">
</form>
