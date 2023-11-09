<?php
include '../../conexao.php';
session_start();

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
	$searchQuery = " AND (num_fogo LIKE :num_fogo OR carcaca LIKE :carcaca) ";
    $searchArray = array( 
        'num_fogo'=>"%$searchValue%", 
        'carcaca'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM sucos LEFT JOIN pneus ON sucos.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON sucos.usuario = usuarios.idusuarios");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM sucos LEFT JOIN pneus ON sucos.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON sucos.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT sucos.*, pneus.num_fogo, usuarios.nome_usuario FROM sucos LEFT JOIN pneus ON sucos.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON sucos.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $excluir = '';
    if( $_SESSION['tipoUsuario']==99){
        $excluir='<a href="excluir-suco.php?idsuco='.$row['idsucos'].' "data-id="'.$row['idsucos'].'"  class="btn btn-danger btn-sm deleteBtn" onclick="return confirm('."Tem certeza de que deseja excluir este item?".')">Deletar</a>';
    }
    $data[] = array(
        "num_fogo"=>$row['num_fogo'],
        "data_medicao"=>date("d/m/Y H:i",strtotime( $row['data_medicao'])) ,
        "km_veiculo"=>$row['km_veiculo'],
        "km_pneu"=>$row['km_pneu'],
        "carcaca"=>$row['carcaca'],
        "suco01"=>$row['suco01'],
        "suco02"=>$row['suco02'] ,
        "suco03"=>$row['suco03'] ,
        "suco04"=>$row['suco04'] ,
        "calibragem"=>$row['calibragem'] ,
        "nome_usuario"=>$row['nome_usuario'],
        "acoes"=> '<a href="javascript:void();" data-id="'.$row['idsucos'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a> ' . $excluir 
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
