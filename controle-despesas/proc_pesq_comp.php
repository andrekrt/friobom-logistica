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

$filial=$_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = " AND (complementos_combustivel.filial=$filial)";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (placa_veiculo LIKE :placa_veiculo OR 
        nome_motorista LIKE :nome_motorista) ";
    $searchArray = array( 
        'placa_veiculo'=>"%$searchValue%", 
        'nome_motorista'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM complementos_combustivel WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM complementos_combustivel LEFT JOIN veiculos ON complementos_combustivel.veiculo = veiculos.cod_interno_veiculo LEFT JOIN motoristas ON complementos_combustivel.motorista = motoristas.cod_interno_motorista LEFT JOIN usuarios ON complementos_combustivel.id_usuario = usuarios.idusuarios WHERE 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM complementos_combustivel LEFT JOIN veiculos ON complementos_combustivel.veiculo = veiculos.cod_interno_veiculo LEFT JOIN motoristas ON complementos_combustivel.motorista = motoristas.cod_interno_motorista LEFT JOIN usuarios ON complementos_combustivel.id_usuario = usuarios.idusuarios WHERE 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "id_complemento"=>$row['id_complemento'],
            "placa_veiculo"=>$row['placa_veiculo'],
            "nome_motorista"=>$row['nome_motorista'],
            "km_saida"=>$row['km_saida'],
            "km_chegada"=>$row['km_chegada'],
            "litros_abast"=>str_replace(".",",",$row['litros_abast']),
            "valor_abast"=>"R$ " . str_replace(".",",",$row['valor_abast']) ,
            "nome_usuario"=>$row['nome_usuario'],
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['id_complemento'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="excluir-complemento.php?idComplemento='.$row['id_complemento'].' " data-id="'.$row['id_complemento'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>'
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
