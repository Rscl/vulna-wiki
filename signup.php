<?php
session_start();
if(isset($_SESSION["username"]))
{
    header("Location: logout.php");
    die;
}
$site = parse_ini_file("site.ini");
if(isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["invitationcode"]))
{
    $dbconfig = parse_ini_file("db.ini");
    $db = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
    if(!$db)
    {
        $error = "Unable to connect database!".mysqli_connect_error();
    }
    else
    {
        if($result = mysqli_query($db, "SELECT username, password, role, userid FROM users WHERE username='".$_POST["username"]."'"))
        {
            if(mysqli_num_rows($result)>0){
                //User exists
                $error = "User account already exists";
                mysqli_free_result($result);
            }
            else
            {
                mysqli_free_result($result);
                //Check for invitation code
                if($result = mysqli_query($db, "SELECT invitationid, code, valid_to, created_at from invitations WHERE code='".$_POST["invitationcode"]."' AND NOW()<valid_to AND NOW()>created_at"))
                {
                    //We found a code!
                    $error = "User account created!";
                    mysqli_free_result($result);
                    if($result = mysqli_query($db, "INSERT INTO users (username, password, role) VALUES('".$_POST["username"]."', '".$_POST["password"]."', 3)"))
                    {
                        mysqli_free_result($result);
                        $_SESSION["message"] = "User account created succesfully! Welcome!";
                        header("Location: /index.php");
                        die;
                    }
                }
                else
                {
                    $error = "DB ERROR: ".mysqli_error($db);
                    mysqli_free_result($result);
                }
            }
        }
    }
    mysqli_close($db);
}
?>



?>
<!DOCTYPE html>
<html>
<head>
<?php
include "head.php";
?>
<link rel="stylesheet" href="/css/signin.css">
</head>

<body class="text-center" data-gr-c-s-loaded="true">
    <?php if(isset($error)) :?>
        <div class="alert alert-danger" role="alert">
            <?= $error ?>
        </div>
    <?php endif;?>
    <form class="form-signin" method="post" action="signup.php">
      <img class="mb-4" src=<?= $site["logo"] ?>>
      <h1 class="h3 mb-3 font-weight-normal">Create a new account</h1>
      <input name="username" type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
      <input name="password" type="password" id="inputPassword" class="form-control my-2" placeholder="Password" required="">
      <input name="password2" type="password" id="inputPassword2" class="form-control" placeholder="Password (again)" required="">
      <label for="invitationcode">Invitation code</label>
        <input name="invitationcode" type="text" id="invitationcode" class="form-control" placeholder="AAAAA-AAAAA..." required>

      <button class="btn btn-lg btn-primary btn-block my-2" type="submit">Sign up</button>

      <p class="mt-5 mb-3 text-muted">© 2020 <a href="https://github.com/Rscl">Rscl</a> / <a href="https://github.com/Rscl/vulna-wiki">Vulna-wiki</a></p>
    </form>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html> 