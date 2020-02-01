<?php
    session_start();
    $site = parse_ini_file("site.ini");
    if(!isset($_SESSION["username"]))
    {
        header("Location: login.php");
        die;
    }
    if(isset($_POST["logout"]))
    {
        session_destroy();
        header("Location: login.php");
        die;
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
    <form class="form-signin" method="post" action="logout.php">
      <img class="mb-4" src=<?= $site["logo"] ?>>
      <h1 class="h3 mb-3 font-weight-normal">You are logged in as <?php echo $_SESSION["username"]; ?></h1>
      <div class="checkbox mb-3">
        <label>
          <input name="logout" type="checkbox" value="logout"> Logout
        </label>
      </div>
      <button class="btn btn-lg btn-primary btn-block" type="submit">Confirm</button>
      <p class="mt-5 mb-3 text-muted">© 2020 <a href="https://github.com/Rscl">Rscl</a> / <a href="https://github.com/Rscl/vulna-wiki">Vulna-wiki</a></p>
    </form>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</body>

</html> 