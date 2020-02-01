<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
}
if(!isset($dbconfig))
    $dbconfig = parse_ini_file("db.ini");
$db_modify = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
$id = $_GET["articleid"];
if(!$db_modify)
{
    $error = "Unable to modify article #{$id} because of DB Error:<br>".mysqli_connect_error();
    $_SESSION["error"] = $error;
    header("Location: /index.php");
    echo $error;
    die;
}
else
{
    if(isset($_POST["articletitle"]) && isset($_POST["articletext"]))
    {
        //Update article
        
        $title = $_POST["articletitle"];
        $content = $_POST["articletext"];
        $query = "UPDATE articles SET title='{$title}', content=\"{$content}\" WHERE articleid={$id}";
        if($update_result = mysqli_query($db_modify, $query))
        {
            $message = "Article #{$id} updated.";
            $_SESSION["message"] = $message;
            header("Location: /index.php?articleid={$id}");
        }
        else
        {
            $error = "Unable to modify article #{$id}<br/>Query: {$query}<br/>DB:".mysqli_error($update_result);
            $_SESSION["error"] = $error;
            header("Location: /index.php?articleid={$id}");
            mysqli_close($db_modify);
            die;
        }
    }

    $id = $_GET["articleid"];
    $query = "SELECT articleid, title, content, category, owner, created_at FROM articles where articleid={$id}";
    $modifyresult = mysqli_query($db_modify, $query);
    if($modifyresult)
    {
        if($row = mysqli_fetch_row($modifyresult))
        {
            $title = $row[1];
            $content = $row[2];
            $category = $row[3];
            $created_at = $row[5];
            $owner = $row[4];
        }
        else
        {
            $error = "Unable to modify article #{$id}<br>".mysqli_error($modifyresult);
            $_SESSION["error"] = $error;
            header("Location: /index.php?modify&articleid={$id}");
            echo $error;
            mysqli_free_result($modifyresult);
            mysqli_close($db_modify);
            die;
        }
    }
    else
    {
        $error = "Unable to modify article #{$id}<br>Query error: ".mysqli_error($modifyresult)."<br/>Query: ".$query."<br/>Connection error:".mysqli_connect_error();
        $_SESSION["error"] = $error;
        header("Location: /index.php");
        mysqli_close($db_modify);
        die;
    }
    mysqli_free_result($modifyresult);
    mysqli_close($db_modify);
}

?>
<div class="row">
    <div class="col-8"><h3>Edit article</h3></div>
    <div class="col-4"><h3>Attachments</h3></div>
</div>
<div class="row">
    <div class="col-8">
    <form method="post" action="modifyarticle.php?articleid=<?=$id?>">
    <div class="form-group">
        <label for="articletitle">Title</label>
        <input type="text" class="form-control" id="articletitle" name="articletitle" placeholder="Article title" value="<?= $title ?>" />
        <label for="articletext">Content</label>
        <textarea class="form-control" id="articletext" name="articletext" rows="10" placeholder="Article content..."><?= $content ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary my-2">Save</button>
    <button type="reset" class="btn btn-secondary my-2">Reset</button>
    </form>
    </div>
    <div class="col-4">
        <?php
        $db_files = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
        $id = $_GET["articleid"];
        if(!$db_files)
        {
            $error = "Unable to modify article #{$id} files because of DB Error:<br>".mysqli_connect_error();
            $_SESSION["error"] = $error;
            header("Location: /index.php?articleid={$id}");
            die;
        }
        else
        {
            $query = "SELECT attachments.attachmentid, attachments.articleid, attachments.description, attachments.filename, attachments.location, attachments.created_at, users.username FROM attachments INNER JOIN users ON attachments.owner = users.userid WHERE attachments.articleid={$id}";
            if($result_files = mysqli_query($db_files, $query))
            {
                while($row = mysqli_fetch_array($result_files))
                {?>

                    <div class="card my-2">
                        <div class="card-header"><?= $row[3] ?></div>
                        <div class="card-body">
                            <p class="card-text"><?= $row[2]?></p>
                            <a href="/delfile.php?attachmentid=<?= $row[0]?>&articleid=<?= $id ?>" class="btn btn-danger mx-2"><i class="far fa-trash-alt"></i></a>
                            <a href="<?= $row[4]?>" class="btn btn-primary"><i class="fas fa-paperclip"></i></a>
                            <a href="/download.php?attachmentid=<?= $row[0]?>" class="btn btn-secondary mx-2"><i class="fas fa-file-download"></i></a>
                        </div>
                    </div>

                <?php
                }
            }
            else
            {
                $error = "Unable to fetch article {$id} files.<br>Query: {$query}";
                $_SESSION["error"] = $error;
                header("Location: /index.php?articleid={$id}");
                die;
            }
        }
        ?>
        <div class="card my-2">
            <div class="card-header">Add new file</div>
            <div class="card-body">
                <form method="post" action="/newfile.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="filedescription">Description</label>
                        <input type="text" class="form-control" id="filedescription" name="filedescription" placeholder="File description"></input>
                    </div>
                    <div class="form-group">
                        <label for="upload">Select file</label>
                        <input type="file" required class="form-control" id="upload" name="upload"></input>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="articleid" value="<?= $id ?>"/>
                        <button type="submit" required class="btn btn-primary" name="submit" value="upload"><i class="fas fa-cloud-upload-alt"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>