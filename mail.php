<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';

if (isset($_COOKIE['username']) && isset($_COOKIE['user_id']) && isset($_COOKIE['email'])) {
    $username = $_COOKIE['username']; 
    $user_id = $_COOKIE['user_id'];   
    $email = $_COOKIE['email'];       

    if (isset($_POST["btn"])) {
        $to = isset($_POST['to']) ? trim($_POST['to']) : '';
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
        $message = isset($_POST['message']) ? trim($_POST['message']) : '';

        if (empty($to)) {
            echo "<p style='color:red;'>Alıcı E-Posta boş bırakılamaz.</p>";
        } else if (empty($name)) {
            echo "<p style='color:red;'>Ad boş bırakılamaz.</p>";
        } else if (empty($subject)) {
            echo "<p style='color:red;'>Konu boş bırakılamaz.</p>";
        } else if (empty($message)) {
            echo "<p style='color:red;'>Mesaj boş bırakılamaz.</p>";
        } else {
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'cerencecen1950@gmail.com';
                $mail->Password   = 'mdpf bofm urju trsb'; // Gerçek şifreyi burada kullanmayın
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;
                $mail->setFrom($email, $username); 
                $mail->addAddress($to);  
                $mail->Subject = $subject;
                $mail->Body    = $message;

                $mail->send();
                echo "<p style='color:green;'>Mail başarıyla gönderildi!</p>";
            } catch (Exception $e) {
                echo "<p style='color:red;'>Mail gönderirken bir hata oluştu: {$mail->ErrorInfo}</p>";
            }
        }
    }
} else {
    echo "Henüz giriş yapmamışsınız";
    exit;  
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mail Gönderme Formu</title>
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

        h2 {
            color: #9c4d97;
            margin-bottom: 20px;
            font-size: 2em;
            text-shadow: 0 0 15px rgba(156, 77, 151, 0.5);
        }

        label {
            font-size: 1.1em;
            color: #9c4d97;
            margin-bottom: 8px;
        }

        input[type="email"], input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #9c4d97;
            border-radius: 8px;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical;
        }

        button {
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

        button:hover {
            background-color: #7b1fa2;
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(123, 31, 162, 0.8);
        }

        a {
            display: inline-block;
            color: #9c4d97;
            margin-top: 10px;
            text-decoration: none;
            font-size: 1em;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #7b1fa2;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #9c4d97;
            text-shadow: 0 0 10px rgba(156, 77, 151, 0.5);
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Mail Gönderme Formu</h2>
    <form action="mail.php" method="post">
        <table>
            <tr>
                <td><label for="to">Alıcı E-Posta:</label></td>
                <td><input type="email" id="to" name="to" required value="<?php echo isset($email) ? $email : ''; ?>" readonly></td>
            </tr>
            <tr>
                <td><label for="name">Ad:</label></td>
                <td><input type="text" id="name" name="name" required value="<?php echo isset($username) ? $username : ''; ?>"></td>
            </tr>
            <tr>
                <td><label for="subject">Konu:</label></td>
                <td><input type="text" id="subject" name="subject"></td>
            </tr>
            <tr>
                <td><label for="message">Mesaj:</label></td>
                <td><textarea id="message" name="message" rows="4"></textarea></td> 
            </tr>
            <tr>
                <td><button type="submit" name="btn">Gönder</button></td>
                <td><a href="index.php">Geri dön</a></td>
            </tr>
        </table>
    </form>
</div>

</body>
</html>
