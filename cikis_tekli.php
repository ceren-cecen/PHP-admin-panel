<?php
session_start();
require "db.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SESSION["user_id"] == $id) {
        session_unset(); 
        session_destroy(); 

        setcookie("user", "", time() - 3600, "/"); 
        setcookie("user_id", "", time() - 3600, "/"); 
    }

    if (isset($_SESSION['id_array'])) {
        
        $key = array_search($id, $_SESSION['id_array']);
        if ($key !== false) {
            unset($_SESSION['id_array'][$key]); 
        }
    }

    if (isset($_SESSION['array'][$id])) {
        unset($_SESSION['array'][$id]); 
    }

    $stmt = $conn->prepare("DELETE FROM user_sessions WHERE user_id = ?");
    $stmt->bind_param("i", $id); 
    $stmt->execute();

    header("Location: oturum.php");
    exit();
} else {
    echo "Geçersiz işlem.";
}
?>
