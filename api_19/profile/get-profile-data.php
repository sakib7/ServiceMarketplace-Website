<?php
header('Content-Type: application/json');
require_once '../../includes/connection.php';

$rawdata = file_get_contents("php://input");
$post = json_decode($rawdata);
$mobile = $post->mobile;

$sql = "SELECT * FROM `user` where `mobile`='$mobile'";
$query=$dbcon->prepare($sql);
$query->execute();
$row_count = $query->rowCount();

if($row_count == 0){
    echo json_encode(array('message' => "no such user"), JSON_FORCE_OBJECT);
} else{
    echo json_encode($query->fetch(PDO::FETCH_OBJ));
}
