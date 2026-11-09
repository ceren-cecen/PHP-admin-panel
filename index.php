<?php
session_start();
require "db.php";

// Duyuruları oku
$satirlar = [];
if (file_exists("duyuru.txt")) {
    $satirlar = file("duyuru.txt", FILE_IGNORE_NEW_LINES);
}

// Kullanıcının IP ve giriş zamanı
$ip_address = ''; 
$login_time = ''; 
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id']; 
    $stmt = $conn->prepare("SELECT ip_address, login_time FROM user_sessions WHERE user_id = ? ORDER BY login_time DESC LIMIT 1");
    $stmt->bind_param("i", $user_id); 
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_array();
        $ip_address = $row['ip_address'];  
        $login_time = $row['login_time'];  
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Duyurular - Mor ve Beyaz Tema</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #c6a3d6, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            color: #4a148c;
            overflow: hidden;
        }

        .container {
            background: rgba(255, 255, 255, 0.8);
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 850px;
            text-align: center;
            border: 2px solid #9c4d97;
        }

        h1, h2 {
            font-family: 'Roboto', sans-serif;
            color: #9c4d97;
            margin-bottom: 20px;
            font-size: 2.5em;
            text-shadow: 0 0 15px rgba(156, 77, 151, 0.5);
        }

        .username {
            font-size: 18px;
            font-weight: bold;
            color: #7b1fa2;
        }

        .duyuru-icerik {
            font-size: 16px;
            line-height: 1.6;
            margin-top: 20px;
            color: #5e35b1;
            text-align: left;
        }

        .marquee {
            font-size: 18px;
            color: #fff;
            padding: 15px;
            background-color: #9c4d97;
            white-space: nowrap;
            overflow: hidden;
            width: 100%;
            margin-top: 25px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(156, 77, 151, 0.5);
        }

        .marquee span {
            display: inline-block;
            padding-left: 100%;
            animation: marquee 20s linear infinite;
        }

        @keyframes marquee {
            from { transform: translateX(100%); }
            to { transform: translateX(-100%); }
        }

        .button, a {
            padding: 14px 28px;
            background-color: #9c4d97;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            display: inline-block;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .button:hover, a:hover {
            background-color: #7b1fa2;
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(123, 31, 162, 0.8);
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #9c4d97;
            text-shadow: 0 0 10px rgba(156, 77, 151, 0.5);
        }

        .button-hover-effect {
            position: relative;
            overflow: hidden;
        }

        .button-hover-effect::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 300%;
            height: 300%;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.4s;
            transform: translate(-50%, -50%) scale(0);
            border-radius: 50%;
        }

        .button-hover-effect:hover::after {
            transform: translate(-50%, -50%) scale(1);
        }

        .button-hover-effect:hover {
            background-color: #9c4d97;
            color: #111;
        }
    </style>
</head>
<body>

<div class="container">
    <?php
    if (isset($_COOKIE['username']) && isset($_COOKIE['user_id']) && isset($_COOKIE['email'])) {
        $username = $_COOKIE['username']; 
        $user_id = $_COOKIE['user_id']; 
        $email = $_COOKIE['email'];  

        $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id); 
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_array(); 
            echo "<h2>Hoş geldiniz, <span class='username'>" . htmlspecialchars($row['username']) . "</span>!</h2>";
            echo "<p><strong>Giriş Zamanı:</strong> " . htmlspecialchars($login_time) . "</p>";
            echo "<p><strong>IP Adresi:</strong> " . htmlspecialchars($ip_address) . "</p>";
            echo "<p><strong>E-posta:</strong> " . htmlspecialchars($email) . "</p>";

            echo "<div class='marquee'>
                    <span>" . htmlspecialchars(implode(" | ", $satirlar)) . "</span>
                  </div>";

            echo "<div style='margin-top: 30px;'>";
            echo "<a href='mail.php' class='button-hover-effect'>Mail Gönder</a> | ";
            echo "<a href='giris.php' class='button-hover-effect'>Başka bir hesapla giriş yap</a> | ";
            echo "<a href='oturum.php' class='button-hover-effect'>Oturum Değiştir</a> | ";
            echo "<a href='cikis.php' class='button-hover-effect'>Çıkış yap</a> | ";
            echo "<a href='admin.php' class='button-hover-effect'>Admin Paneli</a>";
            echo "</div>";
        }
    } else {
        echo "<p>Hoş geldiniz, Misafir!</p>";
        echo "<a href='giris.php' class='button-hover-effect'>Giriş Yap</a>";
    }
    ?>

    <a href="bizeulasin.php" class="button-hover-effect">Bize Ulaşın</a>
</div>

</body>
</html>
