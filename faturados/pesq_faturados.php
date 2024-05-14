<?php
session_start();
include '../conexao-oracle.php';

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
	$searchQuery = " AND (NUMCAR LIKE :NUMCAR OR DATAMON LIKE :DATAMON OR DTFAT LIKE :DTFAT OR MOTORISTA LIKE :MOTORISTA OR VEICULO LIKE :VEICULO  OR ROTA LIKE :ROTA) ";
    $searchArray = array( 
        'NUMCAR'=>"%$searchValue%", 
        'DATAMON'=>"%$searchValue%",
        'DTFAT'=>"%$searchValue%",
        'MOTORISTA'=>"%$searchValue%",
        'VEICULO'=>"%$searchValue%",
        'ROTA'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $dbora->prepare("SELECT COUNT(*) AS allcount FROM friobom.pccarreg WHERE obsfatur LIKE '%faturado com sucesso%' AND DTSAIDA >= TRUNC(SYSDATE - 14, 'DD') ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $dbora->prepare("SELECT COUNT(*) AS allcount FROM friobom.pccarreg WHERE obsfatur LIKE '%faturado com sucesso%' AND DTSAIDA >= TRUNC(SYSDATE - 14, 'DD')  DESC AND ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $dbora->prepare("SELECT * FROM friobom.pccarreg WHERE obsfatur LIKE '%faturado com sucesso%' AND DTSAIDA >= TRUNC(SYSDATE - 14, 'DD') AND ".$searchQuery. " AND ROWNUM>".':limit'." AND ROWNUM<=:offset ORDER BY ".$columnName." ".$columnSortOrder." ");

// Bind values
foreach($searchArray as $key=>$search){
    $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage+(int)$row, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();

foreach($empRecords as $row){
    
    $data[] = array(
        "NUMCAR"=>$row['NUMCAR'],
        "DATAMON"=>$row['DATAMON'],
        "DTFAT"=>$row['DTFAT'],
        "MOTORISTA"=>$row['MOTORISTA'],
        "VEICULO"=>$row['VEICULO'],       
        "ROTA"=>$row['ROTA'],
        "VLTOTAL"=>$row['VLTOTAL']
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
