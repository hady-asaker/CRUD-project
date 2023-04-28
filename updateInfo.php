<?php

session_start();

if ($_SESSION['user']) {

    echo '
        <form method="POST">
            <label for="1">First Name </label>
            <input type="text" id="1" name="firstName" placeholder="first name" value="' . $_SESSION['user']->firstName . '"/>
            <br>
            <label for="2">Last Name </label>
            <input type="text" id="2" name="lastName" placeholder="last name" value="' . $_SESSION['user']->lastName . '"/>
            <br>
            <label for="3">Birth Date </label>
            <input type="date" id="3" name="birthDate" value="' . $_SESSION['user']->birthDate . '"/>
            <br>
            <label for="4">Email </label>
            <input type="email" id="4" name="email" placeholder="email" value="' . $_SESSION['user']->Email . '"/>
            <br>
            <label for="5">Password </label>
            <input type="text" id="5" name="password" placeholder="password" />
            <br>
            <button type="submit" name="update" value="' . $_SESSION['user']->id . '">Update Information</button>
            <a href="' . $_SERVER["HTTP_REFERER"] . '">Return</a>
            </form>';

    if (isset($_POST['update'])) {

        include_once "connection.php";

        $update = $database->prepare("UPDATE users SET 
            firstName = :firstName, lastName = :lastName, birthDate = :birthDate, Email = :Email, Password = :Password
            WHERE id = :id");

        $update->bindParam("firstName", $_POST['firstName']);
        $update->bindParam("lastName", $_POST['lastName']);
        $update->bindParam("birthDate", $_POST['birthDate']);
        $update->bindParam("Email", $_POST['email']);
        $update->bindParam("Password", sha1($_POST['password']));
        $update->bindParam("id", $_POST['update']);

        if ($update->execute()) {

            echo 'Edited Successfully';

            $user = $database->prepare("SELECT * FROM users WHERE id = :id");
            $user->bindParam("id", $_POST['update']);

            $user->execute();
            $_SESSION['user'] = $user->fetchObject();
            if ($_SESSION['user']->ROLE == 'user') {
                header("location: userPage.php");
            } else if ($_SESSION['user']->ROLE == 'admin') {
                header("location: adminPage.php");
            }
        } else {
            echo 'Error';
        }
    }
    
} else {
    session_unset();
    session_destroy();
    header("Location: http://localhost/blog/loginPage.php", true);
}
