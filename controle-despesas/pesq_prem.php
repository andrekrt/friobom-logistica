<?php
include '../conexao-on.php';
include 'funcoes.php';

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
    $condicao = "AND viagem.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (viagem.placa_veiculo LIKE :placa_veiculo) OR (viagem.num_carregemento LIKE :num_carregemento) OR (viagem.nome_rota LIKE :nome_rota) OR (viagem.nome_motorista LIKE :nome_motorista) ";
    $searchArray = array( 
        'placa_veiculo'=>"%$searchValue%",
        'num_carregemento'=>"%$searchValue%",
        'nome_rota'=>"%$searchValue%",
        'nome_motorista'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM viagem WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM viagem WHERE 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT *  FROM viagem LEFT JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo LEFT JOIN rotas ON viagem.cod_rota=rotas.cod_rota WHERE 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $devolucoes = devolucoes($row['num_carregemento']);
    $percDev = $devolucoes==0?1:0;
    $entregasLiq = $row['qtd_entregas']-$devolucoes;
    $fusion = mauUsoFusion($row['num_carregemento']);
    $checklist = checklist($row['num_carregemento']);
    $metaComb = $row['media_comtk']/$row['meta_combustivel'];
    $velocidade = ocorrenciasVel($row['num_carregemento']);
    $metaDias = $row['meta_dias']?$row['meta_dias']:1;
    $diasEmRota =$row['dias_em_rota']>=1?$row['dias_em_rota']:1;
    $percDias = $metaDias/$diasEmRota;

    if($fusion['percentual']<1){
        $valorPago = 0;
    }elseif($fusion['percentual']>=1){
        $valorFusion = 0.5*$entregasLiq;
        $valorCheck = $checklist*0.1*$entregasLiq;
        $valorComb = $metaComb>=1?1*0.1*$entregasLiq:0;
        $valorDev = $percDev*0.1*$entregasLiq;
        $valorDias = $percDias>=1?1*0.1*$entregasLiq:0;
        $valorVeloc = 0.1*$velocidade*$entregasLiq;
        $valorPago = $valorFusion+$valorCheck+$valorComb+$valorDev+$valorDias+$valorVeloc;
    }

    $data[] = array(
        "data_saida"=>date("d/m/Y", strtotime($row['data_saida'])),
        "data_retorno"=>date("d/m/Y", strtotime($row['data_chegada'])),
        "dias_em_rota"=>$row['dias_em_rota'],
        "num_carregemento"=> $row['num_carregemento'],
        "placa_veiculo"=>$row['placa_veiculo'],
        "nome_rota"=>$row['nome_rota'],
        "nome_motorista"=>$row['nome_motorista'],
        "qtd_entregas"=>$row['qtd_entregas'],
        "valor_devolvido"=> $devolucoes,
        "entregasLiq"=>$entregasLiq,
        "fusionMauUso"=>$fusion['qtd'],
        "percFusion"=>($fusion['percentual']*100)."%",
        "checklist"=>($checklist*100) ."%",
        "combustivel"=>number_format($metaComb*100,2,",",".")."%",
        "percDev"=>($percDev*100)."%",
        "percDias"=>number_format($percDias*100,2,",",".")."%",
        "velocidade"=>($velocidade*100)."%",
        "premioTotal"=>"R$ ".number_format($row['qtd_entregas']*1,2,",","."),
        "premio_real"=>"R$ ". number_format($valorPago,2,",",".") ,
        "percPremio"=>number_format(($valorPago/($row['qtd_entregas']*1))*100,2,",",".")."%",
        "pago"=>$row['pago'],
        "acoes"=>'<a class="btn btn-sm btn-primary" href="pagar-premiacao.php?id='.$row['iddespesas'].'"> Pagar </a>'
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
