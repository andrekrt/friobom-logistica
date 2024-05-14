<?php
session_start();
include '../conexao.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$filial = $_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = "AND motoristas_ponto.filial=$filial";
}

// datas para filtro
$dataInicial = !empty($_POST['dataInicial'])?$_POST['dataInicial']:"2024-01-01";
$dataFinal = !empty($_POST['dataFinal'])?$_POST['dataFinal']:"2034-12-31";

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != '' || $dataInicial != '' || $dataFinal != ''){
    $searchQuery = " AND data_ponto BETWEEN :dataInicial AND :dataFinal AND (motorista LIKE :motorista OR mdfe LIKE :mdfe) ";
    $searchArray = array(
        'mdfe'=>"%$searchValue%",
        'motorista'=>"%$searchValue%",
        'dataInicial'=>$dataInicial,
        'dataFinal'=>$dataFinal,
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT(motorista)) AS allcount FROM motoristas_ponto WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT(motorista)) AS allcount FROM motoristas_ponto WHERE 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT motorista, SEC_TO_TIME(SUM(TIME_TO_SEC(hrs_trabalhada))) AS hrs_trabalhada, SEC_TO_TIME(SUM(TIME_TO_SEC(hrs_parada))) AS hrs_parada, SEC_TO_TIME(SUM(TIME_TO_SEC(hrs_trabalhada_liq))) AS hrs_trabalhada_liq FROM motoristas_ponto  WHERE 1 $condicao ".$searchQuery." GROUP BY motorista ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

// Vincular valores
foreach($searchArray as $chave=>$valor){
    $stmt->bindValue(':'.$chave, $valor,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();

foreach($empRecords as $row){
    $data[] = array(
        "motorista"=>$row['motorista'],
        "hrs_trabalhada"=>$row['hrs_trabalhada'],
        "hrs_parada"=>$row['hrs_parada'],
        "hrs_trabalhada_liq"=>$row['hrs_trabalhada_liq'],
    );
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data,
);

echo json_encode($response);
