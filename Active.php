<?php

if (isset($_GET['code'])) {
    
    include_once "connection.php";

    $checkCode = $database->prepare("SELECT SECURITY_CODE from users WHERE SECURITY_CODE= :SECURITY_CODE");
    $checkCode->bindParam("SECURITY_CODE", $_GET['code']);
    $checkCode->execute();

    if($checkCode->rowCount() > 0){

        $update = $database->prepare("UPDATE user SET SECURITY_CODE = :NEWSECURITY_CODE, ACTIVATED='1' 
        WHERE SECURITY_CODE= :SECURITY_CODE " );

        $update->bindParam("NEWSECURITY_CODE",$securityCode);
        $update->bindParam("SECURITY_CODE",$_GET['code']);

        if ($update->execute()) {
            echo "Your account has been verified successfully";
            echo "<a href='loginPage.php' >Login</a>";
        }

    }
    else {
        echo "This page has expired ";
    }

}
?>