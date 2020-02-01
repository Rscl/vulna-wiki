<?php
    //error_reporting(0);
    session_start();
    if(isset($_SESSION["username"]))
    {
        header("Location: logout.php");
        die;
    }
    $site = parse_ini_file("site.ini");
    if(isset($_POST["username"]) && isset($_POST["password"]))
    {
        if(isset($_POST["debug"]))
        {
            echo "SELECT username, password, role, userid FROM users WHERE username='".$_POST["username"]."'";
            die;
        }
        $dbconfig = parse_ini_file("db.ini");
        $db = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
        if(!$db)
        {
            $error = "Unable to connect database!".mysqli_connect_error();
            header("Location: /index.php");
            die;
        }
        else
        {
            if($result = mysqli_query($db, "SELECT username, password, role, userid FROM users WHERE username='".$_POST["username"]."'"))
            {
                $row = mysqli_fetch_row($result);
                if($row[1]==$_POST["password"])
                {
                    //User authenticated
                    session_start();
                    $_SESSION["username"]=$row[0];
                    $_SESSION["role"]=$row[2];
                    $_SESSION["userid"]=$row[3];
                    header("Location: index.php");
                    mysqli_free_result($result);
                    mysqli_close($db);
                    die;
                }
                $error = "Unknown password.";
                mysqli_free_result($result);
            }
            else
            {
                $error = "Unknown username.";
            }
        }
        mysqli_close($db);
    }
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
    <?php if(isset($error)) : ?>
        <div class="alert alert-danger" role="alert">
            <?php print($error); ?>
        </div>
    <?php endif; ?>
    <form class="form-signin" method="post" action="login.php">
      <img class="mb-4" src=<?= $site["logo"] ?>>
      <h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
      <label for="inputEmail" class="sr-only">Email address</label>
      <input name="username" type="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
      <label for="inputPassword" class="sr-only">Password</label>
      <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password" required="">
      <div class="checkbox mb-3">
        <label>
          <input name="remember" type="checkbox" value="remember-me"> Remember me
        </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
      <p class="mt-5 mb-3 text-muted">© 2020 <a href="https://github.com/Rscl">Rscl</a> / <a href="https://github.com/Rscl/vulna-wiki">Vulna-wiki</a></p>
    </form>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html> 