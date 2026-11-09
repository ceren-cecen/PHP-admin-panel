<?php
$host = "localhost"; 
$username = "root";  
$password = "";  
$database = "multisession"; 

// Veritabanı bağlantısı
$conn = new mysqli($host, $username, $password, $database);

// Bağlantıyı kontrol etme
if ($conn->connect_error) {
    die("Bağlantı başarısız: " . $conn->connect_error);
}
?>
