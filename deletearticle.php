<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
if(!isset($dbconfig))
    $dbconfig = parse_ini_file("db.ini");
$dbdel = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
$id = $_GET["articleid"];
if(!$dbdel)
{
    $error = "Unable to modify article #{$id} because of DB Error:<br>".mysqli_connect_error();
    $_SESSION["error"] = $error;
    header("Location: /index.php");
    echo $error;
    die;
}
$query = "SELECT location FROM attachments WHERE articleid ={$id}";
if($article_results = mysqli_query($dbdel, $query))
{
    while($row = mysqli_fetch_array($article_results))
    {
        unlink($row[0]);
    }
}

$query = "DELETE FROM attachments WHERE attachments.articleid = {$id}";
mysqli_query($dbdel, $query);

$query = "DELETE FROM articles where articleid = {$id}";
if($result = mysqli_query($dbdel, $query))
{
    $_SESSION["message"]="Article deleted succesfully!";
    header("Location: /index.php");
    mysqli_close($dbdel);
    die;
}
else
{
    $error = "Unable to delete article #{$id}<br/>DB:".mysqli_error($result);
    $_SESSION["error"] = $error;
    header("Location: /index.php?modify&articleid={$id}");
    mysqli_close($dbdel);
    die;
}

?>