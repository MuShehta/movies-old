<?php

session_start();
$page_title = "Manage Admins";
include "include/ini.php";
$do = (isset($_GET["do"])) ? $do = $_GET["do"] : $do = "manage";
login();
echo '<a class="btn btn-success" href="index.php">Go to Index</a>';
if ($do == "manage") { 
    $admins = get_all("*" , "admin");
    ?>
    <h1 class="text-center">Manage Admins</h1>
    <div class="container">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                <th scope="col">#ID</th>
                <th scope="col">User Name</th>
                <th scope="col">Control</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($admins as $row){
                        echo "<tr>";
                            echo '<th scope="row">'. $row["id"] .'</th>';
                            echo '<td>' . $row["name"] . '</td>';
                            echo '<td>';
                                echo '<a class="btn btn-danger" style="margin-right: 10px;" href="admin.php?do=delete&id='.$row["id"].'">Delete</a>';
                                echo '<a class="btn btn-success" href="admin.php?do=edit&id='.$row["id"].'">Edit</a>';
                            echo '</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
         <a class="btn btn-primary" href="admin.php?do=add">+ Add Admin</a>
    </div>
<?php } elseif ($do == "add") {
    ?>
        <form action="?do=insert" method="POST">
            <div class="form-group">
                <label>User Name</label>
                <input type="text" class="form-control" name="name">
                </div>
            <div class="form-group">
                <label>Password</label>
                <input type="text" class="form-control" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>



    <?php
}elseif ($do == "insert") {
    echo "<h1 class='text-center'>Insert Admin</h1>";
    echo "<div class='container'>";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $name = $_POST["name"];
        $password = $_POST["password"];

        $check = count_where("name" , "admin" , $name);
        $errors = array();
        if (empty($name)) {$errors[] = "Name can't be empty"; }
        if (empty($password)) {$errors[] = "password can't be empty"; }
        if ($check > 0) {$errors[] = "Name has be exist"; }
            
        foreach ($errors as $error) {
            echo "<div class= 'alert alert-danger'>" . $error . "</div>";
        }

        if (empty($errors)) {
            $stmt = $con -> prepare("insert into admin (name, password) values (?,?)");
            $stmt -> execute(array($name , sha1($password)));
            echo "<div class= 'alert alert-success'>" . $stmt -> rowCount() . " Admin Inserted </div>";
        }
        
    } else {
        echo "<div class= 'alert alert-danger'>No such Request</div>";
    }

    echo "</div>";
} elseif ($do == "delete") {
    echo "<h1 class='text-center'>Delete Admin</h1>";
    echo "<div class='container'>";
    $id = isset($_GET["id"]) ? $id = $_GET["id"] : $id = 0;
    $check = count_where("id" , "admin" , $id);
    if ($check > 0) {
        $stmt = $con->prepare("delete from admin where id = :id");
        $stmt->bindparam(":id" , $id);
        $stmt->execute();
        echo "<div class= 'alert alert-success'>Record Delete</div>";
    } else {
        echo "<div class= 'alert alert-danger'>No such Admin</div>";
    }
    echo "</div>";

} elseif ($do == "edit") {
    $id = isset($_GET["id"]) ? $id = $_GET["id"] : $id = 0;
    ?>
        <form action="?do=update" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <div class="form-group">
                <label>User Name</label>
                <input type="text" class="form-control" name="name">
                </div>
            <div class="form-group">
                <label>Password</label>
                <input type="text" class="form-control" name="password">
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
        </form>

    <?php
} elseif($do == "update") {
    echo "<h1 class='text-center'>Edit Admin</h1>";
    echo "<div class='container'>";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $name = $_POST["name"];
        $password = $_POST["password"];
        $id = $_POST["id"];

        $stmt1 = $con->prepare("select name from admin Where name = ? and id != ?");
        $stmt1 -> execute(array($name , $id));
        $check = $stmt1 -> rowCount();
        $errors = array();
        if (empty($name)) {$errors[] = "Name can't be empty"; }
        if (empty($password)) {$errors[] = "password can't be empty"; }
        if ($check > 0) {$errors[] = "Name has be exist"; }
            
        foreach ($errors as $error) {
            echo "<div class= 'alert alert-danger'>" . $error . "</div>";
        }

        if (empty($errors)) {
            $stmt = $con -> prepare("update admin set name = ? , password = ? where id = ?");
            $stmt -> execute(array($name , sha1($password) , $id));
            echo "<div class= 'alert alert-success'>" . $stmt -> rowCount() . " Admin Updated </div>";
        }
        
    } else {
        echo "<div class= 'alert alert-danger'>No such Admin</div>";
    }

    echo "</div>";
}

?>






<?php
include "include/footer.php";
?>


