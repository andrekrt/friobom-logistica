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
    $condicao = "AND metas_tipo.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (idtipometa LIKE :idtipometa OR descricao_tipo LIKE :descricao_tipo OR nome_usuario OR :nome_usuario ) ";
    $searchArray = array( 
        'idtipometa'=>"%$searchValue%", 
        'descricao_tipo'=>"%$searchValue%",
        'nome_usuario'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM metas_tipo WHERE 1 $condicao ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM metas_tipo WHERE 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM metas_tipo LEFT JOIN usuarios ON metas_tipo.usuario = usuarios.idusuarios WHERE 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "idtipometa"=>$row['idtipometa'],
            "descricao_tipo"=>$row['descricao_tipo'],
            "nome_usuario"=>$row['nome_usuario'],
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['idtipometa'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="excluir-forn.php?id='.$row['idtipometa'].' " data-id="'.$row['idtipometa'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>'
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
