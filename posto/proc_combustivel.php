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
	$searchQuery = " AND (data_entrada LIKE :data_entrada OR qualidade LIKE :qualidade OR nome_usuario LIKE :nome_usuario OR nome_fantasia LIKE :nome_fantasia ) ";
    $searchArray = array( 
        'data_entrada'=>"%$searchValue%", 
        'qualidade'=>"%$searchValue%",
        'nome_usuario'=>"%$searchValue%",
        'nome_fantasia'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM combustivel_entrada ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM combustivel_entrada LEFT JOIN usuarios ON combustivel_entrada.usuario = usuarios.idusuarios LEFT JOIN fornecedores ON combustivel_entrada.fornecedor = fornecedores.id WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM combustivel_entrada LEFT JOIN usuarios ON combustivel_entrada.usuario = usuarios.idusuarios LEFT JOIN fornecedores ON combustivel_entrada.fornecedor = fornecedores.id WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    if($_SESSION['tipoUsuario']==4 && $row['situacao']=="Em Análise"){
        $botao='<a href="javascript:void();" data-id="'.$row['idcombustivel_entrada'].'"  class="btn btn-info btn-sm editbtn" >Editar</a>  <a href="excluir-entrada.php?idEntrada='.$row['idcombustivel_entrada'].' " data-id="'.$row['idcombustivel_entrada'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>';
    }
    $data[] = array(
        "idcombustivel_entrada"=>$row['idcombustivel_entrada'],
        "data_entrada"=>date("d/m/Y", strtotime($row['data_entrada'])),
        "valor_litro"=>"R$ ". number_format($row['valor_litro'],4,",","."),
        "frete"=>"R$ ". number_format($row['frete'],2,",","."),
        "total_litros"=>number_format($row['total_litros'],2,",",".") ,
        "valor_total"=>"R$ " . number_format($row['valor_total'],2,",","."),
        "nome_fantasia"=>utf8_encode($row['nome_fantasia']),
        "qualidade"=>$row['qualidade'],
        "situacao"=>$row['situacao'],
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
