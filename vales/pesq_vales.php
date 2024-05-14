<?php
include '../conexao.php';
session_start();
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
    $condicao = "AND vales.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (motorista LIKE :motorista OR rota LIKE :rota OR carregamento LIKE :carregamento OR nome_motorista LIKE :nome_motorista OR nome_rota LIKE :nome_rota OR nome_usuario LIKE :nome_usuario OR situacao LIKE :situacao ) ";
    $searchArray = array( 
        'motorista'=>"%$searchValue%", 
        'rota'=>"%$searchValue%",
        'carregamento'=>"%$searchValue%",
        'nome_motorista'=>"%$searchValue%",
        'nome_rota'=>"%$searchValue%",
        'situacao'=>"%$searchValue%",
        'nome_usuario'=>"%$searchValue%",
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM vales WHERE 1 $condicao ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM vales LEFT JOIN motoristas ON motoristas.cod_interno_motorista=vales.motorista LEFT JOIN rotas ON rotas.cod_rota=vales.rota LEFT JOIN usuarios ON usuarios.idusuarios=vales.usuario WHERE 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM vales LEFT JOIN motoristas ON motoristas.cod_interno_motorista=vales.motorista LEFT JOIN rotas ON rotas.cod_rota=vales.rota LEFT JOIN usuarios on usuarios.idusuarios=vales.usuario  WHERE 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

// Bind values
foreach($searchArray as $key=>$search){
    $stmt->bindValue(':'.$key, $search,PDO::PARAM_STR);
}

$stmt->bindValue(':limit', (int)$row, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$rowperpage+(int)$row, PDO::PARAM_INT);
$stmt->execute();
$empRecords = $stmt->fetchAll();

$data = array();

foreach($empRecords as $row){
    $editar= '';
    $imprimir = '<a href="vale-pdf.php?idvale='.$row['idvale'].' " data-id="'.$row['idvale'].'"  class="btn btn-secondary btn-sm deleteBtn" target="_blank"  >Imprimir</a>';
    $deletar= "";
    $btnPagar = '';

    if($row['situacao']=='Não Resgatado' && ($_SESSION['idUsuario'] == 1 || $_SESSION['idUsuario'] == 45) ){
        $editar=' <a href="javascript:void();" data-id="'.$row['idvale'].'"  class="btn btn-info btn-sm editbtn" >Editar</a> ';
        $deletar = ' <a  data-id="'.$row['idvale'].'"  class="btn btn-danger btn-sm deleteBtn" onclick=\'confirmaDelete(' . $row['idvale'] . ')\' >Deletar</a>  ';
    }

    if($row['pago']==0 && ($_SESSION['idUsuario']==47)){
        $btnPagar= ' <a data-id="'.$row['idvale'].'"  class="btn btn-success btn-sm " onclick=\'confirmaPaga(' . $row['idvale'] . ')\'>Pagar</a> ';
    }

    $pago = $row['pago']==0?"NÃO":"SIM";
    
    $data[] = array(
        "idvale"=>$row['idvale'],
        "data_lancamento"=>date("d/m/Y", strtotime($row['data_lancamento'])),
        "motorista"=>$row['nome_motorista'],
        "rota"=>$row['nome_rota'],
        "valor"=>"R$ ".number_format($row['valor'],2,",",".") ,
        "tipo_vale"=>$row['tipo_vale'],
        "carregamento"=>$row['carregamento'],    
        "situacao"=>$row['situacao'],   
        "pago"=>$pago,
        "usuario"=>$row['nome_usuario'],
        "acoes"=>$imprimir . $btnPagar. $editar . $deletar
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
