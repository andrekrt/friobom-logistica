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
	$searchQuery = " AND (data_entrada LIKE :data_entrada OR qualidade LIKE :qualidade OR nome_usuario LIKE :nome_usuario OR nome_fantasia LIKE :nome_fantasia OR nf LIKE :nf) ";
    $searchArray = array( 
        'data_entrada'=>"%$searchValue%", 
        'qualidade'=>"%$searchValue%",
        'nome_usuario'=>"%$searchValue%",
        'nome_fantasia'=>"%$searchValue%",
        'nf'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM combustivel_entrada ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM combustivel_entrada LEFT JOIN usuarios ON combustivel_entrada.usuario = usuarios.idusuarios LEFT JOIN fornecedores ON combustivel_entrada.fornecedor = fornecedores.id WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM combustivel_entrada LEFT JOIN usuarios ON combustivel_entrada.usuario = usuarios.idusuarios LEFT JOIN fornecedores ON combustivel_entrada.fornecedor = fornecedores.id WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $botao = "";
    $aprovar = "";
    if($_SESSION['tipoUsuario']==4 && $row['situacao']=="Em Análise"){
        $botao=' <a href="javascript:void();" data-id="'.$row['idcombustivel_entrada'].'"  class="btn btn-info btn-sm editbtn" >Editar</a>  <a onclick=\'confirmaDelete('.$row['idcombustivel_entrada'].')\' data-id="'.$row['idcombustivel_entrada'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>';
        $aprovar = ' <a onclick=\'confirmaAprovacao('.$row['idcombustivel_entrada'].', '.$row['total_litros'].')\' data-id="'.$row['idcombustivel_entrada'].'"  class="btn btn-success btn-sm deleteBtn" >Aprovar</a> ';
    }
    $valorLitro = ($row['valor_litro'])?number_format($row['valor_litro'],4,",","."):null;
    $frete = ($row['frete'])?number_format($row['frete'],4,",","."):null;
    $total_litros = ($row['total_litros'])?number_format($row['total_litros'],4,",","."):null;
    $valor_comb = ($row['total_litros'] || $row['valor_litro'])?number_format($row['total_litros']*$row['valor_litro'],2,",","."):null;
    $valor_total = ($row['valor_total'])?number_format($row['valor_total'],4,",","."):null;
    $fantasia = $row['nome_fantasia']?mb_convert_encoding($row['nome_fantasia'],'ISO-8859-1', 'UTF-8'):null;
    $situacao = $row['situacao']?mb_convert_encoding($row['situacao'],'ISO-8859-1', 'UTF-8'):null;
    $qualidade = $row['qualidade']?mb_convert_encoding($row['qualidade'],'ISO-8859-1', 'UTF-8'):null;

    if($valor_total==null || $total_litros==null){
        $vlLitroFinal=null;
    }else{
        $vlLitroFinal = $row['valor_total']/$row['total_litros'];
        $vlLitroFinal ="R$ ". number_format($vlLitroFinal,4,",",".");
    }

    if($frete==null || $total_litros == null){
        $freteLitro = null;
    }else{
        $freteLitro = $row['frete']/$row['total_litros'];
        $freteLitro ="R$" . number_format($freteLitro,4,",",".");
    }
    

    $data[] = array(
        "idcombustivel_entrada"=>$row['idcombustivel_entrada'],
        "data_entrada"=>date("d/m/Y", strtotime($row['data_entrada'])),
        "nf"=>$row['nf'],
        "valor_litro"=>"R$ ".$valorLitro,
        "frete"=>"R$ ". $frete,
        "total_litros"=>$total_litros ,
        "valor_comb"=>$valor_comb,
        "valor_total"=>"R$ " . $valor_total,
        "frete_litro"=>$freteLitro,
        "vl_litro_final"=>$vlLitroFinal,
        "nome_fantasia"=>$row['nome_fantasia'],
        "qualidade"=>$qualidade,
        "situacao"=>$row['situacao'],
        "nome_usuario"=>$row['nome_usuario'],
        "acoes"=>$aprovar. $botao
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
