<?php

include("koneksi.php");

$id = $_GET['id'];
if(isset($_GET['id']) ){

    $nama = $_GET['id'];

    $sql = "DELETE FROM tugas WHERE id=$id";
    $query = mysqli_query($db, $sql);

    if( $query ){
        header('Location: halaman.php');
    } else {
        die("gagal menghapus");
    }

} else {
    die("error");
}

?>