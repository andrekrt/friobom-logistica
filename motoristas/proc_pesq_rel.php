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
	$searchQuery = " AND (nome_motorista LIKE :nome_motorista OR categoria LIKE :categoria) ";
    $searchArray = array( 
        'nome_motorista'=>"%$searchValue%",
        'categoria'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount, nome_motorista, veiculos.categoria as categoria, nome_rota, viagem.placa_veiculo,COUNT(nome_motorista) as contagem, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(peso_carga) as peso, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(mediaSemTk) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, SUM(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(dias_em_rota) as diasEmRota, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo GROUP BY nome_motorista, veiculos.categoria");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount, nome_motorista, veiculos.categoria as categoria, nome_rota, viagem.placa_veiculo,COUNT(nome_motorista) as contagem, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(peso_carga) as peso, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(mediaSemTk) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, SUM(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(dias_em_rota) as diasEmRota, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE 1 ".$searchQuery. " GROUP BY nome_motorista, veiculos.categoria");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT nome_motorista, veiculos.categoria as categoria, nome_rota, viagem.placa_veiculo,COUNT(nome_motorista) as contagem, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(peso_carga) as peso, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(mediaSemTk) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, SUM(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(dias_em_rota) as diasEmRota, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE 1 ".$searchQuery." GROUP BY nome_motorista, veiculos.categoria ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "nome_motorista"=>$row['nome_motorista'],
            "categoria"=>$row['categoria'],
            "contagem"=> $row['contagem'],
            "custoEntrega"=>number_format($row['custoEntrega'],2, ",", ".") ,
            "mediaValorTransportado"=>number_format($row['mediaValorTransportado'],2, ",", "."),
            "valorDevolvido"=>"R$ " . str_replace(".",",",$row['valorDevolvido']),
            "entregas"=> $row['entregas'],
            "kmRodado"=> $row['kmRodado'],
            "litros"=>number_format($row['litros'],2, ",", "."),
            "abastecimento"=> number_format($row['abastecimento'],2, ",", "."),
            "mediaKm"=> number_format($row['mediaKm'],2, ",", "."),
            "diasEmRota"=>number_format($row['diasEmRota'],2, ",", "."),
            "diariasMotoristas"=>number_format($row['diariasMotoristas'],2, ",", "."),
            "diariasAjudante"=>number_format($row['diariasAjudante'],2, ",", "."),
            "diasMotoristas"=>number_format($row['diasMotoristas'],2, ",", "."),
            "diasAjudante"=>number_format($row['diasAjudante'],2, ",", "."),
            "outrosServicos"=>number_format($row['outrosServicos'],2, ",", "."),
            "tomada"=>number_format($row['tomada'],2, ",", "."),
            "descarga"=>number_format($row['descarga'],2, ",", "."),
            "travessia"=>number_format($row['travessia'],2, ",", ".")
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
