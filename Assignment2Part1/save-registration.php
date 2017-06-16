<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registering User</title>
</head>
<body>
<?php
$email = $_POST['email'];
$password = $_POST['password'];
$confirm = $_POST['confirm'];
$userName = $_POST['username'];
$ok = true;
//check if the passwords match
if ($password != $confirm)
{
    echo 'The passwords do not match <br />';
    $ok = false;
}
if (strlen($password) < 8)
{
    echo 'The password is too short, must be 8 or more characters
                        <br />';
    $ok = false;
}
if (empty($email))
{
    echo 'You must enter an email address <br />';
    $ok = false;
}
//if the email and password are ok
if ($ok)
{
    //connect to the DB and setup the new user
    //Step 1 - connect to the DB
    require_once ('db.php');
    //Step 2 - create the SQL command
    $sql = "INSERT INTO users VALUES (:email, :username, :password)";
    //Step 2.5 - hash the password
    $password = password_hash($password, PASSWORD_DEFAULT);
    //Step 3 - prepare and execute the SQL
    $cmd = $conn->prepare($sql);
    $cmd->bindParam(':email', $email, PDO::PARAM_STR, 120);
    $cmd->bindParam(':username', $userName, PDO::PARAM_STR, 100);
    $cmd->bindParam(':password', $password, PDO::PARAM_STR, 255);
    try{
        $cmd->execute();
    }
    catch (Exception $e)
    {
        if (strpos($e->getMessage(),
                'Integrity constraint violation: 1062') == true){
            header('location:registration.php?errorMessage=email-already-exists');
        }
    }
    //Step 4 - disconnect from the DB
    $conn = null;
    //Step 5 - redirect to the login page
    header('location:login.php');
}
?>
</body>
<html/>