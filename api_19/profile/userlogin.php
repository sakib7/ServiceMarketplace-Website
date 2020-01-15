<?php
header('Content-Type: application/json');
require_once '../../includes/connection.php';

$rawdata = file_get_contents("php://input");
$post = json_decode($rawdata);
$uid = $post->uid;
$mobile = $post->mobile;
$token = $post->token;



$sql = "SELECT * FROM `user` where `mobile`='$mobile'";
$query=$dbcon->prepare($sql);
$query->execute();
$row_count = $query->rowCount();

if($row_count == 0){
    $query = "INSERT INTO `user`(`uid`,`mobile`,`token`) VALUES (:uid,:mobile,:token)";
    $statement = $dbcon->prepare($query);
    $statement->bindValue(':uid',$uid);
    $statement->bindValue(':mobile',$mobile);
    $statement->bindValue(':token',$token);
    $statement->execute();

    echo json_encode(array('message' => "successfully regestered"), JSON_FORCE_OBJECT);;
} else{
    $query = "update `user` SET token=:token where mobile=:mobile";
    $statement = $dbcon->prepare($query);
    $statement->bindValue(':token',$token);
    $statement->bindValue(':mobile',$mobile);
    $statement->execute();

    $sql = "SELECT * FROM `user` where `mobile`='$mobile'";
    $query=$dbcon->prepare($sql);
    $query->execute();
    echo json_encode($query->fetch(PDO::FETCH_OBJ));
}
