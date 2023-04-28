<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD | Login</title>
    <link rel="stylesheet" href="loginStyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>

<body>
    <div class="box">
        <div class="container">

            <div class="top">
                <span>Have an account?</span>
                <header>Login</header>
            </div>

            <form method="POST">

                <div class="input-field">
                    <input id="name" class="input" type="email" name="email" placeholder="Email" required autofocus>
                    <i class='bx bx-user'></i>
                </div>
                <br>
                <div class="input-field">
                    <input id="pass" class="input" type="password" name="password" placeholder="Password" required>
                    <i class='bx bx-lock-alt'></i>
                </div>
                <br>
                <button type="submit" class="submit" name="login">login</button></br></br>
                <input type="button" class="submit" onclick="window.location.href='http:Registration.php';" value="Register" /></br></br>
                <div class="two-col">
                    <div class="one">
                        <input type="checkbox" name="remember" id="check">
                        <label for="check"> Remember Me</label>
                    </div>
                    <div class="two">
                        <label><a href="resetPassword.php">Forgot password?</a></label>
                    </div>
                </div>
            </form>
        </div>
    </div>


</body>

</html>

<?php
session_start();

if (isset($_POST['login'])) {        

    include_once "connection.php";

    $passwordUser = sha1($_POST['password']);
    $email = $_POST['email'];
    //echo $passwordUser;

    $login = $database->prepare("SELECT * FROM users WHERE Email = :EMAIL AND Password = :PASSWORD");
    $login->bindParam("EMAIL", $email);
    $login->bindParam("PASSWORD", $passwordUser);
    $login->execute();

    // $checkEmail = $database->prepare("SELECT Email FROM users WHERE Email = :Emaill");
    // $checkEmail->bindParam("Emaill", $email);
    // $checkEmail->execute();

    // $checkPassword = $database->prepare("SELECT Password FROM users WHERE Password = :Pass");
    // $checkPassword->bindParam("Pass", $passwordUser);
    // $checkPassword->execute();

    if ($login->rowCount() > 0) {

        $user = $login->fetchObject();
        //echo $user->Gender;

        if ($user->ACTIVATED == true) {

            $_SESSION['user'] = $user;

            echo 'Hello ' . $user->firstName;

            $_SESSION['email'] = $user->Email;
            $_SESSION['password'] = sha1($user->Password);
            $_SESSION['name'] = $user->firstName;
            //...

            if ($user->ROLE == "user") {
                header("Location: userPage.php", true);
                die("");
            } else if ($user->ROLE == "admin") {
                header("Location: adminPage.php", true);
                die("");
            }
        } else {
            echo 'You must verified your account';
        }
    } else {
        echo "invalid email or password";
    }
}
//session_commit();
?>