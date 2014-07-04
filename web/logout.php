<?
session_start();
if(isset( $_SESSION['iduser'])){
    session_unset();
    session_destroy();
}
header("location:index.php"); ?>
