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
	$searchQuery = " AND (descricao_peca LIKE :descricao_peca OR solicitante LIKE :solicitante OR placa OR :placa OR idpeca LIKE :idpeca) ";
    $searchArray = array( 
        'descricao_peca'=>"%$searchValue%", 
        'solicitante'=>"%$searchValue%",
        'placa'=>"%$searchValue%",
        'idpeca'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM saida_estoque ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM saida_estoque LEFT JOIN peca_reparo ON saida_estoque.peca_idpeca = peca_reparo.id_peca_reparo LEFT JOIN servicos_almoxarifado ON saida_estoque.servico = servicos_almoxarifado.idservicos LEFT JOIN usuarios ON saida_estoque.id_usuario = usuarios.idusuarios WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM saida_estoque LEFT JOIN peca_reparo ON saida_estoque.peca_idpeca = peca_reparo.id_peca_reparo LEFT JOIN servicos_almoxarifado ON saida_estoque.servico = servicos_almoxarifado.idservicos LEFT JOIN usuarios ON saida_estoque.id_usuario = usuarios.idusuarios WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "idsaida_estoque"=>$row['idsaida_estoque'],
            "data_saida"=>date("d/m/Y", strtotime($row['data_saida'])),
            "qtd"=>$row['qtd'],
            "descricao_peca"=>$row['id_peca_reparo']. " - ". $row['descricao'],
            "solicitante"=>$row['solicitante'],
            "placa"=> $row['placa'],
            "obs"=>$row['obs'] ,
            "servico"=>$row['descricao'],
            "requisicao"=>$row['requisicao_saida'] ,
            "os"=>$row['os'] ,
            "nome_usuario"=>$row['nome_usuario']
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
