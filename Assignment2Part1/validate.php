<?php
$email = $_POST['email'];
$password = $_POST['password'];
//Step 1 - connect to the DB
require_once ('db.php');
//Step 2 - build the sql command
$sql = "SELECT * FROM users WHERE email = :email";
//Step 3 - bind the parameters and execute
$cmd = $conn->prepare($sql);
$cmd->bindParam(':email',$email,PDO::PARAM_STR, 120);
$cmd->execute();
$user = $cmd->fetch();
//step 4 - validate the user
if (password_verify($password, $user['password'])){
    //excellent we have a valid password
    session_start();
    $_SESSION['email']  = $user['email'];
    $_SESSION['userName'] = $user['userName'];
    header('location:albums.php');
}
else{
    //user was not found or did not have a valid password
    header('location:login.php?invalid=true');
    exit();
}
//step 5 - disconnect from the db
$conn=null;
?>