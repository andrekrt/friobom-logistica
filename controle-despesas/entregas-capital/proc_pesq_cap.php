<?php
include '../../conexao.php';

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
	$searchQuery = " AND (carga LIKE :carga OR placa_veiculo LIKE :placa_veiculo OR 
        nome_motorista LIKE :nome_motorista) ";
    $searchArray = array( 
        'carga'=>"%$searchValue%", 
        'placa_veiculo'=>"%$searchValue%", 
        'nome_motorista'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM entregas_capital");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM entregas_capital LEFT JOIN motoristas ON entregas_capital.motorista = motoristas.cod_interno_motorista LEFT JOIN veiculos ON entregas_capital.veiculo = veiculos.cod_interno_veiculo LEFT JOIN usuarios ON entregas_capital.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM entregas_capital LEFT JOIN motoristas ON entregas_capital.motorista = motoristas.cod_interno_motorista LEFT JOIN veiculos ON entregas_capital.veiculo = veiculos.cod_interno_veiculo LEFT JOIN usuarios ON entregas_capital.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "carga"=>$row['carga'],
            "sequencia"=>$row['sequencia'],
            "nome_motorista"=>$row['nome_motorista'],
            "placa_veiculo"=>$row['placa_veiculo'],
            "qtd_falta"=>$row['qtd_falta'],
            "hr_rota"=> date("H:i", strtotime($row['hr_rota'])),
            "km_rodado"=>$row['km_rodado'],
            "vl_abastec"=>"R$ ". str_replace(".",",",$row['vl_abastec']),
            "media_consumo"=>str_replace(".",",",$row['media_consumo']),
            "outros_gastos"=>"R$ ". str_replace(".",",",$row['vl_abastec']+$row['diaria_motorista']+$row['diaria_auxiliar']+$row['outros_gastos']) ,
            "nome_usuario"=>$row['nome_usuario'],
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['identregas_capital'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="excluir-entregas.php?idEntregas='.$row['identregas_capital'].' " data-id="'.$row['identregas_capital'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>'
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
