<?php
session_start();
require "db.php"; // Veritabanı bağlantısı için db.php dosyasını dahil ediyoruz.

if (isset($_POST['send_message'])) {
    // Formdan gelen veriler
    $name = $_POST['name'];
    $email = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];

    // Veritabanına kaydetme işlemi
    $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $subject, $message);

    if ($stmt->execute()) {
        $feedback = "Mesajınız başarıyla gönderildi. Teşekkür ederiz!";
    } else {
        $feedback = "Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyin.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bize Ulaşın</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 700px;
            text-align: center;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #333;
        }

        input[type="text"], input[type="email"], textarea {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        textarea {
            width: 80%;
            padding: 10px;
            margin: 10px 0;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
            resize: vertical;
        }

        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .feedback {
            font-size: 16px;
            margin-top: 20px;
            color: green;
        }

        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Bize Ulaşın</h2>

    <!-- Mesaj gönderme formu -->
    <form method="post" action="">
        <input type="text" name="name" id="name" placeholder="Adınız" required>
        <input type="email" name="email" id="email" placeholder="E-posta" required>
        <input type="text" name="subject" id="subject" placeholder="Konu" required>
        <textarea name="message" id="message" placeholder="Mesajınızı yazın..." rows="4" required></textarea>
        <input type="submit" value="Gönder" name="send_message">
    </form>

    <!-- Form gönderildikten sonra gösterilecek geri bildirim -->
    <?php
    if (isset($feedback)) {
        echo "<p class='feedback'>$feedback</p>";
    }
    ?>

    <div class="footer">
        <p>&copy; 2025 Duyuru Sistemi. Tüm hakları saklıdır.</p>
    </div>
</div>

</body>
</html>
