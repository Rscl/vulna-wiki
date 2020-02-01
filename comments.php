<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
$dbconfig = parse_ini_file("db.ini");
$dbcomments = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
if(!$dbcomments)
{
    $error = "Unable to connect database!".mysqli_connect_error();
    header("Location: /index.php");
    die;
}
if(isset($_GET["articleid"]))
    $id=$_GET["articleid"];
else
    $id = 1;
if(isset($_GET["delete"]) && isset($_GET["articleid"]) && isset($_GET["commentid"]))
{
    //Delete comment
    $articleid = $_GET["articleid"];
    $commentid = $_GET["commentid"];
    if(mysqli_query($dbcomments, "DELETE FROM comments WHERE articleid=".articleid." AND commentid=".$commentid))
    {
        $_SESSION["message"] = "Comment deleted succesfully";
    }
    else
    {
        $_SESSION["error"] = "Unable to delete comment";
    }
    header("Location: /index.php?articleid=".$articleid);
    mysqli_close($dbcomments);
    die;
}

if(isset($_GET["create"]) && isset($_GET["articleid"]))
{
    //New Comment
    $text = $_POST["comment_text"];
    $owner = $_SESSION["userid"];
    $articleid = $_GET["articleid"];
    if(mysqli_query($dbcomments, "INSERT INTO comments (text, owner, articleid) VALUES('{$text}', {$owner},{$articleid})"))
    {
        $_SESSION["message"] = "comment created!";
    }
    else
    {
        $_SESSION["error"] = "Unable to create comment! <br>".mysqli_error($dbcomments);;
    }
    header("Location: /index.php?articleid=".$articleid);
    mysqli_close($dbcomments);
    die;
}

if($commentresult = mysqli_query($dbcomments, "SELECT comments.commentid, comments.text, comments.created_at, users.username from comments INNER JOIN users ON comments.owner = users.userid where articleid=".$id))
{
    while($row = mysqli_fetch_array($commentresult))
    {?>
        <div class="card my-2 mx-2">
            <div class="card-body">
                <blockquote class="blockquote mb-0">
                    <?php if($row["username"] == $_SESSION["username"] || $_SESSION["role"]==5){?><a class="btn btn-danger float-right" href="/comments.php?delete&articleid=<?=$id?>&commentid=<?=$row["commentid"]?>"><i class="fas fa-trash-alt"></i></a><?php }?>
                    <p><?= $row["text"] ?></p>
                    <footer class="blockquote-footer text-right"><?= $row["created_at"] ?> by <cite title="Source Title"><?= $row["username"] ?></cite></footer>
                </blockquote>
            </div>
        </div>
    <?php }
    mysqli_free_result($commentresult);
}
else
{
    ?><p>No comments, be first to comment!</p><?php
}
?>
<div class="card my-2 mx-2">
    <div class="card-body">
        <form class="form" method="post" action="/comments.php?create&articleid=<?=$id?>">
            <div class="form-row">
                <div class="col-10"><textarea class="form-control" rows="3" placeholder="New comment" required name="comment_text"></textarea></div>
                <div class="col-2"><button type="submit" class="btn btn-primary"><i class="far fa-comment"></i></button></div>
            </div>
        </form> 
    </div>
</div>