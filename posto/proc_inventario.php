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

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (data_inventario LIKE :data_inventario OR qtd_encontrada LIKE :qtd_encontrada OR nome_usuario LIKE :nome_usuario ) ";
    $searchArray = array( 
        'data_inventario'=>"%$searchValue%", 
        'qtd_encontrada'=>"%$searchValue%",
        'nome_usuario'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM combustivel_inventario");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM combustivel_inventario LEFT JOIN usuarios ON combustivel_inventario.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM combustivel_inventario LEFT JOIN usuarios ON combustivel_inventario.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $botao = "";
    if($_SESSION['tipoUsuario']==4){
        $botao = '<a href="javascript:void();" data-id="'.$row['idinventario'].'"  class="btn btn-info btn-sm editbtn" >Editar</a>  <a href="excluir-inventario.php?idInventario='.$row['idinventario'].' " data-id="'.$row['idinventario'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>';
    }
    $data[] = array(
        "idinventario"=>$row['idinventario'],
        "data_inventario"=>date("d/m/Y", strtotime($row['data_inventario'])),
        "volume_anterior"=>number_format($row['volume_anterior'],2,",",".")."l",
        "qtd_encontrada"=> number_format($row['qtd_encontrada'],2,",",".")."l",
        "volume_divergente"=>number_format($row['volume_divergente'],2,",",".") ,
        "nome_usuario"=>$row['nome_usuario'],
        "acoes"=> $botao
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
