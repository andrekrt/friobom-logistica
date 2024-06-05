<?php
session_start();
include '../../conexao.php';

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
    $condicao = "AND manutencao_pneu.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (num_fogo LIKE :num_fogo OR tipo_manutencao LIKE :tipo_manutencao OR fornecedor LIKE :fornecedor) ";
    $searchArray = array( 
        'num_fogo'=>"%$searchValue%", 
        'tipo_manutencao'=>"%$searchValue%", 
        'fornecedor'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM manutencao_pneu LEFT JOIN pneus ON manutencao_pneu.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON manutencao_pneu.usuario = usuarios.idusuarios WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM manutencao_pneu LEFT JOIN pneus ON manutencao_pneu.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON manutencao_pneu.usuario = usuarios.idusuarios WHERE 1 $condicao".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM manutencao_pneu LEFT JOIN pneus ON manutencao_pneu.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON manutencao_pneu.usuario = usuarios.idusuarios WHERE 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
        "data_manutencao"=>date("d/m/Y",strtotime( $row['data_manutencao'])) ,
        "tipo_manutencao"=>$row['tipo_manutencao'],
        "km_veiculo"=>$row['km_veiculo'],
        "km_pneu"=>$row['km_pneu'],
        "valor"=>"R$ ". str_replace(".",",",$row['valor']) ,
        "num_nf"=>$row['num_nf'],
        "fornecedor"=>$row['fornecedor'],
        "suco01"=>$row['suco01'],
        "suco02"=>$row['suco02'] ,
        "suco03"=>$row['suco03'] ,
        "suco04"=>$row['suco04'] ,
        "nome_usuario"=>$row['nome_usuario'],
        "acoes"=> '<a href="javascript:void();" data-id="'.$row['idmanutencao_pneu'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a  data-id="'.$row['idmanutencao_pneu'].'" onclick=\'confirmaDelete(' . $row['idmanutencao_pneu'] . ')\' class="btn btn-danger btn-sm deleteBtn" >Deletar</a>'
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
