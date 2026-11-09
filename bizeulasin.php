<?php
require "db.php";

$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" &&
    isset($_POST["username"], $_POST["email"], $_POST["message"])) {

    $username = htmlspecialchars(trim($_POST["username"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    if (!empty($username) && !empty($email) && !empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (username, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $message);

        if ($stmt->execute()) {
            $success = "Mesajınız başarıyla gönderildi!";
        } else {
            $error = "Mesaj gönderilirken bir hata oluştu.";
        }
    } else {
        $error = "Lütfen tüm alanları doldurunuz.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Bize Ulaşın</title>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        /* Genel sayfa stilleri */
        body {
            font-family: 'Open Sans', sans-serif;
            background: linear-gradient(135deg, #a0a0f1, #6a4c93); /* Mor renk geçişi */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            color: white;
        }

        /* Form container */
        .form-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            color: #333;
            display: flex;
            flex-direction: column;
            align-items: center; /* Formu ortalar */
        }

        /* Başlık */
        h2 {
            font-size: 28px;
            color: #6a4c93; /* Koyu mor başlık rengi */
            margin-bottom: 20px;
            font-weight: 600;
        }

        /* Input ve Textarea */
        input, textarea {
            width: 100%;
            padding: 14px;
            margin-top: 12px;
            margin-bottom: 12px; /* Textbox'lar arasına boşluk ekledim */
            border-radius: 10px;
            border: 2px solid #6a4c93; /* Koyu mor kenarlık */
            font-size: 16px;
            transition: all 0.3s ease;
            background-color: #f3e5f5; /* Açık mor arka plan */
        }

        /* Focus efektleri */
        input:focus, textarea:focus {
            border-color: #8e44ad; /* Hafif mor focus efekti */
            outline: none;
            background-color: #e1bee7; /* Açık mor arka plan */
        }

        /* Button */
        button, .back-button {
            background-color: #8e44ad; /* Mor buton rengi */
            color: white;
            padding: 14px 25px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            margin-top: 15px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        /* Hover efektleri */
        .back-button:hover, button:hover {
            background-color: #6a4c93; /* Koyu mor hover rengi */
        }

        /* Success ve error mesajları */
        .success {
            color: green;
            margin-top: 15px;
        }

        .error {
            color: red;
            margin-top: 15px;
        }

        /* Mobil uyumlu tasarım */
        @media screen and (max-width: 768px) {
            .form-container {
                padding: 30px;
                width: 90%;
            }

            h2 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Bize Ulaşın</h2>

    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST" action="">
        <input type="text" name="username" placeholder="Adınız Soyadınız" required 
               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        <input type="email" name="email" placeholder="E-posta Adresiniz" required
               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        <textarea name="message" rows="5" placeholder="Mesajınız..." required><?php 
            echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
        <button type="submit">Gönder</button>
    </form>

    <!-- Anasayfaya Dön Butonu -->
    <a href="index.php" class="back-button">Anasayfaya Dön</a>
</div>

</body>
</html>
