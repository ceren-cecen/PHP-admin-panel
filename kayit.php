<?php
require_once 'db.php';

$message = "";
$username = "";
$email = "";
$tel = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $email    = isset($_POST['email']) ? trim($_POST['email']) : '';
    $tel      = isset($_POST['number']) ? trim($_POST['number']) : '';
    $created_at = date('Y-m-d H:i:s');

    // Boşluk ve geçerlilik kontrolleri sırasıyla
    if ($username === "") {
        $message = "Kullanıcı adı boş bırakılamaz.";
    } elseif ($password === "") {
        $message = "Şifre boş bırakılamaz.";
    } elseif ($email === "") {
        $message = "Email boş bırakılamaz.";
    } elseif (strpos($email, '@') === false) {
        $message = "Email adresi geçerli değil. '@' içermelidir.";
    } elseif ($tel === "") {
        $message = "Telefon numarası boş bırakılamaz.";
    } else {
        $sql = "INSERT INTO users (username, password, email, phone, created_at) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("sssss", $username, $password, $email, $tel, $created_at);
            if ($stmt->execute()) {
                $message = "Kullanıcı başarıyla kaydedildi!";
                // Alanları temizle
                $username = $email = $tel = '';
            } else {
                $message = "Hata oluştu: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "SQL hatası: " . $conn->error;
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kayıt</title>
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
        input[type="password"],
        input[type="number"] {
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

        .message {
            font-size: 16px;
            margin-bottom: 20px;
            color: #c2185b;
            font-weight: bold;
        }

        a {
            display: block;
            margin-top: 20px;
            color: #9c4d97;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        a:hover {
            text-decoration: underline;
        }

        @media screen and (max-width: 768px) {
            .container {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>✨ Kayıt ✨</h1>

        <?php if (!empty($message)) echo "<div class='message'>$message</div>"; ?>

        <form action="kayit.php" method="POST">
            <label for="username">Kullanıcı Adı</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">

            <label for="password">Şifre</label>
            <input type="password" id="password" name="password">

            <label for="email">Email</label>
            <!-- HTML tarafında email doğrulama devre dışı -->
            <input type="text" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>">

            <label for="number">Telefon</label>
            <input type="number" id="number" name="number" value="<?php echo htmlspecialchars($tel); ?>">

            <button type="submit">Kaydol</button>
        </form>

        <a href="giris.php">Zaten kayıtlı mısın? Giriş yap</a>
    </div>

</body>
</html>
