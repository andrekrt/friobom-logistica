<?php

use Mpdf\Tag\A;

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
	$searchQuery = " AND (veiculo LIKE :veiculo OR hr_tk LIKE :hr_tk OR carregamento_ret LIKE :carregamento_ret OR hr_tk_ret LIKE :hr_tk_ret OR obs LIKE :obs) ";
    $searchArray = array( 
        'veiculo'=>"%$searchValue%", 
        'hr_tk'=>"%$searchValue%",
        'carregamento_ret'=>"%$searchValue%",
        'hr_tk_ret'=>"%$searchValue%",
        'obs'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM checklist_apps LEFT JOIN checklist_apps_retorno02 ON checklist_apps.id = checklist_apps_retorno02.checksaida");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM checklist_apps LEFT JOIN checklist_apps_retorno02 ON checklist_apps.id = checklist_apps_retorno02.checksaida WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM checklist_apps LEFT JOIN checklist_apps_retorno02 ON checklist_apps.id = checklist_apps_retorno02.checksaida WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $retorno="";
    if(empty($row['data_ret'])){
        $retorno = '<a href="form-retorno.php?id='.$row['id'].'" class="btn btn-success btn-sm deleteBtn" >Retorno</a>';
    }
    
    $data[] = array(
        "id"=>$row['id'],
        "veiculo"=>$row['veiculo'],
        "dataSaida"=>date("d/m/Y", strtotime($row['data'])),
        "hr_tkSaida"=>$row['hr_tk'],
        "dataRetorno"=>$row['data_ret']==null?"":date("d/m/Y", strtotime($row['data_ret'])) ,
        "hr_tkRetorno"=>$row['hr_tk_ret'],
        "carregamento"=>$row['carregamento_ret'],
        "acoes"=> ' <a target="_blank" href="ficha.php?id='.$row['id'].'" class="btn btn-secondary btn-sm deleteBtn" >Ficha</a> ' . $retorno
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
