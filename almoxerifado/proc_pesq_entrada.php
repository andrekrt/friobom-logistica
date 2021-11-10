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
	$searchQuery = " AND (descricao_peca LIKE :descricao_peca OR 
        apelido LIKE :apelido ) ";
    $searchArray = array( 
        'descricao_peca'=>"%$searchValue%", 
        'apelido'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM entrada_estoque ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM entrada_estoque LEFT JOIN peca_estoque ON entrada_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON entrada_estoque.id_usuario = usuarios.idusuarios LEFT JOIN fornecedores ON entrada_estoque.fornecedor = fornecedores.id WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM entrada_estoque LEFT JOIN peca_estoque ON entrada_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON entrada_estoque.id_usuario = usuarios.idusuarios LEFT JOIN fornecedores ON entrada_estoque.fornecedor = fornecedores.id WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "identrada_estoque"=>$row['identrada_estoque'],
            "data_nf"=>date("d/m/Y", strtotime($row['data_nf'])),
            "num_nf"=>$row['num_nf'],
            "num_pedido"=>$row['num_pedido'],
            "descricao_peca"=>$row['descricao_peca'],
            "preco_custo"=>"R$ " . str_replace(".",",",$row['preco_custo']),
            "qtd"=> str_replace(".",",",$row['qtd']),
            "desconto"=>"R$ " . str_replace(".",",",$row['desconto']) ,
            "obs"=>$row['obs'] ,
            "apelido"=>$row['apelido'],
            "vl_total_comprado"=>"R$ " . str_replace(".",",",$row['vl_total_comprado']) ,
            "nome_usuario"=>$row['nome_usuario'],
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['identrada_estoque'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="excluir-entrada.php?idEntrada='.$row['identrada_estoque'].' " data-id="'.$row['identrada_estoque'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>'
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