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
	$searchQuery = " AND (idsupervisor LIKE :idsupervisor OR nome_supervisor LIKE :nome_supervisor ) ";
    $searchArray = array( 
        'idsupervisor'=>"%$searchValue%",
        'nome_supervisor'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM supervisores");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM supervisores WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT idsupervisor, nome_supervisor, nome_cidade, placa_veiculo FROM supervisores LEFT JOIN cidades ON supervisores.cidade_residencia = cidades.idcidades LEFT JOIN veiculos ON supervisores.veiculo = veiculos.cod_interno_veiculo WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "idsupervisor"=>$row['idsupervisor'] ,
            "nome_supervisor"=>$row['nome_supervisor'],
            "cidade_residencia"=>$row['nome_cidade'],
            "veiculo"=>$row['placa_veiculo'],
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['idsupervisor'].'"  class="btn btn-info btn-sm editbtn" >Editar</a>  <a href="excluir-supervisor.php?codigo='.$row['idsupervisor'].' " data-id="'.$row['idsupervisor'].'"  class="btn btn-danger btn-sm deleteBtn" onclick=\'return confirm("Deseja Excluir?");\'>Deletar</a>'
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
