<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
</head>
<body>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th,td {
            text-align: left;
            padding: 8px;
        }
        th {background-color: #ddd;}
        tr:nth-child(even) {background-color: #f2f2f2;}
        td {border: 1px solid #ddd;}
    </style>
</body>
</html>
<?php

session_start();

if (isset($_SESSION['user'])) {
    
    if ($_SESSION['user'] -> ROLE == "admin") {

        echo 'Welcome Admin: ' . $_SESSION['user']->firstName;

        echo '<form method="POST">
                <button type="submit" name="logout">Logout</button>
                <button type="submit" name="update">Update Information</button>
                <button type="submit" name="addUser">Add User</button>
            </form>';
            
            
        require "search.php";

        include "./connection.php";

        $showUsers = $database->prepare("SELECT * FROM users WHERE ACTIVATED=1 AND ROLE != 'admin'");
        $showUsers->execute();
        
        echo "<table>";
        echo "<tr><th>First Name</th><th>Last Name</th><th>Profile</th><th>Update Data</th><th>Delete User</th></tr>";
        
        foreach($showUsers as $user){
            echo "<tr>";
            echo "<td>".$user['firstName']."</td>";
            echo "<td>".$user['lastName']."</td>";
            echo '<td><form method="POST">
                      <button type="submit" name="profile" value="' . $user['id'] . '">Profile</button>
                  </form></td>';
            echo '<td><form method="POST">
                      <button type="submit" name="updateUserInfo" value="' . $user['id'] . '">Update Data</button>
                  </form></td>';
            echo '<td><form method="POST">
                      <button type="submit" name="delete" value="' . $user['id'] . '">Delete User</button>
                  </form></td>';
            echo "</tr>";
        }
        
        echo "</table>";
        
        if (isset($_POST['profile'])) {
            $_SESSION['user']->id = $_POST['profile'];
            header("Location: profile.php");
        }    
        if (isset($_POST['updateUserInfo'])) {
            $_SESSION['user']->role = $_POST['update'];
            header("Location: updateInfo.php");
        }
        
        if (isset($_POST['delete'])) {
            $deletePosts = $database->prepare("DELETE FROM todo WHERE user_ID  = :id");
            $deletePosts->bindParam("id", $_POST['delete']);

            $deleteUser = $database->prepare("DELETE FROM users WHERE id=:id");
            $deleteUser->bindParam(":id", $_POST['delete']);

            if ( $deletePosts->execute() && $deleteUser->execute()) {
                echo 'User Deleted Successfully!';
            }
            else 
                echo 'Some error';
}
        


        if (isset($_POST['logout'])) {

            session_destroy();
            session_unset();
            header("Location: http://localhost/blog/loginPage.php");

        }
        if (isset($_POST['update'])) {
            header("Location: updateInfo.php");
        }
        if (isset($_POST['addUser'])) {
            header("Location: addUser.php");
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