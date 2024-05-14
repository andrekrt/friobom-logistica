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
    $condicao = "AND t1.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (carregamento LIKE :carregamento OR motorista LIKE :motorista OR rota LIKE :rota OR veiculo LIKE :veiculo ) ";
    $searchArray = array( 
        'carregamento'=>"%$searchValue%", 
        'motorista'=>"%$searchValue%",
        'rota'=>"%$searchValue%",
        'veiculo'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT carregamento) AS allcount FROM trocas t1 WHERE 1 $condicao ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT carregamento) AS allcount FROM trocas t1 WHERE 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT carregamento, COUNT(DISTINCT cod_produto) as qtd, rota, veiculo, motorista, SUM(valor_unit*qtd_falta) as vlTotal,
  CASE 
	WHEN COUNT(DISTINCT situacao) = 1 THEN MIN(situacao)
        ELSE 'Faltando'
	END AS situacao 
 FROM trocas t1 WHERE 1 $condicao ".$searchQuery." GROUP BY carregamento ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $imprimir ="";
    $excluir="";
    $conferir="";

    if($row['situacao']=="Não Conferido"){
        $conferir='<a href="detalhes.php?carregamento='.$row['carregamento'].' " data-id="'.$row['carregamento'].'"  class="btn btn-info btn-sm deleteBtn" >Conferir</a>';
    }

    if($row['situacao']<>"Não Conferido"){
        $imprimir = '<a target="_blank" href="comprovante.php?carregamento='.$row['carregamento'].' " data-id="'.$row['carregamento'].'"  class="btn btn-secondary btn-sm deleteBtn" >Imprimir</a>';
    }
   

    $data[] = array(
        "carregamento"=>$row['carregamento'],
        "qtd"=>number_format($row['qtd'],2,",",".") ,
        "rota"=>$row['rota'],
        "veiculo"=>$row['veiculo'],
        "motorista"=>$row['motorista'],
        "situacao"=>$row['situacao'],
        "valor"=>"R$". number_format($row['vlTotal'],2,",",".") ,
        "acoes"=>$conferir . $imprimir . $excluir   
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
