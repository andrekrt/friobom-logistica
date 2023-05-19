<?php
include '../../conexao.php';

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
	$searchQuery = " AND (data_chegada LIKE :data_chegada  ) ";
    $searchArray = array( 
        'data_chegada'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(distinct(date_format(data_chegada, '%m/%Y'))) AS allcount FROM fusion_praca WHERE situacao = 'Finalizado' GROUP BY date_format(data_chegada, '%m/%Y')");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(distinct(date_format(data_chegada, '%m/%Y'))) AS allcount FROM fusion_praca  WHERE 1 AND situacao = 'Finalizado' ".$searchQuery . "GROUP BY date_format(data_chegada, '%m/%Y')");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT data_chegada, COUNT(*) as qtd, SUM(num_entregas) as totalEntregas, SUM(num_devolucao) as totalDevolucao, SUM(premio_possivel) as totalPremiacaoPossivel, SUM(premio_real) as totalPago, AVG(perc_premio) as percPremio FROM fusion_praca WHERE 1 AND situacao = 'Finalizado' ".$searchQuery."GROUP BY date_format(data_chegada, '%m/%Y') ORDER BY idfusion_praca ASC LIMIT :limit,:offset");

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
            "saida"=>date("m/Y ", strtotime($row['data_chegada'])) ,
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
