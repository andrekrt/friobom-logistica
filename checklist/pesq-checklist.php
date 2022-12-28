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
	$searchQuery = " AND (veiculo LIKE :veiculo OR hr_tk LIKE :hr_tk OR carregamento OR :carregamento OR tipo_checklist LIKE :tipo_checklist OR obs LIKE :obs) ";
    $searchArray = array( 
        'veiculo'=>"%$searchValue%", 
        'hr_tk'=>"%$searchValue%",
        'carregamento'=>"%$searchValue%",
        'tipo_checklist'=>"%$searchValue%",
        'obs'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM checklist_apps");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM checklist_apps WHERE 1 ".$searchQuery);
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
    if($row['id_ret']){
        $retorno = '<a href="https://friobom036.sharepoint.com/sites/Anexos/imagensRetorno/'.$row['id_ret'].'" target="_blank" >Fotos</a>';
    }else{
        $retorno = 'Ainda não retornou';
    }
    $data[] = array(
            "id"=>$row['id'],
            "data_check"=>date("d/m/Y", strtotime($row['data'])),
            "veiculo"=>$row['veiculo'],
            "carregamento"=>$row['carregamento_ret'],
            "hr_tk"=>$row['hr_tk'],
            "fotos_saidas"=>'<a href="https://friobom036.sharepoint.com/sites/Anexos/imagens/'.$row['id'].'" target="_blank" >Fotos</a>',
            "fotos_retorno"=>$retorno,
            "tipo_check"=>$row['tipo_checklist'],
            "acoes"=> ' <a href="ficha.php?id='.$row['id'].'" class="btn btn-secondary btn-sm deleteBtn" >Ficha</a>'
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
