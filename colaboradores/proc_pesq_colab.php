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
    $condicao = " AND (colaboradores.filial=$filial)";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (nome_colaborador LIKE :nome_colaborador OR cpf_colaborador LIKE :cpf_colaborador OR cargo_colaborador LIKE :cargo_colaborador ) ";
    $searchArray = array( 
        'nome_colaborador'=>"%$searchValue%", 
        'cpf_colaborador'=>"%$searchValue%",
        'cargo_colaborador'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM colaboradores WHERE ativo = 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM colaboradores WHERE 1 AND ativo = 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM colaboradores WHERE 1 AND ativo = 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "nome_colaborador"=>$row['nome_colaborador'],
        "cpf_colaborador"=>$row['cpf_colaborador'],
        "salario_colaborador"=>"R$". str_replace(".",",", $row['salario_colaborador']),
        "extra"=>"R$". str_replace(".",",", $row['salario_extra']),
        "cargo_colaborador"=>$row['cargo_colaborador'],
        "acoes"=> '<a href="javascript:void();" data-id="'.$row['idcolaboradores'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a  data-id="'.$row['idcolaboradores'].'"  class="btn btn-danger btn-sm deleteBtn" onclick=\'confirmaDelete(' . $row['idcolaboradores'] . ')\'>Desativar</a>'
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
