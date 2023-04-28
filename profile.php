<?php

// this file to let Admin delete posts from user's profile

session_start();

if (isset($_SESSION['user'])) {

    $user_ID = $_SESSION['user']->id;

    echo '
    <form method="POST">
        <label for="1">Write Post</label><input type="text" id="1" name="text"/>
        <button type="submit" name="post" value="' . $user_ID . '">Post</button>
    </form>';

    if(isset($_POST['post'])){

        include "connection.php";

        $addPost = $database->prepare("INSERT INTO todo(text, user_ID) VALUES(:newPost, :user_ID)");

        $addPost->bindParam("newPost", $_POST['text']);
        $addPost->bindParam("user_ID", $_POST['post']);

        if($addPost->execute()){
            echo "Posted Successfully<br>";

            $datae = new PDO("mysql:host=$host; dbname=$dbName; charset=utf8", $username, $password);
        }
    }
    showUserPosts();
}
else 
{
    header("Location: http://localhost/blog/loginPage.php");
    die("");
}  
function showUserPosts()
{
    $user_ID = $_SESSION['user']->id;
    echo $user_ID . "<br>";

    include "connection.php";

    $profile = $database->prepare("SELECT * FROM todo WHERE user_ID = :id");
    $profile->bindParam("id", $user_ID);
    $profile->execute();

    // Table headers
    echo"<table>
            <tr>
                <th>ID</th>
                <th>Post</th>
                <th>Delete</th>
                <th>Edit</th>
            </tr>
        ";

    foreach ($profile as $value) {
        echo "<tr>";
        echo "<td>" . $value['id'] . '</td>';
        echo "<td>" . $value['text'] . '</td>';
        
        // Form for edit and delete buttons
        echo '
            
            <form method="POST">
                <td><button type="submit" name="deletePost" value="' . $value['id'] . '">Delete Post</button></td>
                <td><button type="submit" name="editPost" value="' . $value['id'] . '">Edit Post</button></td>
            </form>';
        
        // Check if edit button is clicked for this post
        if (isset($_POST['editPost']) && $_POST['editPost'] == $value['id']){
            echo'
                <form method="POST">
                    <input type="text" name="new" value="' . $value['text'] . '" required/>
                    <button type="submit" name="updatePost" value="' . $value['id'] . '">Update</button>
                </form>';
            
            // Check if update button is clicked for this post
            if (isset($_POST['updatePost']) && $_POST['updatePost'] == $value['id']) {          
                $editPost = $database->prepare("UPDATE todo SET text = :newPost  WHERE id= :id2");
                $editPost->bindParam("newPost", $_POST['new']);
                $editPost->bindParam("id2", $_POST['updatePost']);
                
                if($editPost->execute()){
                    echo "Post Edited Successfully";
                    //header("refresh:1; url=profile.php");
                }
                else {
                    echo 'Error editing post';
                }          
            }    
        }
        else {
        }
        echo "</tr>";
    }
    echo "</table>";

    // Check if delete button is clicked for any post
    if (isset($_POST['deletePost'])) {
        $deletePost = $database->prepare("DELETE FROM todo WHERE id= :id");
        $deletePost->bindParam("id", $_POST['deletePost']);
    
        if($deletePost->execute()){
            echo "Post Deleted Successfully";
        }
        else {
            echo 'Error deleting post';
        }
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
</head>
<body>
</body>
</html>