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

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (data_abastecimento LIKE :data_abastecimento OR carregamento LIKE :carregamento OR placa_veiculo LIKE :placa_veiculo OR tipo_abastecimento LIKE :tipo_abastecimento ) ";
    $searchArray = array( 
        'data_abastecimento'=>"%$searchValue%", 
        'carregamento'=>"%$searchValue%",
        'placa_veiculo'=>"%$searchValue%",
        'tipo_abastecimento'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM combustivel_saida");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM combustivel_saida LEFT JOIN usuarios ON combustivel_saida.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM combustivel_saida LEFT JOIN usuarios ON combustivel_saida.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $delete="";
    $editar = "";
    $ficha = "";
    if( $_SESSION['tipoUsuario']==4 || $_SESSION['tipoUsuario']==99){
        $delete='  <a href="excluir-entrada.php?idSaida='.$row['idcombustivel_saida'].' " data-id="'.$row['idcombustivel_saida'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>';
        $editar = ' <a href="javascript:void();" data-id="'.$row['idcombustivel_saida'].'"  class="btn btn-info btn-sm editbtn" >Editar</a>';
        
    }elseif($_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario']==99){
        $ficha = ' <a href="ficha-abastecimento.php?id='.$row['idcombustivel_saida'].' " data-id="'.$row['idcombustivel_saida'].'"  class="btn btn-secondary btn-sm deleteBtn" >Ficha</a>';
    }
    $data[] = array(
        "idcombustivel_saida"=>$row['idcombustivel_saida'],
        "data_abastecimento"=>date("d/m/Y", strtotime($row['data_abastecimento'])),
        "litro_abastecimento"=> number_format($row['litro_abastecimento'],2,",","."),
        "valor_medio"=> number_format($row['preco_medio'],2,",","."),
        "valor_total"=> number_format($row['valor_total'],2,",","."),
        "carregamento"=>$row['carregamento'] ,
        "km"=>$row['km'] ,
        "placa_veiculo"=>$row['placa_veiculo'],
        "rota"=>$row['rota'],
        "motorista"=>$row['motorista'],
        "tipo_abastecimento"=>($row['tipo_abastecimento']),
        "nome_usuario"=>$row['nome_usuario'],
        "acoes"=>  $editar . $delete . $ficha
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
