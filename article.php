<?php
$dbconfig = parse_ini_file("db.ini");
$db = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
if(!$db)
{
    $error = "Unable to connect database!<br>".mysqli_connect_error();
    header("Location: /index.php");
    die;
}
else
{
    if(isset($_GET["articleid"]))
        $id=$_GET["articleid"];
    else
        $id = 1;
    if($article_results = mysqli_query($db, "SELECT articles.articleid, articles.title, articles.content, articles.created_at, users.username, owner FROM articles INNER JOIN users ON articles.owner = users.userid WHERE articleid=".$id))
    {
        while($row = mysqli_fetch_array($article_results))
        {
            ?>
                <div class="row">
                    <div class="col-8">
                        <h3 class="text-uppercase"><?= $row["title"] ?>
                        <?php if($_SESSION["role"]==5 || $row["owner"] == $_SESSION["userid"]) :?>
                            <a class="btn btn-danger float-right mx-2" href="/index.php?delete&articleid=<?= $id ?>"><i class="far fa-trash-alt"></i></a>
                            <a class="btn btn-primary float-right" href="/index.php?modify&articleid=<?= $id ?>"><i class="far fa-edit"></i></a>
                            
                        <?php endif;?>
                        </h3>
                        
                        <?= $row["content"] ?>
                        <blockquote class="blockquote mb-0">
                                <footer class="blockquote-footer text-right">Article created at <?= $row["created_at"] ?> by <cite title="Source Title"><?= $row["username"] ?></cite></footer>
                        </blockquote>
                        <?php include("comments.php"); ?>
                    </div>
                    <div class="col-4">
                        <?php include("attachments.php"); ?>
                    </div>
                </div>
            <?php
        }
        mysqli_free_result($article_results);
    }
}
mysqli_close($db);
?>