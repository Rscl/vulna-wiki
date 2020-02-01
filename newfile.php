
<?php
session_start();
$target_dir = "uploads/";
$originalFilename = $_FILES["upload"]["name"];
$filetype = strtolower(pathinfo($originalFilename,PATHINFO_EXTENSION));
$newname = RandomString();
$target_file = $target_dir . $newname .".". $filetype; //basename($_FILES["upload"]["name"]);

// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    move_uploaded_file($_FILES["upload"]["tmp_name"],$target_file);

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
        $description = $_POST["filedescription"];
        $id = $_POST["articleid"];
        $owner = $_SESSION["userid"];
        $query = "INSERT INTO attachments (articleid, description, filename, location, owner) VALUES({$id}, '{$description}', '{$originalFilename}', '{$target_file}' ,'{$owner}')";
        if($result = mysqli_query($db, $query))
        {
            $_SESSION["message"]="File uploaded succesfully!";
            header("Location: /index.php?modify&articleid={$id}");
            mysqli_free_result($result);
            mysqli_close($db);
            die;
        }
        else{
            $_SESSION["error"]="File uploaded failed!";
            header("Location: /index.php?modify&articleid={$id}");
            mysqli_close($db);
            die;
        }
    }
}

function RandomString()
    {
        $characters = 'abcdefghijklmnopqrstuvwxyz';
        $randstring = '';
        for ($i = 0; $i < 32; $i++) { 
            $index = rand(0, strlen($characters) - 1); 
            $randstring .= $characters[$index]; 
        } 
        return $randstring;
    }

?>
