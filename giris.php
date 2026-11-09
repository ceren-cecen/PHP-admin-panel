<?php
session_start();
require "db.php";

if (!isset($_SESSION["id_array"])) {
    $_SESSION["id_array"] = [];
}

function getIpAddressFromCmd() {
    $output = shell_exec('ipconfig | findstr /i "IPv4"');
    $output = trim($output);
    preg_match('/(\d+\.\d+\.\d+\.\d+)/', $output, $matches);
    return isset($matches[0]) ? $matches[0] : null;
}

// Başlangıçta mesaj boş
$errorMessage = "";

$kadi = "";
$mail = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kadi = isset($_POST["kadi"]) ? trim($_POST["kadi"]) : '';
    $mail = isset($_POST["mail"]) ? trim($_POST["mail"]) : '';
    $sifre = isset($_POST["sifre"]) ? trim($_POST["sifre"]) : '';

    if (empty($kadi)) {
        $errorMessage = "Kullanıcı adı boş bırakılamaz.";
    } else if (empty($mail)) {
        $errorMessage = "Mail alanı boş bırakılamaz.";
    } else if (empty($sifre)) {
        $errorMessage = "Şifre alanı boş bırakılamaz.";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ? AND email = ?");
        $stmt->bind_param("sss", $kadi, $sifre, $mail);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_array();

            if (in_array($row["id"], $_SESSION["id_array"])) {
                $errorMessage = "Zaten bu hesapla giriş yaptınız.";
            } else {
                $_SESSION["user_id"] = $row["id"];
                $_SESSION["username"] = $row["username"];
                $_SESSION["array"][$row["id"]] = array(
                    "id" => $row["id"],
                    "kadi" => $row["username"],
                    "sifre" => $row["password"],
                );
                $_SESSION["id_array"][] = $row["id"];

                $cookie_expiry = time() + (86400 * 30);
                setcookie("username", $kadi, $cookie_expiry, "/");
                setcookie("email", $mail, $cookie_expiry, "/");
                setcookie("user_id", $row["id"], $cookie_expiry, "/");

                $localIp = getIpAddressFromCmd();
                if ($localIp) {
                    $stmt = $conn->prepare("INSERT INTO user_sessions (user_id, ip_address, login_time) VALUES (?, ?, NOW())");
                    $stmt->bind_param("is", $row["id"], $localIp);
                    $stmt->execute();
                }

                header("Location: index.php");
                exit();
            }
        } else {
            $errorMessage = "Böyle bir kayıt yok.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #c6a3d6, #ffffff);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #4a148c;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(156, 77, 151, 0.2);
            max-width: 450px;
            width: 90%;
            text-align: center;
            border: 2px solid #9c4d97;
        }

        h1 {
            font-family: 'Lora', serif;
            font-size: 28px;
            color: #9c4d97;
            margin-bottom: 30px;
            text-shadow: 0 0 10px rgba(156, 77, 151, 0.5);
        }

        label {
            text-align: left;
            display: block;
            margin-bottom: 6px;
            font-weight: 600;
            color: #9c4d97;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #9c4d97;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 15px;
        }

        input:focus {
            border-color: #7b1fa2;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            background-color: #9c4d97;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 10px;
        }

        button:hover {
            background-color: #7b1fa2;
            transform: scale(1.03);
        }

        .reset-button {
            background-color: #f44336;
        }

        .reset-button:hover {
            background-color: #d32f2f;
        }

        .links {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
        }

        .links a {
            color: #9c4d97;
            text-decoration: none;
            font-weight: 600;
        }

        .links a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }
        }

        .error-message {
            color: red;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Giriş Yap</h1>

        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <label for="kadi">Kullanıcı Adı</label>
            <input type="text" name="kadi" id="kadi" value="<?php echo htmlspecialchars($kadi); ?>">

            <label for="mail">Mail</label>
            <input type="text" name="mail" id="mail" value="<?php echo htmlspecialchars($mail); ?>">

            <label for="sifre">Şifre</label>
            <input type="password" name="sifre" id="sifre">

            <button type="submit">Giriş Yap</button>
            <button type="reset" class="reset-button">Temizle</button>
        </form>

        <div class="links">
            Hala kayıt olmadın mı? <a href="kayit.php">Buradan</a> kayıt ol<br>
            Bu hesapta giriş yaptıysan <a href="index.php">Buradan</a> ana sayfaya dön
        </div>
    </div>

</body>
</html>
