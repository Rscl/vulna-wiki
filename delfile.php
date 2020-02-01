<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
if(!isset($dbconfig))
    $dbconfig = parse_ini_file("db.ini");
$dbdelfile = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
$id = $_GET["articleid"];
$fileid = $_GET["attachmentid"];

if(!$dbdelfile)
{
    $error = "Unable to delete attachment #{$fileid} because of DB Error:<br>".mysqli_connect_error();
    $_SESSION["error"] = $error;
    header("Location: /index.php");
    die;
}
$query = "SELECT location FROM attachments WHERE attachmentid ={$fileid}";
if($article_results = mysqli_query($dbdelfile, $query))
{
    while($row = mysqli_fetch_array($article_results))
    {
        unlink($row[0]);
    }
}

$query = "DELETE FROM attachments WHERE attachments.attachmentid = {$fileid}";
if($result = mysqli_query($dbdelfile, $query))
{
    $_SESSION["message"]="File deleted succesfully!";
    header("Location: /index.php?modify&articleid={$id}");
    mysqli_free_result($result);
    mysqli_close($db);
    die;
}
else{
    $_SESSION["error"]="File delete failed!";
    header("Location: /index.php?modify&articleid={$id}");
    mysqli_close($db);
    die;
}
?>