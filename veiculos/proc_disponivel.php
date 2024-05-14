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
$status = $_GET['status'];

$filial = $_SESSION['filial'];
if($filial===99){
    $condicao = " ";
}else{
    $condicao = "AND veiculos.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (cod_interno_veiculo LIKE :cod_interno_veiculo OR tipo_veiculo LIKE :tipo_veiculo OR placa_veiculo LIKE :placa_veiculo OR categoria LIKE :categoria OR marca LIKE :marca ) ";
    $searchArray = array( 
        'cod_interno_veiculo'=>"%$searchValue%", 
        'tipo_veiculo'=>"%$searchValue%",
        'placa_veiculo'=>"%$searchValue%",
        'categoria'=>"%$searchValue%",
        'marca'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM veiculos WHERE ativo = 1 AND situacao ='$status' $condicao ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM veiculos WHERE 1 $condicao AND situacao ='$status' AND ativo = 1".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM veiculos WHERE 1 AND situacao ='$status' AND ativo = 1 $condicao".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $codVeiculo = $row['cod_interno_veiculo'];
    $kmRestante = $row['km_atual']-$row['km_ultima_revisao'];
    $kmRestanteAlinhamento = $row['km_atual']-$row['km_alinhamento'];
    $cor = "";
    if ($row['categoria']=='Truck' && $kmRestante >= 20000) {
        $situacao = "Pronto para Revisão";
        
    } elseif($row['categoria']=='Toco' && $kmRestante >= 20000) {
        $situacao = "Pronto para Revisão";
    }elseif($row['categoria']=='Mercedinha' && $kmRestante >= 15000){
        $situacao = "Pronto para Revisão";
    }else{
        $situacao = "Aguardando";
    }

    //situacao alinhamento
    if($kmRestanteAlinhamento>=7000){
        $situacaoAlinhamento = 'Pronto para Alinhamento';
    }else{
        $situacaoAlinhamento = 'Aguardando';
    }
    $data[] = array(
        "cod_interno_veiculo"=>$row['cod_interno_veiculo'],
        "tipo_veiculo"=>$row['tipo_veiculo'],
        "placa_veiculo"=>$row['placa_veiculo'],
        "categoria"=>$row['categoria'],
        "marca"=>$row['marca'],
        "peso_maximo"=>$row['peso_maximo'],
        "cubagem"=>$row['cubagem'],
        "km_ultima_revisao"=>$row['km_ultima_revisao'],
        "data_revisao_oleo"=>$row['data_revisao_oleo']?date("d/m/Y", strtotime($row['data_revisao_oleo'])):"",
        "km_atual"=>$row['km_atual'],
        "data_revisao_diferencial"=>$row['data_revisao_diferencial']?date("d/m/Y", strtotime($row['data_revisao_diferencial'])):"",
        "km_revisao_diferencial"=>$row['km_revisao_diferencial'],
        "km_restante"=>$row['km_atual']-$row['km_ultima_revisao'],
        "km_alinhamento"=>$row['km_alinhamento'],
        "km_restante_alinhamento"=>$kmRestanteAlinhamento,
        "alinhamento"=>$situacaoAlinhamento,
        "situacao"=>$situacao,
        "media_combustivel"=>number_format($row['meta_combustivel'],2,",",".") ,
        "acoes"=> '<a href="javascript:void();" data-id="'.$row['cod_interno_veiculo'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="desativar.php?codVeiculo='.$row['cod_interno_veiculo'].' " data-id="'.$row['cod_interno_veiculo'].'"  class="btn btn-danger btn-sm deleteBtn" >Desativar</a>'
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
