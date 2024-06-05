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

$filial=$_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = " AND (auxiliares_rota.filial=$filial)";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (nome_auxiliar LIKE :nome_auxiliar OR cpf_auxiliar LIKE :cpf_auxiliar OR nome_rota LIKE :nome_rota) ";
    $searchArray = array( 
        'nome_auxiliar'=>"%$searchValue%", 
        'cpf_auxiliar'=>"%$searchValue%",
        'nome_rota'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM auxiliares_rota LEFT JOIN rotas ON auxiliares_rota.rota = rotas.cod_rota WHERE ativo = 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM auxiliares_rota LEFT JOIN rotas ON auxiliares_rota.rota = rotas.cod_rota WHERE 1 AND ativo = 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM auxiliares_rota LEFT JOIN rotas ON auxiliares_rota.rota = rotas.cod_rota WHERE 1 AND ativo = 1 $condicao".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "nome_auxiliar"=>$row['nome_auxiliar'],
        "cpf_auxiliar"=>$row['cpf_auxiliar'],
        "salario_auxiliar"=>"R$". str_replace(".",",", $row['salario_auxiliar']),
        "rota"=>$row['nome_rota'],
        "acoes"=> '<a href="javascript:void();" data-id="'.$row['idauxiliares'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a> <a data-id="'.$row['idauxiliares'].'"  class="btn btn-danger btn-sm deleteBtn" onclick=\'confirmaDelete(' . $row['idauxiliares'] . ')\'>Desativar</a> '
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
