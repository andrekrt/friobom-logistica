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

$filial = $_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = "AND mdfes.filial=$filial";
}


$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (num_mdfe LIKE :num_mdfe OR veiculo LIKE :veiculo OR motorista LIKE :motorista OR situacao LIKE :situacao ) ";
    $searchArray = array( 
        'num_mdfe'=>"%$searchValue%",
        'veiculo'=>"%$searchValue%",
        'motorista'=>"%$searchValue%",
        'situacao'=>"%$searchValue%",
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM mdfes WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM mdfes WHERE 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM mdfes WHERE 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "num_mdfe"=>$row['num_mdfe'],
        "cargas"=>$row['cargas'],
        "veiculo"=>$row['veiculo'],
        "motorista"=>$row['motorista'],
        "situacao"=>$row['situacao'],
        "data_saida"=>date('d/m/Y', strtotime($row['data_saida'])),
        "data_retorno"=>empty($row['data_retorno'])?"":date('d/m/Y', strtotime($row['data_retorno'])),
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
