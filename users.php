<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
if($_SESSION["role"]<5)
{
    $_SESSION["error"] = "You don't have permission to view admin module.";
    Header("Location: /index.php");
    die;
}
if(!isset($dbconfig))
    $dbconfig = parse_ini_file("db.ini");
$dbadmin = mysqli_connect($dbconfig["server"], $dbconfig["user"], $dbconfig["password"], $dbconfig["database"]);
if(isset($_GET["delete"]))
{
    //Delete user
}
if(isset($_GET["modify"]))
{
    //Modify user
}
$query = "SELECT userid, username, password, role FROM users";
if($resultusers = mysqli_query($dbadmin, $query))
{?>
    <div class="row">
        <div class="col-8">
        <h3>Users</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">Password</th>
                    <th scope="col">Role</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while($row = mysqli_fetch_array($resultusers))
                { ?>
                <tr id="row_<?= $row[0] ?>">
                    <th scope="row"><?= $row[0] ?></th>
                    <td><?= $row[1] ?></td>
                    <td><div class="password-text">*********</div><input class="password-edit" type="password" hidden value="<?=$row[2]?>"/></td>
                    <td>
                    <div class="role-text">
                        <?php if($row[3]==3) echo "Quest";?>
                        <?php if($row[3]==4) echo "Participant";?>
                        <?php if($row[3]==5) echo "Admin";?>
                    </div>
                    <select class="role-select" name="role" id="role_select" hidden>
                        <option <?php if($row[3]==3) echo "selected";?>>Quest</option>
                        <option <?php if($row[3]==4) echo "selected";?>>Participant</option>
                        <option <?php if($row[3]==5) echo "selected";?>>Admin</option>
                    </select></td>
                    <td><button class="btn btn-primary btn-edit" onclick="EnableEditOnRow('<?=$row[0]?>')">Edit</Button>
                    <button class="btn btn-primary btn-save" hidden onclick="SaveEdit('<?=$row[0]?>')">Save</Button>
                    <button class="btn btn-danger mx-2 btn-delete" onclick="DeleteRow(<?=$row[0]?>)">Delete</button></td>
                    </tr>
                <?php }?>
                
            </tbody> 
            
        </table>
        </div>
    </div>
<?php }
?>

<script>
function EnableEditOnRow(row){
    $("#row_"+row).find(".role-text").prop("hidden", true);
    $("#row_"+row).find(".role-select").prop("hidden", false)
    $("#row_"+row).find(".password-text").prop("hidden", true);
    $("#row_"+row).find(".password-edit").prop("hidden", false);
    $("#row_"+row).find(".btn-edit").prop("hidden", true);
    $("#row_"+row).find(".btn-save").prop("hidden", false);
    $("#row_"+row).find(".btn-delete").prop("hidden", true);
}

function SaveEdit(row){
    $("#row_"+row).find(".role-text").prop("hidden", false);
    $("#row_"+row).find(".role-select").prop("hidden", true)
    $("#row_"+row).find(".password-text").prop("hidden", false);
    $("#row_"+row).find(".password-edit").prop("hidden", true);
    $("#row_"+row).find(".btn-edit").prop("hidden", false);
    $("#row_"+row).find(".btn-save").prop("hidden", true);
    $("#row_"+row).find(".btn-delete").prop("hidden", false);
    //$.post("/users.php?modify&userid="+row, {password: $("#row_"+row).find(".password-edit").value, role: $("#row_"+row).find(".role-select").value});
    alert("Undefined action.");
    location.reload();
}

function DeleteRow(row){
    alert("Undefined action.");
}
</script>