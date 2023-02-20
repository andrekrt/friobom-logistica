<?php

session_start();
include '../conexao-on.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (data_hora LIKE :data_hora OR codigo_sup LIKE :codigo_sup OR cod_cliente LIKE :cod_cliente OR rca LIKE :rca) ";
    $searchArray = array( 
        'data_hora'=>"%$searchValue%", 
        'codigo_sup'=>"%$searchValue%",
        'cod_cliente'=>"%$searchValue%",
        'rca'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM localizacao ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM localizacao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM localizacao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

// Bind values
foreach($searchArray as $key=>$search){
    $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();

foreach($empRecords as $row){
    $latitude=str_replace(",",".",$row['latitude']);
    $logintude = str_replace(",",".", $row['longitude']);
    $data[] = array(
        "id"=>$row['id'],
        "data_hora"=>date("d/m/Y H:i", strtotime($row['data_hora'])) ,
        "codigo_sup"=>$row['codigo_sup'],
        "cod_cliente"=>$row['cod_cliente'],
        "rca"=>$row['rca'],
        "localizacao"=> '<a href="https://maps.google.com/?q='.$latitude.','.$logintude.'" target="_blank">Localização</a>'
    );
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
