<?php
require_once 'vendor/autoload.php';
require_once 'conn_db.php';

session_start();

// Periksa apakah token JWT ada dalam session
if (isset($_SESSION['jwt_token'])) {
    $token = $_SESSION['jwt_token'];

    // Secret key untuk mendekripsi token JWT (sama dengan yang digunakan pada login.php)
    $secret_key = 'paijo';

    try {
        // Decode token untuk mendapatkan data payload
        $decoded_token = \Firebase\JWT\JWT::decode($token, $secret_key, array('HS512', 'kid' => 'my_key_1'));

        // Periksa apakah token telah kedaluwarsa
        if (isset($decoded_token->exp) && time() > $decoded_token->exp) {
            // Token telah kedaluwarsa, hapus token dari session dan arahkan kembali ke halaman login.php
            unset($_SESSION['jwt_token']);
            header("Location: login.php");
            exit();
        }

        // Data yang berhasil di-decode dari token
        $user_id = $decoded_token->user_id;
        $username = $decoded_token->username;

        // Tampilkan pesan selamat datang
        echo "Selamat datang, $username! Anda berhasil login.";

        // Anda juga dapat menggunakan data ini untuk mengambil data pengguna lain dari database atau melakukan tindakan lain sesuai kebutuhan aplikasi Anda.

    } catch (Exception $e) {
        echo "Terjadi kesalahan dalam verifikasi token: " . $e->getMessage();
    }
} else {
    // Jika token JWT tidak ada dalam session, arahkan kembali ke halaman login.php
    header("Location: login.php");
    exit();
}
?>

<!-- Bagian HTML untuk halaman index.php -->
<a href="logout.php">Logout</a>
