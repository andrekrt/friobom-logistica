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
	$searchQuery = " AND (mdfe LIKE :mdfe OR motorista LIKE :motorista OR nome_usuario LIKE :nome_usuario OR data_ponto LIKE :data_ponto ) ";
    $searchArray = array( 
        'mdfe'=>"%$searchValue%",
        'motorista'=>"%$searchValue%",
        'nome_usuario'=>"%$searchValue%",
        'data_ponto'=>"%$searchValue%",
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM motoristas_ponto");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM motoristas_ponto LEFT JOIN usuarios ON motoristas_ponto.usuario=usuarios.idusuarios WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM motoristas_ponto LEFT JOIN usuarios ON motoristas_ponto.usuario=usuarios.idusuarios  WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "idponto"=>$row['idponto'],
        "mdfe"=>$row['mdfe'],
        "motorista"=>$row['motorista'],
        "data_ponto"=>date("d/m/Y", strtotime( $row['data_ponto'])),
        "hora_inicio"=>$row['hora_inicio'],
        "hora_final"=>$row['hora_final'],
        "tempo_parado"=>$row['tempo_parado'],
        "hrs_trabalhada"=>$row['hrs_trabalhada'],
        "hrs_parada"=>$row['hrs_parada'],
        "hrs_trabalhada_liq"=>$row['hrs_trabalhada_liq'],
        "usuario"=>$row['nome_usuario']
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
