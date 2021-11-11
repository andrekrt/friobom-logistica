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
	$searchQuery = " AND (veiculos.placa_veiculo LIKE :placa_veiculo ) ";
    $searchArray = array( 
        'placa_veiculo'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT placa_veiculo) AS allcount FROM viagem ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT( *) AS allcount, viagem.placa_veiculo,COUNT(nome_motorista) as contagem, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(peso_carga) as peso, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(mediaSemTk) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, SUM(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(dias_em_rota) as diasEmRota, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia FROM viagem WHERE 1 ".$searchQuery . " GROUP BY placa_veiculo");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT viagem.placa_veiculo,COUNT(nome_motorista) as contagem, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(peso_carga) as peso, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(mediaSemTk) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, SUM(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(dias_em_rota) as diasEmRota, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia FROM viagem WHERE 1 ".$searchQuery." GROUP BY placa_veiculo ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "placa_veiculo"=>$row['placa_veiculo'],
            "custoEntrega"=>"R$ ". number_format($row['custoEntrega'], 2, ",",".") ,
            "mediaValorTransportado"=>"R$ ". number_format($row['mediaValorTransportado'], 2, ",","."),
            "valorDevolvido"=>"R$ ". number_format($row['valorDevolvido'], 2, ",","."),
            "entregas"=>$row['entregas'],
            "kmRodado"=> $row['kmRodado'],
            "litros"=>number_format($row['litros'], 2, ",","."),
            "abastecimento"=> "R$ ". number_format($row['abastecimento'], 2, ",","."),
            "media"=>number_format($row['kmRodado']/$row['litros'], 2, ",","."),
            "diasEmRota"=> number_format($row['diasEmRota'], 2, ",","."),
            "diariasMotoristas"=> "R$ ". number_format($row['diariasMotoristas'], 2, ",","."),
            "diariasAjudante"=> "R$ ". number_format($row['diariasAjudante'], 2, ",","."),
            "diasMotoristas"=> number_format($row['diasMotoristas'], 2, ",","."),
            "diasAjudante"=> number_format($row['diasAjudante'], 2, ",","."),
            "outrosServicos"=>"R$ " . number_format($row['diasMotoristas'], 2, ",","."),
            "tomada"=> "R$ " . number_format($row['tomada'], 2, ",","."),
            "descarga"=> "R$ " . number_format($row['descarga'], 2, ",","."),
            "travessia"=>"R$ " . number_format($row['travessia'], 2, ",","."),
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
