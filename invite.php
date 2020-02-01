<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
if($_SESSION["role"]>3)
{
    $code = RandomString(5)."-".RandomString(5)."-".RandomString(5)."-".RandomString(5);
    $valid = date ("Y-m-d H:i:s", strtotime('+2 weeks'));
    $query = "INSERT INTO invitations (code, valid_to) VALUES('{$code}', '{$valid}')";
    $dbconfig = parse_ini_file("db.ini");
    $dbinvi = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
    if(!$dbinvi)
    {
        $error = "Unable to connect database!".mysqli_connect_error();
        header("Location: /index.php");
        die;
    }
    else
    {
        if($result = mysqli_query($dbinvi, $query))
        {
            $_SESSION["message"] = "Please send following code <b>{$code}</b> to invited person(s). Invitation is valid until <b>{$valid}.</b>";
            header("Location: /index.php");
            die;
        }
        else
        {
            $_SESSION["error"] = "Unknown error while generating invitation.";
            header("Location: /index.php");
            die;
        }
    }
}
else
{
    $_SESSION["error"] = "Only participants and administrators can invite quests.";
    header("Location: /index.php");
    die;
}

function RandomString($len=32)
{
    $characters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $randstring = '';
    for ($i = 0; $i < $len; $i++) { 
        $index = rand(0, strlen($characters) - 1); 
        $randstring .= $characters[$index]; 
    } 
    return $randstring;
}

?>