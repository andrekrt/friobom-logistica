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
    $condicao = "AND veiculos.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (veiculos.placa_veiculo LIKE :placa_veiculo OR 
        veiculos.tipo_veiculo LIKE :tipo_veiculo ) ";
    $searchArray = array( 
        'placa_veiculo'=>"%$searchValue%", 
        'tipo_veiculo'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT veiculos.placa_veiculo) AS allcount FROM veiculos LEFT JOIN viagem ON viagem.cod_interno_veiculo=veiculos.cod_interno_veiculo LEFT JOIN solicitacoes_new ON solicitacoes_new.placa = veiculos.placa_veiculo WHERE 1 $condicao ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT( *) AS allcount, veiculos.placa_veiculo, veiculos.tipo_veiculo, COUNT(viagem.placa_veiculo), SUM(viagem.km_rodado), SUM(viagem.litros), SUM(viagem.valor_total_abast), COUNT(solicitacoes_new.placa), SUM(solicitacoes_new.vl_total), (SUM(solicitacoes_new.vl_total)+SUM(viagem.valor_total_abast))/ SUM(viagem.km_rodado) FROM veiculos LEFT JOIN viagem ON viagem.cod_interno_veiculo=veiculos.cod_interno_veiculo LEFT JOIN solicitacoes_new ON solicitacoes_new.placa = veiculos.placa_veiculo WHERE 1 $condicao ".$searchQuery . " GROUP BY veiculos.placa_veiculo");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT veiculos.filial,veiculos.placa_veiculo, veiculos.tipo_veiculo, COUNT(viagem.placa_veiculo) AS qtd, SUM(viagem.km_rodado) AS kmRodado, SUM(viagem.litros) AS totalLitros, SUM(viagem.valor_total_abast) AS vlAbast, COUNT(solicitacoes_new.placa) AS qtdSolic, SUM(solicitacoes_new.vl_total) AS vlSolic, (SUM(solicitacoes_new.vl_total)+SUM(viagem.valor_total_abast))/ SUM(viagem.km_rodado) AS media FROM veiculos LEFT JOIN viagem ON viagem.cod_interno_veiculo=veiculos.cod_interno_veiculo LEFT JOIN solicitacoes_new ON solicitacoes_new.placa = veiculos.placa_veiculo WHERE 1 $condicao ".$searchQuery." GROUP BY veiculos.placa_veiculo ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "filial"=>$row['filial'],
        "placa_veiculo"=>$row['placa_veiculo'],
        "tipo_veiculo"=>$row['tipo_veiculo'],
        "qtd"=>$row['qtd'],
        "kmRodado"=>$row['kmRodado'],
        "totalLitros"=>$row['totalLitros'],
        "vlAbast"=> $row['vlAbast'],
        "qtdSolic"=> $row['qtdSolic'],
        "vlSolic"=> $row['vlSolic'],
        "media"=> $row['media'],
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
