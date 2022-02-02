<?php
include '../conexao.php';

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
	$searchQuery = " AND (placa_veiculo LIKE :placa_veiculo OR 
        km_alinhamento LIKE :km_alinhamento OR tipo_alinhamento LIKE :tipo_alinhamento) ";
    $searchArray = array( 
        'placa_veiculo'=>"%$searchValue%", 
        'km_alinhamento'=>"%$searchValue%",
        'tipo_alinhamento'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM alinhamentos_veiculo ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM alinhamentos_veiculo WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM alinhamentos_veiculo WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "data_alinhamento"=> date("d/m/Y", strtotime($row['data_alinhamento'])),
            "placa_veiculo"=>$row['placa_veiculo'],
            "km_alinhamento"=>$row['km_alinhamento'],
            "tipo_alinhamento"=>$row['tipo_alinhamento'],
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['idalinhamento'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a> '
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
