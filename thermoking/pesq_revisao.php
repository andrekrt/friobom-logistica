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
    $condicao = "AND revisao_tk.filial=$filial";
}


$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (placa_veiculo LIKE :placa_veiculo OR tipo_veiculo LIKE :tipo_veiuclo OR tipo_tk LIKE :tipo_tk ) ";
    $searchArray = array( 
        'placa_veiculo'=>"%$searchValue%",
        'tipo_veiuclo'=>"%$searchValue%",
        'tipo_tk'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM revisao_tk WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT( *) AS allcount FROM revisao_tk  LEFT JOIN thermoking ON revisao_tk.thermoking = thermoking.idthermoking LEFT JOIN veiculos ON thermoking.veiculo = veiculos.cod_interno_veiculo WHERE 1 $condicao ".$searchQuery . "");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM revisao_tk LEFT JOIN thermoking ON revisao_tk.thermoking = thermoking.idthermoking LEFT JOIN veiculos ON thermoking.veiculo = veiculos.cod_interno_veiculo WHERE 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "placa_veiculo"=>$row['placa_veiculo'] ,
        "tipo_veiculo"=>$row['tipo_veiculo'],
        "tipo_tk"=>$row['tipo_tk'],
        "horimetro_revisao"=>$row['horimetro_revisao'],
        "data_revisao_tk"=>date("d/m/Y", strtotime($row['data_revisao_tk'])),
        "acoes"=> '<a href="javascript:void();" data-id="'.$row['idrevisao'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  '
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
