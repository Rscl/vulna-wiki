<?php
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
        if($result = mysqli_query($db, "SELECT articleid, title FROM articles;"))
        {
            while($row = mysqli_fetch_array($result))
            {
                ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php?articleid=<?= $row[0]?>"><?= $row[1] ?> <span class="border border-primary">*new*</span></a>
                    </li>
                <?php
            }
            mysqli_free_result($result);
        }
    }
    mysqli_close($db);
?>