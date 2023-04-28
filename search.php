
<?php

// Search for users
// Updater User's information -> will added
// Delete Users

session_reset();


if ($_SESSION['user']->ROLE == 'admin') {
  
        include "connection.php";
        
        
        echo '
        <form method="GET">
            <input type="text" name="search" required placeholder="search about user">
            <button type="submit" name="search-btn" value="Searching_About_user">Search</button>
        </form>';

        $serchField = @("%" . $_GET['search']) OR die();
        $search = $database->prepare("SELECT * FROM users WHERE firstName LIKE :name");
        $search->bindParam("name", $serchField);
        
        $search->execute();
        $userr = $search->fetchObject();
        $_SESSION['userInfo'] = $userr;

        function showData($userr)
        {
            echo 'id: ' . $userr->id . '<br>';
            echo 'First Name: ' . $userr->firstName . '<br>';
            echo 'Last Name: ' . $userr->lastName . '<br>';
            echo 'Gender: ' . $userr->Gender . '<br>';
            echo 'Email: ' . $userr->Email . '<br>';
        }


        if (isset($_GET['search-btn'])) {

            if (($search->rowCount()>0)) {

                echo 'User Found<br>';

                if(strtolower($_SESSION['user']->firstName) == strtolower($_GET['search'])){

                    echo 'You cannot delete<br>';
                    //echo $userr->id;
                    showData($userr);
                    echo '
                    <form method="POST">
                        <button type="submit" name="profile" value="' . $userr->id . '">Profile</button>
                    </form>';
                    
                    if (isset($_POST['profile'])) {
                        $_SESSION['user']->id = $userr->id;
                        //session_unset();
                        header("Location: profile.php");
                    }
    
                } else {

                    showData($userr);
                    //echo $userr->id;
                    echo '
                    <form method="POST">
                        <button type="submit" name="delete" value="' . $userr->id . '">Delete User</button>
                        <button type="submit" name="profile" value="' . $userr->id . '">Profile</button>
                        <button type="submit" name="userInfo" value="' . $userr->id . '">Update user info</button>
                    </form>';
                    
                    if (isset($_POST['profile'])) {

                        $_SESSION['user']->id = $userr->id;
                        header("Location: profile.php");

                    }
                    if (isset($_POST['delete'])) {

                        $deletePosts = $database->prepare("DELETE FROM todo WHERE user_ID  = :id");
                        $deletePosts->bindParam("id", $userr->id);
                        
                        $deleteUser = $database->prepare("DELETE FROM users WHERE id = :id");
                        $deleteUser->bindParam("id", $userr->id);
            
                        if ( $deletePosts->execute() && $deleteUser->execute()) {
                            echo 'User Deleted Successfully!';
                        }
                        else 
                            echo 'Some error';
                    }
                    if (isset($_POST['userInfo'])) {
                        // session_abort();
                        session_commit();
                        echo '
                        <form method="POST">
                            <label for="1">First Name </label>
                            <input type="text" id="1" name="userFName" placeholder="first name" value="' . $userr->firstName . '"required/>
                            <br>
                            <label for="2">Last Name </label>
                            <input type="text" id="2" name="userLName" placeholder="last name" value="' . $userr->lastName . '"required/>
                            <br>
                            <label for="3">Birth Date </label>
                            <input type="date" id="3" name="userBD" value="' . $userr->birthDate . '"required/>
                            <br>
                            <label for="4">Email </label>
                            <input type="email" id="4" name="userEmail" placeholder="email" value="' . $userr->Email . '"required/>
                            <br>
                            <label for="5">Password </label>
                            <input type="text" id="5" name="userPass" placeholder="password" required/>
                            <br>
                            <button type="submit" name="save" value = "' . $userr->id . '">Save</button>
                        </form>';
                        echo $userr->id;

                        if (isset($_POST['save'])) {    // مش شغالة بنت الاحبة
                    
                            $save = $database->prepare("UPDATE users SET 
                                firstName = :userFName, lastName = :userLName, birthDate = :userBD, Email = :userEmail, Password = :userPass
                                WHERE id = :ID");
                    
                            $save->bindParam("userFName", $_POST['userFName']);
                            $save->bindParam("userLName", $_POST['userLName']);
                            $save->bindParam("userBD", $_POST['userBD']);
                            $save->bindParam("userEmail", $_POST['userEmail']);
                            $save->bindParam("userPass", sha1($_POST['userPass']));
                            $save->bindParam("ID", $userr->id);
                                                
                            if ($save->execute()) {     // او دي
                    
                                echo 'Edited Successfully';

                            } else {
                            echo "aaaaaaaaaaaaaaaa";
                        }
                    } else {
                        echo "???????";
                    }
                }
            }
        } else {
            echo 'user not found<br>';
        }
    }
}

else {
    header("Location: http://localhost/blog/loginPage.php");
    die("");
}
// echo $_SESSION['userInfo']->id;
// echo $userr->id;
?> 
