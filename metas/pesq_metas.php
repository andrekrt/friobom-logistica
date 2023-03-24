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
	$searchQuery = " AND (tipo_meta LIKE :tipo_meta ) ";
    $searchArray = array( 
        'tipo_meta'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT token) AS allcount FROM metas ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT token) AS allcount FROM metas WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM metas LEFT JOIN usuarios ON metas.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery." GROUP BY token ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "token"=>$row['token'],
        "data_meta"=>date("m/Y", strtotime($row['data_meta'])) ,
        "tipo_meta"=>$row['tipo_meta'],
        "nome_usuario"=>$row['nome_usuario'],
        "acoes"=> '<a href="edit-metas.php?token='.$row['token'].'" data-id="'.$row['token'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="excluir-meta.php?token='.$row['token'].' " data-id="'.$row['token'].'"  class="btn btn-danger btn-sm deleteBtn" onclick=\'return confirm("Deseja Excluir?");\'>Deletar</a>'
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