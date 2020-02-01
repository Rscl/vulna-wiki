<h3>Attachments</h3>
<?php
$dbconfig = parse_ini_file("db.ini");
$dbfiles = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
if(!$dbfiles)
{
    $error = "Unable to connect database!".mysqli_connect_error();
    header("Location: /index.php");
    die;
}
else
{
    if($result = mysqli_query($dbfiles, "SELECT attachments.attachmentid, attachments.articleid, attachments.description, attachments.filename, attachments.location, attachments.created_at, users.username FROM attachments INNER JOIN users ON attachments.owner = users.userid WHERE attachments.articleid=".$id))
    {
        while($row = mysqli_fetch_array($result))
        {?>
            
            <div class="card">
                <div class="card-header"><?= $row[3] ?></div>
                <div class="card-body">
                    <p class="card-text"><?= $row[2]?></p>
                    <a href="<?= $row[4]?>" class="btn btn-primary"><i class="fas fa-paperclip"></i></a>
                    <a href="/download.php?filename=<?= $row[3]?>&location=<?= $row[4] ?>" class="btn btn-secondary mx-2"><i class="fas fa-file-download"></i></a>
                </div>
            </div>
            <?php
        }
        mysqli_free_result($result);
        mysqli_close($dbfiles);
    }
    else
    {
        ?><p>No attachments</p><?php
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