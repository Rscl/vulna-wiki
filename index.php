<?php
    session_start();
    if (isset($_SESSION['username'])) {

    } else {
        header("Location: login.php");
    }

    $site = parse_ini_file("site.ini");
?>
<!DOCTYPE html>
<html>
<head>
<?php
    include("head.php");
?>
</head>

<body>
<nav class="navbar navbar-expand navbar-dark sticky-top bg-dark flex-md-nowrap p-0">
    <a class="navbar-brand col-sm-3 col-md-2 mr-0" href="/index.php"><?php echo $site["name"]?></a>
    <input class="form-control form-control-dark w-100" type="text" placeholder="Search" aria-label="Search">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
    <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav px-3">
        <?php if($_SESSION["role"] > 3):?>
            <li class="nav-item text-nowrap"><a class="nav-link" href="/index.php?create">New Article</a></li>
            <li class="nav-item text-nowrap"><a class="nav-link" href="/index.php?invite">Invite</a></li>
        <?php endif;?>
        <?php if($_SESSION["role"] == 5):?>
        <li class="nav-item text-nowrap"><a class="nav-link" href="/index.php?users">Users</a></li>
        <?php endif;?>
        <li class="nav-item text-nowrap"><a class="nav-link" href="/logout.php">Sign out</a></li>
    </ul>
    </div>
</nav>
<div class="container-fluid">
    <div class="row block">
        <nav class="col-md-2 d-none d-md-block bg-light sidebar">
          <div class="sidebar-sticky">
            <ul class="nav flex-column">
              <?php include("articlelist.php");?>
            </ul>
          </div>
        </nav>
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
            <div class="container">
            
                <?php
                if(isset($_SESSION["message"])){?>

                    <div class="col-8 alert alert-success alert-dismissible fade show" role="alert"><?=$_SESSION["message"] ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php
                    unset($_SESSION["message"]);
                }
                ?>

                <?php
                if(isset($_SESSION["error"])){?>
                    <div class="col-8 alert alert-danger alert-dismissible fade show" role="alert"><?=$_SESSION["error"] ?>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php
                    unset($_SESSION["error"]);
                }
                ?>
                <?php

                    if(isset($_GET["create"]))
                    {
                        include("newarticle.php");
                    }
                    else if(isset($_GET["invite"]))
                    {
                        include("invite.php");
                    }
                    else if(isset($_GET["modify"]))
                    {
                        include("modifyarticle.php");
                    }
                    else if(isset($_GET["delete"]))
                    {
                        include("deletearticle.php");
                    }
                    else if(isset($_GET["users"]))
                    {
                        include("users.php");
                    }
                    else
                    {
                        include("article.php");
                    }
                ?>
            </div>
        </main>
    </div>
</div>
<?php

?>

<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.12/js/bootstrap-select.js" crossorigin="anonymous"></script>
<script src="https://kit.fontawesome.com/354ba08793.js" crossorigin="anonymous"></script>
</body>

</html> 
