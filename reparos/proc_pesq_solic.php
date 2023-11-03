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
	$searchQuery = " AND (placa LIKE :placa OR motorista LIKE :motorista OR rota LIKE :rota OR local_reparo LIKE :local_reparo ) ";
    $searchArray = array( 
        'placa'=>"%$searchValue%", 
        'motorista'=>"%$searchValue%",
        'rota'=>"%$searchValue%",
        'local_reparo'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT token) AS allcount FROM solicitacoes_new LEFT JOIN peca_reparo ON solicitacoes_new.peca_servico = peca_reparo.id_peca_reparo LEFT JOIN usuarios ON solicitacoes_new.usuario = usuarios.idusuarios  ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(DISTINCT token) AS allcount FROM solicitacoes_new LEFT JOIN peca_reparo ON solicitacoes_new.peca_servico = peca_reparo.id_peca_reparo LEFT JOIN usuarios ON solicitacoes_new.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery );
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT solicitacoes_new.id, solicitacoes_new.token,solicitacoes_new.data_atual, solicitacoes_new.placa, solicitacoes_new.motorista, solicitacoes_new.rota, solicitacoes_new.problema, solicitacoes_new.local_reparo, solicitacoes_new.situacao, COUNT(peca_servico) as peca, SUM(qtd) as qtd, GROUP_CONCAT('R$ ', vl_unit) as vlUnit, SUM(vl_total) as vlTotal, frete, num_nf,nome_usuario, nome_fantasia FROM solicitacoes_new LEFT JOIN peca_reparo ON solicitacoes_new.peca_servico = peca_reparo.id_peca_reparo LEFT JOIN usuarios ON solicitacoes_new.usuario = usuarios.idusuarios LEFT JOIN fornecedores ON solicitacoes_new.fornecedor = fornecedores.id WHERE 1 ".$searchQuery." GROUP BY token ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $editar ="";
    $excluir="";
    $imprimir="";
    
    if( $_SESSION['tipoUsuario']==4){
        $excluir=' <a href="excluir.php?token='.$row['token'].' " data-id="'.$row['id'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a> ';
    }
    if($row['situacao']==="Aprovado"){
        $imprimir=' <a class="btn btn-secondary  btn-sm" href="gerar-pdf.php?token='.$row['token'].'">Imprimir</a>';
    }elseif($row['situacao'!="Aprovado"]){
        $editar= ' <a href="form-edit-solic.php?idPneu='.$row['id'].'" data-id="'.$row['id'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>'; 
    }
    $data[] = array(
        "token"=>$row['token'],
        "data_atual"=>date("d/m/Y", strtotime($row['data_atual'])),
        "placa"=>$row['placa'],
        "motorista"=>$row['motorista'],
        "rota"=>$row['rota'],
        "problema"=>$row['problema'],
        "fornecedor"=>$row['nome_fantasia'],
        "peca"=> $row['peca'],
        "qtd"=>str_replace(".",",",$row['qtd']),
        "vlTotal"=>"R$ ". str_replace(".",",",$row['vlTotal']+$row['frete']) ,
        "frete"=> "R$ ". str_replace(".", ",", $row['frete']),
        "nf"=> $row['num_nf'],
        "situacao"=>$row['situacao'],
        "nome_usuario"=>$row['nome_usuario'],
        "acoes"=>$imprimir . $editar . $excluir   
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
