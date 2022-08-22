<?php

use Mpdf\Tag\A;

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
	$searchQuery = " AND (placa_veiculo LIKE :placa_veiculo OR nome_rota LIKE :nome_rota OR nome_motorista LIKE :nome_motorista OR ajudante LIKE :ajudante ) ";
    $searchArray = array( 
        'placa_veiculo'=>"%$searchValue%", 
        'nome_rota'=>"%$searchValue%",
        'nome_motorista'=>"%$searchValue%",
        'ajudante'=>"%$searchValue%",
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM checklist");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM checklist WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM checklist LEFT JOIN veiculos ON checklist.veiculo = veiculos.cod_interno_veiculo LEFT JOIN rotas ON checklist.rota = rotas.cod_rota LEFT JOIN motoristas ON checklist.motorista = motoristas.cod_interno_motorista LEFT JOIN usuarios ON checklist.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $edit = "";
    $delete = "";
    $pdf = "";
    $retorno = "";
    if($row['chegada']<"2022-01-01"){
        $edit = ' <a href="form-edit-check.php?id='.$row['idchecklist'].'" class="btn btn-info btn-sm editbtn" >Editar</a>';
        $delete = ' <a href="excluir-check.php?id='.$row['idchecklist'].' " class="btn btn-danger btn-sm deleteBtn" >Deletar</a>';
        $retorno = ' <a href="form-retorno.php?id='.$row['idchecklist'].'" class="btn btn-success btn-sm deleteBtn" >Retorno</a>';
        $pdf = ' <a href="ficha.php?id='.$row['idchecklist'].'" class="btn btn-secondary btn-sm deleteBtn" >Ficha</a>';
    }else{
        $pdf = ' <a href="ficha.php?id='.$row['idchecklist'].'" class="btn btn-secondary btn-sm deleteBtn" >Ficha</a>';
        $edit = ' <a href="form-edit-retorno.php?id='.$row['idchecklist'].'" class="btn btn-info btn-sm editbtn" >Editar Retorno</a>';
    }
    $data[] = array(
        "idchecklist"=>$row['idchecklist'],
        "saida"=>date("d/m/Y",strtotime($row['saida'])),
        "placa_veiculo"=>$row['placa_veiculo'],
        "anexos"=> '<a target="_blank" href="uploads/'.$row['idchecklist'].'/saida">Fotos</a> ',
        "retorno"=> '<a target="_blank" href="uploads/'.$row['idchecklist'].'/retorno">Fotos</a> ',
        "qtdnf"=>$row['qtdnf'],
        "vl_carga"=>$row['vl_carga'],
        "km_saida"=>$row['km_saida'],
        "nome_rota"=>$row['nome_rota'],
        "nome_motorista"=>$row['nome_motorista'],
        "ajudante"=>$row['ajudante'],
        "chegada"=>date("d/m/Y",strtotime($row['chegada'])),
        "km_rota"=>$row['km_rota'],
        "litros_abastecido"=>$row['litros_abastecido'],
        "valor_abastecido"=>$row['valor_abastecido'],
        "usuario"=>$row['nome_usuario'],
        "acoes"=> $edit. $retorno. $pdf. $delete
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
