<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="./registerStyle.css">
    <link rel="stylesheet" href="./valid.css">
</head>

<body>
    <div class="container">
        <div class="title">
            <p>Registration</p>
        </div>

        <form method="POST">
            <div class="user_details">
                <div class="input_box">
                    <label for="1" class="form-label">First Name </label>
                    <input type="text" id="1" class="form-control" name="firstName" placeholder="first name" required>
                </div>
                <div class="input_box">
                    <label for="2">Last Name </label>
                    <input type="text" id="2" name="lastName" placeholder="last name" required>
                </div>
                <div class="input_box email">
                    <label for="4">Email </label>
                    <input type="email" id="4" name="email" placeholder="email" required>
                </div>
                <div class="input_box password">
                    <label for="5">Password </label>
                    <input type="password" id="5" name="password" placeholder="password" required>
                </div>
                <div class="input_box">
                    <label for="3">Birth Date </label>
                    <input type="date" id="3" name="birthDate" required>
                </div>
            </div>
            <!-- <br class="input_box"> -->
            <div class="gender" style="margin-right: 800px;">
                <label for="radio_1"><span class="gender_title">Gender</span></label>
                <input type="radio" name="gender" id="radio_1" value="male">
                <input type="radio" name="gender" id="radio_2" value="female">

                <div class="category">
                    <label for="radio_1">
                        <span class="dot one"></span>
                        <span>Male</span>
                    </label>
                    <label for="radio_2" style="margin-left: 25px;">
                        <span class="dot two"></span>
                        <span>Female</span>
                    </label>
                </div>
            </div>
            <div class="reg_btn" style="display: inline-block;">
                <input type="submit" name="register" value="Register" />
            </div>
            <div class="reg_btn" style="display: inline-block;">
                <input type="button" class="submit" onclick="window.location.href='http:loginPage.php';" value="Login" />
            </div>
        </form>
    </div>
</body>

</html>

<?php

include_once "connection.php";

if (isset($_POST['register'])) {

    $checkEmail = $database->prepare("SELECT * FROM users WHERE Email = :EMAIL");
    $email = $_POST['email'];
    $checkEmail->bindParam('EMAIL', $email);
    $checkEmail->execute();

    if ($checkEmail->rowCount() > 0) {
        echo 'This Email is used<br>';
    } else {

        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $gender = $_POST['gender'];
        $birthDate = $_POST['birthDate'];
        $email = $_POST['email'];
        $password = sha1($_POST['password']);

        $register = $database->prepare("INSERT INTO 
        users(firstName, lastName, Gender, birthDate, Email, Password, SECURITY_CODE, ROLE) 
        VALUES(:first, :last, :gender, :date, :email, :pass, :SECURITY_CODE, 'user')");

        $register->bindParam('first', $firstName);
        $register->bindParam('last', $lastName);
        $register->bindParam('gender', $gender);
        $register->bindParam('date', $birthDate);
        $register->bindParam('email', $email);
        $register->bindParam('pass', $password);

        $securityCode = md5(date("H:i:s"));
        $register->bindParam('SECURITY_CODE', $securityCode);

        if ($register->execute()) {

            echo 'Register Successfully<br>';

            require_once "mail.php";
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Account verification link";
            $mail->Body = '<h1>Thanks For Registration</h1>'
                . 'verification Link '
                . '<a href ="http://localhost/blog/Active.php?code=' . $securityCode . '">'
                . 'http://localhost/blog/Active.php' . '?code=' . $securityCode . '</a>';;

            $mail->setFrom("hadyasaker8@gmail.com", "Test COMPANY");
            $mail->send();

            //sleep(2);
            //header("Location: loginPage.php");
            exit();
        } else {
            echo 'Some Error exists</br>';
        }
    }
}

?>