<?php
include '../conexao.php';
session_start();
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
	$searchQuery = " AND (carga LIKE :carga OR nf LIKE :nf OR situacao LIKE :situacao OR nome_usuario OR :nome_usuario) ";
    $searchArray = array( 
        'carga'=>"%$searchValue%", 
        'nf'=>"%$searchValue%",
        'situacao'=>"%$searchValue%",
        'nome_usuario'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT(TOKEN)) AS allcount FROM denegadas");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT(TOKEN)) AS allcount FROM denegadas LEFT JOIN usuarios ON denegadas.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery );
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT token, carga, COUNT(nf) as qtd, situacao, usuario,nome_usuario FROM denegadas LEFT JOIN usuarios ON denegadas.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery."GROUP BY token ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    if($_SESSION['idUsuario']==$row['usuario'] && $row['situacao']=="Aguardando Confirmação"){
        $botao= '<a href="javascript:void();" data-id="'.$row['token'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a> <a  class="btn btn-secondary btn-sm" onclick=\'confirmaDelete(' . $row['token'] . ')\'>Confirmar Todas as NF\'s</a>';
    }
    $data[] = array(
        "id_denegadas"=>$row['token'],
        "carga"=>$row['carga'],
        "nf"=>$row['qtd'],
        "situacao"=>$row['situacao'],
        "nome_usuario"=>$row['nome_usuario'],
        "acoes"=>$botao
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
