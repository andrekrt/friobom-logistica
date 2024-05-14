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
    $condicao = "AND combustivel_extrato.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (data_operacao LIKE :data_operacao OR tipo_operacao LIKE :tipo_operacao OR carregamento LIKE :carregamento OR placa LIKE :placa ) ";
    $searchArray = array( 
        'data_operacao'=>"%$searchValue%", 
        'tipo_operacao'=>"%$searchValue%",
        'carregamento'=>"%$searchValue%",
        'placa'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM combustivel_extrato WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM combustivel_extrato LEFT JOIN usuarios ON combustivel_extrato.usuario = usuarios.idusuarios WHERE 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM combustivel_extrato LEFT JOIN usuarios ON combustivel_extrato.usuario = usuarios.idusuarios WHERE 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $data[] = array(
        "idextrato"=>$row['idextrato'],
        "data_operacao"=>date("d/m/Y", strtotime($row['data_operacao'])),
        "tipo_operacao"=>$row['tipo_operacao'],
        "volume"=>number_format($row['volume'],2,",","."),
        "carregamento"=>$row['carregamento'] ,
        "placa"=> $row['placa'],
        "nome_usuario"=>$row['nome_usuario'],

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
