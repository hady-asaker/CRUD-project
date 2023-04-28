<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- Bootstrap JS -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


</head>

<body>
    <h1>This Page To Let Admin To Add User</h1>
    <form method="POST">
        <div class="row g-3">
            <div class="col">
                <label for="1" class="form-label">First Name </label><input type="text" class="form-control"  id="1" name="firstName" placeholder="first name" required>
            </div>
            <div class="col">
                <label for="2" class="form-label">Last Name </label><input type="text" class="form-control" style="width: 99%;" id="2" name="lastName" placeholder="last name" required>
            </div>
        </div>

        <br>
        <label for="4" class="form-label">Email </label><input type="email" class="form-control" style="width: 99.5%;" id="4" name="email" placeholder="email" required>
        <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>

        <br>
        <label for="5" class="form-label">Password </label><input type="password" class="form-control" style="width: 99.5%;" aria-describedby="passwordHelpBlock" id="5" name="password" placeholder="password" required>
        <div id="passwordHelpBlock" class="form-text">
        Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
        </div>

        <br>
        <label for="3" class="form-label">Birth Date </label><input type="date" class="form-control" style="width: 99.5%;" id="3" name="birthDate" required>

        <br>

        <fieldset class="row mb-3">
            <label class="col-form-label col-sm-1 pt-0" for="m">Gender</label>
            <div class="col-sm-10">
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="gender" id="m" value="male" required>
                    <label for="m" class="form-check-label">Male</label>
                </div>
                <div class="form-check">
                    <input type="radio" class="form-check-input" name="gender" id="f" value="female" required>
                    <label for="f" class="form-check-label">Female</label>
                </div>
            </div>
        </fieldset>

        <br>
        <button type="submit" class="btn btn-primary" name="addUser">Add User</button>
        <a href="adminPage.php" class="btn btn-secondary">Return</a>
        
    </form>

</body>
</html>

<?php

include_once "connection.php";

session_start();

if ($_SESSION['user'] -> ROLE == "admin") {

    if (isset($_POST['addUser'])) {

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

            $addUser = $database->prepare("INSERT INTO 
            users(firstName, lastName, Gender, birthDate, Email, Password, SECURITY_CODE, ROLE, ACTIVATED) 
            VALUES(:first, :last, :gender, :date, :email, :pass, :SECURITY_CODE, 'user', 1)");

            $addUser->bindParam('first', $firstName);
            $addUser->bindParam('last', $lastName);
            $addUser->bindParam('gender', $gender);
            $addUser->bindParam('date', $birthDate);
            $addUser->bindParam('email', $email);
            $addUser->bindParam('pass', $password);

            $securityCode = md5(date("H:i:s"));
            $addUser->bindParam('SECURITY_CODE', $securityCode);

            if ($addUser->execute()) {

                echo 'User Added Successfully<br>';

                //header("Location: loginPage.php");
                //exit();
            } else {
                echo 'Some Error exists</br>';
            }
        }
    }
}
else {
    header("Location: http://localhost/blog/loginPage.php");
    die("");
}
?>