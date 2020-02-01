<?php
    if(!isset($_SESSION)) 
    { 
        session_start(); 
    } 
    if(isset($_POST["articletext"]))
    {
        //Creating new article
        if(!isset($dbconfig))
            $dbconfig = parse_ini_file("db.ini");
        if(!isset($db))
            $db = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
        if(!$db)
        {
            $error = "Unable to create a new article<br>".mysqli_connect_error();
            header("Location: /index.php");
            mysqli_close($db);
            die;
        }
        else
        {
            $title = $_POST["articletitle"];
            $content = $_POST["articletext"];
            if($_POST["menu"]=="true")
                $menu = 1;
            else
                $menu = 0;
            $userid = $_SESSION["userid"];
            if(mysqli_query($db, "INSERT INTO articles (title, content, category, owner) VALUES('{$title}', '{$content}', 1, $userid)"))
            {
                $_SESSION["message"] = "New article created succesfully!";
                $newid = mysqli_insert_id($db);
                header("Location: /index.php?modify&articleid={$newid}");
                mysqli_close($db);
            }
            else
            {
                $_SESSION["error"] = "Unable to create new article!<br>".mysqli_error($db);
                header("Location: /index.php");
                mysqli_close($db);
                die;
            }
        }
    }
?>

<form method="post" action="newarticle.php" class="col-8">
  <div class="form-group">
    <label for="articletitle">Title</label>
    <input type="text" class="form-control" id="articletitle" name="articletitle" placeholder="Article title"></input>
    <label for="articletext">Content</label>
    <textarea class="form-control" id="articletext" name="articletext" rows="10" placeholder="Article content..."></textarea>
  </div>
  <button type="submit" class="btn btn-primary my-2">Submit</button>
</form>