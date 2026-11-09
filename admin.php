<?php
// 🔗 Veritabanı bağlantısı
$sunucu = "localhost";
$s_adi = "root";
$s_sifre = "";
$veritabani = "multisession";
$connect = mysqli_connect($sunucu, $s_adi, $s_sifre, $veritabani) or die(mysqli_connect_error());

// 📝 Duyuru işlemleri
$duyurular = []; // Duyurular değişkeni boş bir dizi olarak başlatıldı

if (file_exists("duyuru.txt")) {
    $duyurular = file("duyuru.txt", FILE_IGNORE_NEW_LINES); // Duyuruları dizi olarak oku
}

// ➕ Duyuru ekle
if (isset($_POST['duyuruKaydet'])) {
    $duyuruMetni = trim($_POST['duyuru']);
    if (!empty($duyuruMetni)) {
        file_put_contents("duyuru.txt", $duyuruMetni . "\n", FILE_APPEND);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// ❌ Duyuru sil
if (isset($_GET['duyuruSil'])) {
    $index = intval($_GET['duyuruSil']);
    if (isset($duyurular[$index])) {
        unset($duyurular[$index]);
        file_put_contents("duyuru.txt", implode("\n", $duyurular));
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Veri Girişi ve Duyuru Yönetimi</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #9b59b6, #ffffff);
            color: #333;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            height: 100vh;
            overflow: auto;
            box-sizing: border-box;
        }

        .container {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            width: 100%;
            max-width: 1400px;
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin-top: 30px;
        }

        .left, .right {
            flex: 1;
            padding: 20px;
            box-sizing: border-box;
            background: #f5f5f5;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .left {
            background: #f9f9f9;
            border-right: 2px solid #ccc;
        }

        .right {
            background: #f9f9f9;
            color: #333;
            overflow-y: auto;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        form label {
            font-size: 14px;
            color: #555;
            margin-bottom: 5px;
        }

        form input, form button {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 2px solid #ccc;
            font-size: 14px;
            margin-bottom: 15px;
            box-sizing: border-box;
        }

        form button {
            background-color: #9b59b6;
            color: #fff;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #8e44ad;
        }

        h1, h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        hr {
            border: 1px solid #ccc;
            margin: 20px 0;
        }

        .data {
            font-size: 16px;
            line-height: 1.6;
        }

        .data a {
            color: #9b59b6;
            text-decoration: none;
        }

        .data a:hover {
            text-decoration: underline;
        }

        .duyuru-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px;
            margin-bottom: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .duyuru-item a {
            color: #f44336;
            font-weight: bold;
        }

        .duyuru-item a:hover {
            color: #d32f2f;
        }

        .duyuru-item p {
            margin: 0;
            font-size: 16px;
        }

        .duyuru-item a {
            transition: color 0.3s ease;
        }

        @media screen and (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 10px;
            }

            .left, .right {
                width: 100%;
                padding: 15px;
            }

            form input, form button {
                padding: 10px;
                font-size: 14px;
            }

            h1, h2 {
                font-size: 20px;
            }

            .duyuru-item {
                font-size: 14px;
                padding: 10px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="left">
        <h1>Veri Girişi</h1>
        <form action="" method="POST">
            <label for="username">Kullanıcı Adı:</label>
            <input type="text" id="username" name="username" placeholder="Kullanıcı Adı">

            <label for="password">Şifre:</label>
            <input type="password" id="password" name="password" placeholder="Şifre">

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" placeholder="Email">

            <label for="number">Tel no:</label>
            <input type="number" id="number" name="number" placeholder="Telefon Numarası">

            <button type="submit" name="kaydet">Kaydet</button>
            <button type="submit" name="bul">Bul</button>
            <button type="submit" name="sil">Sil</button>
            <button type="submit" name="guncelle">Güncelle</button>

            <a href="index.php">
                <button type="button">Ana Sayfaya Dön</button>
            </a>
        </form>

        <hr>

        <h2>Duyuru Ekle</h2>
        <form method="POST">
            <label for="duyuru">Duyuru:</label>
            <input type="text" name="duyuru" id="duyuru" placeholder="Duyuru metni" required>
            <button type="submit" name="duyuruKaydet">Duyuru Kaydet</button>
        </form>
    </div>

    <div class="right">
        <h2>Veritabanı Verileri</h2>
        <div class="data">
            <?php
            $sql = "SELECT * from users";
            $s_sql = mysqli_query($connect, $sql);

            $username = @$_POST['username'];
            $password = @$_POST['password'];
            $email = @$_POST['email'];
            $tel = @$_POST['number'];
            $created_at = date('Y-m-d H:i:s');

            $kaydet = @$_POST['kaydet'];
            $bul = @$_POST['bul'];
            $sil = @$_POST['sil'];
            $guncelle = @$_POST['guncelle'];

            if (mysqli_num_rows($s_sql) > 0) {
                while ($row = mysqli_fetch_array($s_sql)) {
                    echo $row["id"] . " | " . $row["username"] . " | " . $row["password"] . " | " . $row["email"] . " | " . $row["phone"] . " | " . $row["created_at"] . "<br>";
                }
            }

            if (isset($bul)) {
                if (empty($username)) {
                    echo "Kullanıcı adı alanını boş bırakma";
                } else {
                    $username = mysqli_real_escape_string($connect, $username); 
                    $sql = "SELECT * FROM users WHERE username='$username'";
                    $stmt = mysqli_query($connect, $sql);

                    if (!$stmt) {
                        die("Sorgu hatası: " . mysqli_error($connect));
                    }

                    if (mysqli_num_rows($stmt) > 0) {
                        while ($row = mysqli_fetch_array($stmt)) {
                            echo $row["id"] . " | " . $row["username"] . " | " . $row["password"] . " | " . $row["email"] . " | " . $row["phone"] . " | " . $row["created_at"] . "<br>";
                        }
                        echo "Başarıyla bulundu!";
                    } else {
                        echo "Kullanıcı bulunamadı.";
                    }
                }
            }

            if (isset($kaydet)) {
                $username = mysqli_real_escape_string($connect, $username);
                $password = mysqli_real_escape_string($connect, $password);
                $email = mysqli_real_escape_string($connect, $email);
                $tel = mysqli_real_escape_string($connect, $tel);

                $sql = "INSERT INTO users (username, password, email, phone, created_at) VALUES ('$username','$password','$email','$tel','$created_at')";
                $stmt = mysqli_query($connect, $sql);

                echo $stmt ? "Kullanıcı kaydedildi." : "Kullanıcı kaydedilemedi: " . mysqli_error($connect);
            }

            if (isset($sil)) {
                $username = mysqli_real_escape_string($connect, $username);
                $sql = "DELETE from users WHERE username='$username'";
                $stmt = mysqli_query($connect, $sql);
                echo $stmt ? "Kullanıcı silindi." : "Kullanıcı silinemedi: " . mysqli_error($connect);
            }

            if (isset($guncelle)) {
                $username = mysqli_real_escape_string($connect, $username);
                $password = mysqli_real_escape_string($connect, $password);
                $email = mysqli_real_escape_string($connect, $email);
                $tel = mysqli_real_escape_string($connect, $tel);

                $sql = "UPDATE users SET password='$password', email='$email', phone='$tel' WHERE username='$username'";
                $stmt = mysqli_query($connect, $sql);
                echo $stmt ? "Kullanıcı güncellendi." : "Kullanıcı güncellenemedi: " . mysqli_error($connect);
            }

            
            ?>
        </div>

        <hr>

        <h2>Duyurular</h2>
        <ul>
            <?php if (!empty($duyurular)): ?>
                <?php foreach ($duyurular as $i => $duyuru): ?>
                    <li class="duyuru-item">
                        <p><?= htmlspecialchars($duyuru) ?></p>
                        <a href="?duyuruSil=<?= $i ?>" onclick="return confirm('Bu duyuruyu silmek istiyor musunuz?');">[Sil]</a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Henüz duyuru bulunmamaktadır.</p>
            <?php endif; ?>
        </ul>
        <?php
        $sorgu_mesaj="SELECT * FROM messages ";
        $stmt_mesaj=mysqli_query($connect,$sorgu_mesaj);
        if ($num=mysqli_num_rows($stmt_mesaj) > 0) {
                        while ($row = mysqli_fetch_array($stmt_mesaj)) {
                            echo $row["id"] . " | " . $row["username"] . " | " . $row["email"] . " | " . $row["message"] . " | " . $row["sent_at"]. "<br>";
                        }
                        echo "Başarıyla bulundu!";
                    }
        mysqli_close($connect);
        ?>
    </div>
</div>

</body>
</html>
