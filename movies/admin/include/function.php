<?php


function get_all($select , $from) {
    global $con;
    $stmt = $con -> prepare("select $select from $from");
    $stmt -> execute();
    return $stmt -> fetchAll();
}



function count_db($from) {
    global $con;
    $stmt = $con -> prepare("select * from $from");
    $stmt -> execute();
    return $stmt -> rowCount();
}

function count_where($select , $from , $value) {
    global $con;
    $stmt = $con -> prepare("select $select from $from where $select = ?");
    $stmt -> execute(array($value));
    return $stmt -> rowCount();
}


function login() {
    if (!isset($_SESSION["user_name"])){
        header("Location:login.php");
        exit;
    }
}

?>