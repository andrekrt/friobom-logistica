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
	$searchQuery = " AND (nome_rota LIKE :nome_rota  ) ";
    $searchArray = array( 
        'nome_rota'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM rotas");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM rotas WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM rotas WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "cod_rota"=>$row['cod_rota'],
            "nome_rota"=>$row['nome_rota'],
            "fechamento1"=>$row['fechamento1'],
            "hora_fechamento1"=>$row['hora_fechamento1'],
            "fechamento2"=>$row['fechamento2'],
            "hora_fechamento2"=>$row['hora_fechamento2'],
            "meta_dias"=>empty($row['meta_dias'])?null: number_format($row['meta_dias'],1,",",","),
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['cod_rota'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="excluir.php?codRotas='.$row['cod_rota'].' " data-id="'.$row['cod_rota'].'"  class="btn btn-danger btn-sm deleteBtn"  >Deletar</a>'
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
