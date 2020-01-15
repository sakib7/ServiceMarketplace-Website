<?php
header('Content-Type: application/json');
require_once '../../includes/connection.php';

$rawdata = file_get_contents("php://input");
$post = json_decode($rawdata);
$statement = null;

if(isset($post->name)){
    $query = "update `user` SET name=:name where mobile=:mobile";
    $statement = $dbcon->prepare($query);
    $statement->bindValue(':name',$post->name);
    $statement->bindValue(':mobile',$post->mobile);
}
elseif(isset($post->address)){
    $query = "update `user` SET address=:address where mobile=:mobile";
    $statement = $dbcon->prepare($query);
    $statement->bindValue(':address',$post->address);
    $statement->bindValue(':mobile',$post->mobile);
}
elseif(isset($post->email)){
    $query = "update `user` SET email=:email where mobile=:mobile";
    $statement = $dbcon->prepare($query);
    $statement->bindValue(':email',$post->email);
    $statement->bindValue(':mobile',$post->mobile);
}
$statement->execute();

$sql = "SELECT * FROM `user` where `mobile`='$post->mobile'";
$query=$dbcon->prepare($sql);
$query->execute();
echo json_encode($query->fetch(PDO::FETCH_OBJ));

