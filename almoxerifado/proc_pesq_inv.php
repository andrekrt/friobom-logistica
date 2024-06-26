<?php
session_start();
include '../conexao.php';
include('funcoes.php');

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

$filial=$_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = " AND (inventario_almoxarifado.filial=$filial)";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (descricao_peca LIKE :descricao_peca OR grupo_peca LIKE :grupo_peca OR idpeca LIKE :idpeca) ";
    $searchArray = array( 
        'descricao_peca'=>"%$searchValue%", 
        'grupo_peca'=>"%$searchValue%",
        'idpeca'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM inventario_almoxarifado WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM inventario_almoxarifado WHERE 1 $condicao".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM inventario_almoxarifado LEFT JOIN usuarios ON inventario_almoxarifado.usuario = usuarios.idusuarios LEFT JOIN peca_reparo ON inventario_almoxarifado.peca = peca_reparo.id_peca_reparo WHERE 1 $condicao".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "idinventario"=>$row['idinventario'],
            "data"=>date("d/m/Y", strtotime($row['data_inv'])) ,
            "descricao_peca"=>$row['id_peca_reparo']. " - " .$row['descricao'],
            "un_medida"=>$row['un_medida'],
            "grupo_peca"=>$row['categoria'],
            "qtd"=>number_format($row['qtd'],2,",","."),
            "nome_usuario"=>$row['nome_usuario'],
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['idinventario'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  '
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
