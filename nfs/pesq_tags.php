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
    $condicao = "AND tags_xml.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (nome_coluna LIKE :nome_coluna OR descricao LIKE :descricao OR valor LIKE :valor OR legenda LIKE :legenda OR cor LIKE :cor ) ";
    $searchArray = array( 
        'nome_coluna'=>"%$searchValue%",
        'descricao'=>"%$searchValue%",
        'valor'=>"%$searchValue%",
        'legenda'=>"%$searchValue%",
        'cor'=>"%$searchValue%",
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM tags_xml WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM tags_xml WHERE 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM tags_xml WHERE 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "idtags"=>$row['idtags'],
        "nome_coluna"=>$row['nome_coluna'],
        "descricao"=>$row['descricao'],
        "valor"=>$row['valor'],
        "legenda"=> $row['legenda'],
        "cor"=>'<input type="color" value="'.$row['cor'].'" disabled> ' ,
        "acoes"=> '<a href="javascript:void();" data-id="'.$row['idtags'].'"  class="btn btn-info btn-sm editbtn" >Editar</a>  <a  data-id="'.$row['idtags'].'"  class="btn btn-danger btn-sm deleteBtn" onclick=\'confirmaDelete(' . $row['idtags'] . ')\'>Deletar</a> '
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
