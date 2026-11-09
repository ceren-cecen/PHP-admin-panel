<?php
    session_start();
    require "C:\\xampp\\htdocs\\ceren\\db.php"; 

    function getIpAddressFromCmd() {
       
        $output = shell_exec('ipconfig | findstr /i "IPv4"');
        $output = trim($output);
        
        
        preg_match('/(\d+\.\d+\.\d+\.\d+)/', $output, $matches);
        
        return isset($matches[0]) ? $matches[0] : null;
    }

   
    $localIp = getIpAddressFromCmd();

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $cookie_id = isset($_COOKIE['user_id']) ? $_COOKIE['user_id'] : null;

    if ($localIp) {
        
        $stmt = $conn->prepare("DELETE FROM user_sessions WHERE ip_address = ?");
        $stmt->bind_param("s", $localIp);
        $stmt->execute();
    }

    
    if ($user_id || $cookie_id) {
        $id_to_delete = $user_id ? $user_id : $cookie_id;

       
        $stmt = $conn->prepare("SELECT ip_address FROM user_sessions WHERE user_id = ?");
        $stmt->bind_param("i", $id_to_delete);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            
            $row = $result->fetch_array();
            $ip_address = $row['ip_address'];

            
            $stmt = $conn->prepare("DELETE FROM user_sessions WHERE user_id = ? AND ip_address = ?");
            $stmt->bind_param("is", $id_to_delete, $ip_address);
            $stmt->execute();
        }
    }

    
    session_unset(); 
    session_destroy(); 

    setcookie("user", "", time() - 3600, "/");  
    setcookie("user_id", "", time() - 3600, "/"); 

    header("Location: index.php");
    exit();
?>
