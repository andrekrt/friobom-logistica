<?php
include '../conexao.php';

session_start();
$tipoUsuario=$_SESSION['tipoUsuario'];

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
	$searchQuery = " AND (nome_motorista LIKE :nome_motorista ) ";
    $searchArray = array( 
        'nome_motorista'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM ocorrencias LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista GROUP BY ocorrencias.cod_interno_motorista");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM ocorrencias LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista WHERE 1 ".$searchQuery . " GROUP BY ocorrencias.cod_interno_motorista");
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT ocorrencias.cod_interno_motorista, nome_motorista, COUNT(*) as ocorrencias, SUM(advertencia) as advertencia, SUM(vl_total_custos) as custoTotal FROM ocorrencias LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista WHERE 1 ".$searchQuery." GROUP BY ocorrencias.cod_interno_motorista ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    //qtd de má condução
    $qtdConduta = $db->prepare("SELECT * FROM ocorrencias WHERE cod_interno_motorista = :motorista AND tipo_ocorrencia = :ocorrencia");
    $qtdConduta->bindValue(':motorista', $row['cod_interno_motorista']);
    $qtdConduta->bindValue(':ocorrencia', 'Má Condução');
    $qtdConduta->execute();
    $qtdConduta = $qtdConduta->rowCount();

    //qtd de mau comportamento
    $qtdComportamneto = $db->prepare("SELECT * FROM ocorrencias WHERE cod_interno_motorista = :motorista AND tipo_ocorrencia = :ocorrencia");
    $qtdComportamneto->bindValue(':motorista', $row['cod_interno_motorista']);
    $qtdComportamneto->bindValue(':ocorrencia', 'Mau Comportamento');
    $qtdComportamneto->execute();
    $qtdComportamneto = $qtdComportamneto->rowCount();

    $data[] = array(
            "nome_motorista"=>$row['nome_motorista'],
            "ma_conducao"=>$qtdConduta,
            "mau_comportamento"=> $qtdComportamneto,
            "ocorrencias"=>$row['ocorrencias'],
            "advertencia"=>$row['advertencia'],
            "custoTotal"=>"R$ ". str_replace(".",",",$row['custoTotal'])
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
