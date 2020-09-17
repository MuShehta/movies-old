<?php

session_start();
$page_title = "Manage Movies";
include "include/ini.php";
$do = (isset($_GET["do"])) ? $do = $_GET["do"] : $do = "manage";
login();
echo '<a class="btn btn-success" href="index.php">Go to Index Page</a>';
if ($do == "manage") {
    $movies = get_all("*" , "movie");
    ?>
    <h1 class="text-center">Manage Movies</h1>
    <div class="container">
        <table class="table table-bordered">
            <thead class="thead-dark">
                <tr>
                <th scope="col">#ID</th>
                <th scope="col">Name</th>
                <th scope="col">Control</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    foreach ($movies as $row){
                        echo "<tr>";
                            echo '<th scope="row">'. $row["id"] .'</th>';
                            echo '<td>' . $row["name"] . '</td>';
                            echo '<td>';
                                echo '<a class="btn btn-danger" style="margin-right: 10px;" href="items.php?do=delete&id='.$row["id"].'">Delete</a>';
                                echo '<a class="btn btn-success" href="items.php?do=edit&id='.$row["id"].'">Edit</a>';
                            echo '</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
         <a class="btn btn-primary" href="items.php?do=add">+ Add Movie</a>
    </div>
<?php } elseif($do == "add") {
    ?>
    <form action="?do=insert" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name">
            </div>
        <div class="form-group">
            <label>Category</label>
            <select class="form-control" name="cat">
                <option value="movie">Movie</option>
                <option value="serice">Serice</option>
                <option value="show">Show</option>
                <option value="program">Program</option>
            </select>
        </div>
        <div class="form-group">
            <label>Description</label>
            <input type="text" class="form-control" name="desc">
        </div>
        <div class="form-group">
            <label>Main Image</label>
            <input type="file" class="form-control" name="main_img">
        </div>
        <div class="form-group">
            <label>Secondary Image</label>
            <input type="file" class="form-control" name="sec_img">
        </div>
        <button type="submit" class="btn btn-primary">Add</button>
    </form>
<?php
} elseif ($do == "insert") {
    echo "<h1 class='text-center'>Insert Show</h1>";
    echo "<div class='container'>";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $name = $_POST["name"];
        $cat = $_POST["cat"];
        $desc = $_POST["desc"];
        $main_img = $_FILES["main_img"];
        $sec_img = $_FILES["sec_img"];

        $errors = array();
        if (empty($name)) {$errors[] = "Name can't be empty"; }
        if (empty($cat)) {$errors[] = "Category can't be empty"; }
        if (empty($desc)) {$errors[] = "Description can't be empty"; }
        if (empty($main_img["name"])) {$errors[] = "Main Image can't be empty"; }
        if (empty($sec_img["name"])) {$errors[] = "Secondry Image can't be empty"; }

        foreach ($errors as $error) {
            echo "<div class= 'alert alert-danger'>" . $error . "</div>";
        }

        if (empty($errors)) {
            $main_name = "uploads/" . $name . rand(1 , 100000000000000000) . $main_img["name"];
            move_uploaded_file($main_img["tmp_name"] , $main_name);
            $sec_name = "uploads/" .  $name . rand(1 , 100000000000000000) . $sec_img["name"];
            move_uploaded_file($sec_img["tmp_name"] , $sec_name);

            $stmt = $con -> prepare("insert into movie (name, description, cat, main_img, sec_img) values (?,?,?,?,?)");
                $stmt -> execute(array($name , $desc , $cat , $main_name , $sec_name));
                echo "<div class= 'alert alert-success'>" . $stmt -> rowCount() . " Record Inserted </div>";
        }

    }

    echo "</div>";
} elseif($do == "delete") {
    echo "<h1 class='text-center'>Delete Show</h1>";
        echo "<div class='container'>";
        $id = (isset($_GET["id"]) && is_numeric($_GET["id"])) ? intval($_GET["id"]) : 0;
        
        //check if user is correct
        $stmt = $con->prepare("select * from movie where id = ?");
        $stmt -> execute(array($id));
        $row = $stmt -> fetch();
        $count = $stmt->rowCount();

        if($count > 0) {
            $stmt = $con->prepare("delete from movie where id = :id");
            $stmt->bindparam(":id" , $id);
            $stmt->execute();
            echo "<div class= 'alert alert-success'>Item Deleted</div>";
        }else {
            echo "<div class= 'alert alert-danger'>No such Item</div>";
        }
        echo "</div>";

} elseif ($do == "edit") {
    ?>
    <form action="?do=update&id=<?php echo $_GET["id"]; ?>" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name">
            </div>
        <div class="form-group">
            <label>Category</label>
            <select class="form-control" name="cat">
                <option value="movie">Movie</option>
                <option value="serice">Serice</option>
                <option value="program">Program</option>
            </select>
        </div>
        <div class="form-group">
            <label>Description</label>
            <input type="text" class="form-control" name="desc">
        </div>
        <div class="form-group">
            <label>Main Image</label>
            <input type="file" class="form-control" name="main_img">
        </div>
        <div class="form-group">
            <label>Secondary Image</label>
            <input type="file" class="form-control" name="sec_img">
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
<?php
} elseif ($do == "update") {
    echo "<h1 class='text-center'>Update Show</h1>";
    echo "<div class='container'>";
    $id = (isset($_GET["id"]) && is_numeric($_GET["id"])) ? intval($_GET["id"]) : 0;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $name = $_POST["name"];
        $cat = $_POST["cat"];
        $desc = $_POST["desc"];
        $main_img = $_FILES["main_img"];
        $sec_img = $_FILES["sec_img"];

        $errors = array();
        if (empty($name)) {$errors[] = "Name can't be empty"; }
        if (empty($cat)) {$errors[] = "Category can't be empty"; }
        if (empty($desc)) {$errors[] = "Description can't be empty"; }
        if (empty($main_img["name"])) {$errors[] = "Main Image can't be empty"; }
        if (empty($sec_img["name"])) {$errors[] = "Secondry Image can't be empty"; }

        foreach ($errors as $error) {
            echo "<div class= 'alert alert-danger'>" . $error . "</div>";
        }

        if (empty($errors)) {
            ini_set('upload_max_filesize', '10M');
            ini_set('post_max_size', '10M');
            ini_set('max_input_time', 300);
            ini_set('max_execution_time', 300);
            $main_name = "uploads/" . $name . rand(1 , 100000000000000000) . $main_img["name"];
            move_uploaded_file($main_img["tmp_name"] , $main_name);
            $sec_name = "uploads/" .  $name . rand(1 , 100000000000000000) . $sec_img["name"];
            move_uploaded_file($sec_img["tmp_name"] , $sec_name);

            
            $stmt = $con -> prepare("update movie set name = ? , cat = ? , description = ? , main_img = ? , sec_img = ? where id = ?");
            $stmt -> execute(array($name , $cat , $desc , $main_name , $sec_name , $id));
            echo "<div class= 'alert alert-success'>" . $stmt -> rowCount() . " Show Updated </div>";
        }

    }

    echo "</div>";
}else {
    echo "<div class= 'alert alert-danger'>No such Admin</div>";
}

include "include/footer.php";
?>