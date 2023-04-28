
<?php

session_start();

if (isset($_SESSION['user'])) {
    
    if ($_SESSION['user'] -> ROLE == "user") {
        
        echo 'Welcome user: ' . $_SESSION['user']->firstName;

        echo '<form method="POST">
                <button type="submit" name="logout">Logout</button>
             </form>';
             
        echo '<form method="POST">
             <button type="submit" name="update">Update Information</button>
            </form>';

        session_commit();
        include_once "profile.php";

        if (isset($_POST['logout'])) {

            session_destroy();
            session_unset();
            header("Location: http://localhost/blog/loginPage.php");

        }
        if (isset($_POST['update'])) {
            header("Location: http://localhost/blog/updateInfo.php");
        }


    }
    else {
        header("Location: http://localhost/blog/loginPage.php");
        die("");
    }

}
else {
    header("Location: http://localhost/blog/loginPage.php");
    die("");
}  

?>