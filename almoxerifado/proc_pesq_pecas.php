<?php
include '../conexao-on.php';
include('funcoes.php');

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
	$searchQuery = " AND (descricao_peca LIKE :descricao_peca OR grupo_peca LIKE :grupo_peca ) ";
    $searchArray = array( 
        'descricao_peca'=>"%$searchValue%", 
        'grupo_peca'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM peca_estoque ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM peca_estoque WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM peca_estoque LEFT JOIN usuarios ON peca_estoque.id_usuario = usuarios.idusuarios WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "idpeca"=>$row['idpeca'],
        "descricao_peca"=>($row['descricao_peca']),
        "un_medida"=>($row['un_medida']),
        "grupo_peca"=>($row['grupo_peca']),
        "estoque_minimo"=>str_replace(".",",",$row['estoque_minimo']),
        "total_entrada"=>str_replace(".",",",$row['total_entrada']),
        "total_saida"=> str_replace(".",",",$row['total_saida']),
        "total_estoque"=> str_replace(".",",",$row['total_estoque']) ,
        "qtd_inv"=> str_replace(".",",",$row['qtd_inv']) ,
        "valor_total"=>"R$ " . str_replace(".",",",$row['valor_total']) ,
        "situacao"=>($row['situacao']),
        "data_cadastro"=>date("d/m/Y", strtotime($row['data_cadastro'])) ,
        "nome_usuario"=>($row['nome_usuario']),
        "acoes"=> '<a href="javascript:void();" data-id="'.$row['idpeca'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="excluir.php?idPeca='.$row['idpeca'].' " data-id="'.$row['idpeca'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>'
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
