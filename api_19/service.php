<?php
header('Content-Type: application/json');

require_once '../includes/connection.php';

$data = array();

$data['ads'] = getAd();
$data['cats'] = getCat();
$data['trendings'] = getTrending();

function getAd(){
    global $dbcon;
    $query = "SELECT * FROM ad";
    $res = $dbcon->query($query);
    $root = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $root[] = $row;
    }
    return $root;
}

function getTrending(){
    global $dbcon;
    $query = "SELECT * FROM trending";
    $res = $dbcon->query($query);
    $root = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $row['trending_services'] = getTrendingService($row['id']);
        $root[] = $row;
    }
    return $root;
}

function getTrendingService($id){
    global $dbcon;
    $query = "SELECT * FROM trending_service WHERE trending_id='$id'";
    $res = $dbcon->query($query);
    $root = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $root[] = $row;
    }
    return $root;
}

function getCat(){
    global $dbcon;
    $query = "SELECT * FROM category WHERE status='active'";
    $res = $dbcon->query($query);
    $root = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $row['subcates'] = getSubcat($row['id']);
        $root[] = $row;
    }
    return $root;
}

function getSubcat($id){
    global $dbcon;
    $query = "SELECT * FROM subcategory WHERE status='active' AND category_id='$id'";
    $res = $dbcon->query($query);
    $root = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $row['services'] = getService($row['id']);
        $root[] = $row;
    }
    return $root;
}

function getService($id){
    global $dbcon;
    $query = "SELECT * FROM service WHERE status='active' AND subcategory_id='$id'";
    $res = $dbcon->query($query);
    $root = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $row['variant_a'] = getVarA($row['id']);
        $row['variant_b'] = getVarB($row['id']);
        $row['variant_price'] = getVarPrice($row['id']);
        $root[] = $row;
    }
    return $root;
}

function getVarA($id){
    global $dbcon;
    $query = "SELECT * FROM variant_a WHERE service_id='$id' ORDER BY id";
    $res = $dbcon->query($query);
    $root = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $root[] = $row;
    }
    return $root;
}

function getVarB($id){
    global $dbcon;
    $query = "SELECT * FROM variant_b WHERE service_id='$id' ORDER BY id";
    $res = $dbcon->query($query);
    $root = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $root[] = $row;
    }
    return $root;
}

function getVarPrice($id){
    global $dbcon;
    $query = "select variant_price.id as variant_price_id,
    service.id as service_id,
    service.name as service_name,
    service.image as service_image,
    variant_a.id as variant_a_id,
    variant_a.name as variant_a_name,
    variant_b.id as variant_b_id,
    variant_b.name as variant_b_name,
    variant_price.price as variant_price_price
    from service 
    inner join variant_a on service.id=variant_a.service_id
    inner join variant_b on service.id=variant_b.service_id
    left join variant_price on variant_a.id=variant_price.variant_a_id and variant_b.id=variant_price.variant_b_id
    WHERE service.id='$id' ORDER BY variant_a.id,variant_b.id";
    $res = $dbcon->query($query);
    $root = array();
    while($row = $res->fetch(PDO::FETCH_ASSOC)){
        $root[] = $row;
    }
    return $root;
}


echo json_encode($data);

