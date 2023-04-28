<?php

if (!isset($_GET['code'])) {
    
    echo '
    <div>Enter Your Email</div>
    <form method="POST">
        <label for="e">email</label><input id="e" type="email" name="email" required>
        <br>
        <button type="submit" name="reset">Send Reset Link</button>
    </form>';

}

else if (isset($_GET['code']) && isset($_GET['email'])) {

    echo '
    <div>Enter The New Password</div>
    <form method="POST">
        <label for="pass">Password</label><input id="pass" type="text" name="password" required/>
        <br>
        <button type="submit" name="newPassword">Reset</button>
    </form>';

}
?>


<?php

if (isset($_POST['reset'])) {

    include "connection.php";

    $checkEmail = $database->prepare("SELECT Email, SECURITY_CODE FROM users WHERE Email= :email");
    $checkEmail->bindParam("email",$_POST['email']);
    $checkEmail->execute();

    if ($checkEmail->rowCount() > 0) {
        
        echo "This Account is exists";

        require_once 'mail.php';

        $user = $checkEmail->fetchObject();

        $email = $_POST['email'];
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = "Password Reset link";
        $mail->Body = '<h1>Remember Password!</h1>'
         . '<div>Reset Link </div>' 
         . '<a href="http://localhost/blog/resetPassword.php?email=' . $_POST['email'] 
         . '&code=' . $user->SECURITY_CODE. '">http://localhost/blog/resetPassword.php?email=' . $_POST['email'] 
         . '&code=' . $user->SECURITY_CODE. '</a>';
        ;

        $mail->setFrom("hadyasaker8@gmail.com", "Test COMPANY");
        $mail->send();
        echo 'Reset Password Link sent';

    } else {

        echo "Email not found";

    }
}
?>

<?php

if (isset($_POST['newPassword'])) {

    include "connection.php";

    $newPassword = $database->prepare("UPDATE users SET Password = :password WHERE Email = :email");
    $newPassword->bindParam("password",$_POST['password']);
    $newPassword->bindParam("email",$_GET['email']);

    if ($newPassword->execute()) {

        echo "Your Password Is Changed Successfully";
        
    } else {
        echo "Some Error Exists";
    }

}
?>