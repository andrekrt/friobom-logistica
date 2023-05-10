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
	$searchQuery = " AND (saida LIKE :saida  ) ";
    $searchArray = array( 
        'saida'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(distinct(date_format(saida, '%m/%Y'))) AS allcount FROM fusion GROUP BY date_format(saida, '%m/%Y')");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(distinct(date_format(saida, '%m/%Y'))) AS allcount FROM fusion  WHERE 1 ".$searchQuery . "GROUP BY date_format(saida, '%m/%Y')");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT saida, COUNT(*) as qtd, SUM(num_entregas) as totalEntregas, SUM(num_dev) as totalDevolucao, SUM(premio_possivel) as totalPremiacaoPossivel, SUM(premio_real) as totalPago, AVG(premio_alcancado) as percPremio FROM fusion WHERE 1  ".$searchQuery."GROUP BY date_format(saida, '%m/%Y') ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "saida"=>date("m/Y ", strtotime($row['saida'])) ,
            "qtd"=>$row['qtd'],
            "entregas"=>$row['totalEntregas'],
            "devolucao"=>$row['totalDevolucao'],
            "premiacao"=>"R$".number_format($row['totalPremiacaoPossivel'],2,",",".") ,
            "pago"=>"R$".number_format($row['totalPago'],2,",","."),
            "percPremio"=>($row['percPremio']*100)."%"
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
