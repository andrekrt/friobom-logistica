<?php
session_start();
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

$filial=$_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = " AND (peca_reparo.filial=$filial)";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (descricao LIKE :descricao OR categoria LIKE :categoria OR situacao LIKE :situacao ) ";
    $searchArray = array( 
        'descricao'=>"%$searchValue%", 
        'categoria'=>"%$searchValue%",
        'situacao'=>"%%$searchValue"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM peca_reparo WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM peca_reparo WHERE 1 $condicao".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM peca_reparo LEFT JOIN usuarios ON peca_reparo.usuario = usuarios.idusuarios WHERE 1 $condicao".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    atualizaEStoque($row['id_peca_reparo']);
    
    $data[] = array(
        "id_peca_reparo"=>$row['id_peca_reparo'],
        "descricao"=>($row['descricao']),
        "un_medida"=>($row['un_medida']),
        "categoria"=>($row['categoria']),
        "estoque_minimo"=>str_replace(".",",",$row['estoque_minimo']),
        "total_entrada"=>str_replace(".",",",$row['total_entrada']),
        "total_saida"=> str_replace(".",",",$row['total_saida']),
        "total_estoque"=> str_replace(".",",",$row['total_estoque']) ,
        "qtd_inv"=>$row['qtd_inv']?str_replace(".",",",$row['qtd_inv']):"" ,
        "valor_total"=>"R$ " . str_replace(".",",",$row['valor_total']) ,
        "situacao"=>($row['situacao']),
        "nome_usuario"=>($row['nome_usuario']),
       
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
