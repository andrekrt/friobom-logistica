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
	$searchQuery = " AND (veiculo_anterior LIKE :veiculo_anterior OR num_fogo LIKE :num_fogo OR novo_veiculo LIKE :novo_veiculo) ";
    $searchArray = array( 
        'veiculo_anterior'=>"%$searchValue%",
        'num_fogo'=>"%$searchValue%",
        'novo_veiculo'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM rodizio_pneu LEFT JOIN pneus ON rodizio_pneu.pneu = pneus.idpneus LEFT JOIN usuarios ON rodizio_pneu.usuario = usuarios.idusuarios");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM rodizio_pneu LEFT JOIN pneus ON rodizio_pneu.pneu = pneus.idpneus LEFT JOIN usuarios ON rodizio_pneu.usuario = usuarios.idusuarios  WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM rodizio_pneu LEFT JOIN pneus ON rodizio_pneu.pneu = pneus.idpneus LEFT JOIN usuarios ON rodizio_pneu.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "num_fogo"=>$row['num_fogo'],
            "data_rodizio"=>date("d/m/Y",strtotime( $row['data_rodizio'])) ,
            "veiculo_anterior"=>$row['veiculo_anterior'],
            "km_inicial_veiculo_anterior"=>$row['km_inicial_veiculo_anterior'],
            "km_final_veiculo_anterior"=>$row['km_final_veiculo_anterior'],
            "km_rodado_veiculo_anterior"=>$row['km_rodado_veiculo_anterior'],
            "novo_veiculo"=>$row['novo_veiculo'],
            "km_inicial_novo_veiculo"=>$row['km_inicial_novo_veiculo'],
            "nome_usuario"=>$row['nome_usuario']
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
