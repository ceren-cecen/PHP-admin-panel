<?php
session_start();
header("Content-type: text/html; charset=utf8");
require "db.php";

if (isset($_GET["id"]) && $_GET["id"] != "") {
    $id = $_GET["id"];
    $_SESSION["user_id"] = $id;
    setcookie("user_id", $id, time() + 3600, "/");
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Oturum Yönetimi</title>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #ff9a8b, #fad0c4);
            margin: 0;
            padding: 20px;
            color: #4a148c;
        }

        .container {
            max-width: 800px;
            background-color: rgba(255, 255, 255, 0.9);
            margin: auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(255, 105, 180, 0.2);
            border: 2px solid #f8bbd0;
        }

        h1 {
            font-family: 'Lora', serif;
            text-align: center;
            color: #9c4d97;
            font-size: 36px;
            margin-bottom: 30px;
            text-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .user-entry {
            padding: 12px;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-entry span {
            font-weight: bold;
            color: #ad1457;
        }

        .user-entry a {
            text-decoration: none;
            margin-left: 10px;
            color: #2980b9;
        }

        .user-entry a:hover {
            text-decoration: underline;
        }

        .current-user {
            color: #e91e63;
            font-weight: bold;
        }

        .no-users {
            text-align: center;
            color: #777;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons a {
            padding: 10px 20px;
            background-color: #9c4d97;
            color: white;
            border-radius: 8px;
            font-size: 14px;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.2s;
        }

        .action-buttons a:hover {
            background-color: #7b1fa2;
            transform: scale(1.05);
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #777;
        }

    </style>
</head>
<body>

<div class="container">
    <h1> Aktif Oturumlar</h1>

    <?php
    if (isset($_SESSION["id_array"])) {
        foreach ($_SESSION["id_array"] as $id) {
            echo '<div class="user-entry">';
            echo '<span class="' . ($_SESSION["user_id"] == $id ? 'current-user' : '') . '">' . 
                 htmlspecialchars($_SESSION["array"][$id]["kadi"]) . '</span>';

            echo '<div class="action-buttons">';

            // Eğer bu kullanıcı aktifse ana sayfaya dön butonunu göster
            if ($_SESSION["user_id"] == $id) {
                echo '<a href="index.php" class="current-user">Ana sayfaya dön</a>';
            } else {
                // Başka bir kullanıcıyı seçmek ve çıkış yapmak için bağlantılar
                echo '<a href="oturum.php?id=' . $id . '">Bu hesaba geç</a>';
                echo '<a href="cikis_tekli.php?id=' . $id . '">Bu hesaptan çık</a>';
            }

            echo '</div>';

            echo '</div>';
        }
    } else {
        echo '<p class="no-users">Henüz oturum açmış kullanıcı yok.</p>';
    }
    ?>

</div>
</body>
</html>
