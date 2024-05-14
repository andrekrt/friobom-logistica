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
    $condicao = " AND (folha_pagamento.filial=$filial)";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (mes_ano LIKE :mes_ano OR tipo_funcinarios LIKE :tipo_funcinarios) ";
    $searchArray = array( 
        'mes_ano'=>"%$searchValue%", 
        'tipo_funcinarios'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM folha_pagamento LEFT JOIN usuarios ON folha_pagamento.usuario = usuarios.idusuarios WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM folha_pagamento LEFT JOIN usuarios ON folha_pagamento.usuario = usuarios.idusuarios WHERE 1 $condicao".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM folha_pagamento LEFT JOIN usuarios ON folha_pagamento.usuario = usuarios.idusuarios WHERE 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "mes_ano"=>date("m/Y", strtotime($row['mes_ano'])),
        "pagamento"=>"R$ ".number_format($row['pagamento'],2,",",".") ,
        "tipo_funcionarios"=>$row['tipo_funcionarios'],
        "nome_usuario"=>$row['nome_usuario'],
        "acoes"=> '<a href="javascript:void();" data-id="'.$row['idpagamento'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a> <a data-id="'.$row['idpagamento'].'"  class="btn btn-danger btn-sm deleteBtn" onclick=\'confirmaDelete(' . $row['idpagamento'] . ')\' >Deletar</a> '
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
