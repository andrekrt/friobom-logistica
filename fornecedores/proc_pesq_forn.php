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
	$searchQuery = " AND (razao_social LIKE :razao_social OR cnpj LIKE :cnpj OR nome_fantasia OR :nome_fantasia OR apelido LIKE :apelido OR endereco LIKE :endereco OR bairro LIKE :bairro OR  cidade LIKE :cidade OR uf LIKE :uf OR cep LIKE :cep OR telefone LIKE :telefone ) ";
    $searchArray = array( 
        'razao_social'=>"%$searchValue%", 
        'cnpj'=>"%$searchValue%",
        'nome_fantasia'=>"%$searchValue%",
        'apelido'=>"%$searchValue%",
        'endereco'=>"%$searchValue%",
        'bairro'=>"%$searchValue%",
        'cidade'=>"%$searchValue%",
        'uf'=>"%$searchValue%",
        'cep'=>"%$searchValue%",
        'telefone'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM fornecedores ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM fornecedores WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM fornecedores WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "razao_social"=>$row['razao_social'],
            "endereco"=>$row['endereco'],
            "bairro"=>$row['bairro'],
            "cidade"=>$row['cidade'],
            "cep"=> $row['cep'],
            "uf"=>$row['uf'] ,
            "cnpj"=>$row['cnpj'],
            "nome_fantasia"=>$row['nome_fantasia'],
            "apelido"=>$row['apelido'],
            "telefone"=>$row['telefone'],
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['id'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="excluir-forn.php?id='.$row['id'].' " data-id="'.$row['id'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>'
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
