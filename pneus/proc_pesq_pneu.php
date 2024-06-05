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
    $condicao = "AND pneus.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (num_fogo LIKE :num_fogo OR marca LIKE :marca OR modelo LIKE :modelo OR num_serie LIKE :num_serie OR veiculo LIKE :veiculo  ) ";
    $searchArray = array( 
        'num_fogo'=>"%$searchValue%", 
        'marca'=>"%$searchValue%",
        'modelo'=>"%$searchValue%",
        'num_serie'=>"%$searchValue%",
        'veiculo'=>"%$searchValue%",
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM pneus LEFT JOIN usuarios ON pneus.usuario = usuarios.idusuarios WHERE uso = 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM pneus LEFT JOIN usuarios ON pneus.usuario = usuarios.idusuarios WHERE uso = 1 AND 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM pneus LEFT JOIN usuarios ON pneus.usuario = usuarios.idusuarios WHERE uso = 1 AND 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "filial"=>$row['filial'],
        "num_fogo"=>$row['num_fogo'],
        "data_cadastro"=>date("d/m/Y", strtotime($row['data_cadastro'])) ,
        "medida"=>$row['medida'],
        "calibragem_maxima"=>$row['calibragem_maxima'],
        "marca"=>$row['marca'],
        "modelo"=>$row['modelo'],
        "num_serie"=>$row['num_serie'],
        "vida"=> $row['vida'],
        "posicao_inicio"=>$row['posicao_inicio'],
        "veiculo"=>$row['veiculo'],
        "km_rodado"=>$row['km_rodado'],
        "situacao"=>$row['situacao'],
        "localizacao"=>$row['localizacao'],
        "suco01"=>$row['suco01'],
        "suco02"=>$row['suco02'],
        "suco03"=>$row['suco03'],
        "suco04"=>$row['suco04'],
        "usuario"=>$row['nome_usuario'],
        "acoes"=> '<a href="javascript:void();" data-id="'.$row['idpneus'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="javascript:void(); " data-id="'.$row['idpneus'].'"  class="btn btn-danger btn-sm deleteBtn" >Descartar</a>'
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
