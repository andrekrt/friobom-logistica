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
	$searchQuery = " AND (cod_interno_veiculo LIKE :cod_interno_veiculo OR 
        tipo_veiculo LIKE :tipo_veiculo OR 
        placa_veiculo LIKE :placa_veiculo OR categoria LIKE :categoria ) ";
    $searchArray = array( 
        'cod_interno_veiculo'=>"%$searchValue%", 
        'tipo_veiculo'=>"%$searchValue%",
        'placa_veiculo'=>"%$searchValue%",
        'categoria'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM veiculos ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM veiculos WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM veiculos WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    if ($row['categoria']=='Truck' && $kmRestante >= 20000) {
        $situacao = "Pronto para Revisão";
    } elseif($row['categoria']=='Toco' && $kmRestante >= 20000) {
        $situacao = "Pronto para Revisão";
    }elseif($row['categoria']=='3/4' && $kmRestante >= 15000){
        $situacao = "Pronto para Revisão";
    }else{
        $situacao = "Aguardando";
    }
    $data[] = array(
            "cod_interno_veiculo"=>$row['cod_interno_veiculo'],
            "tipo_veiculo"=>$row['tipo_veiculo'],
            "placa_veiculo"=>$row['placa_veiculo'],
            "categoria"=>$row['categoria'],
            "peso_maximo"=>$row['peso_maximo'],
            "cubagem"=>$row['cubagem'],
            "km_ultima_revisao"=>$row['km_ultima_revisao'],
            "data_revisao"=> date("d/m/Y", strtotime($row['data_revisao'])),
            "km_atual"=>$row['km_atual'],
            "km_revisao_diferencial"=>$row['km_revisao_diferencial'],
            "km_restante"=>$row['km_atual']-$row['km_ultima_revisao'],
            "situacao"=>$situacao,
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['cod_interno_veiculo'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a href="excluir.php?codVeiculo='.$row['cod_interno_veiculo'].' " data-id="'.$row['cod_interno_veiculo'].'"  class="btn btn-danger btn-sm deleteBtn" >Deletar</a>'
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
