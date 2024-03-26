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
	$searchQuery = " AND (carregamento LIKE :carregamento OR placa_veiculo LIKE :placa_veiculo OR nome_motorista LIKE :nome_motorista OR nome_rota LIKE :nome_rota OR situacao LIKE :situacao) ";
    $searchArray = array( 
        'carregamento'=>"%$searchValue%",
        'placa_veiculo'=>"%$searchValue%",
        'nome_motorista'=>"%$searchValue%",
        'nome_rota'=>"%$searchValue%",
        'situacao'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM fusion WHERE situacao = 'Finalizada'");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM fusion LEFT JOIN veiculos ON fusion.veiculo = veiculos.cod_interno_veiculo LEFT JOIN motoristas ON fusion.motorista = motoristas.cod_interno_motorista LEFT JOIN rotas ON fusion.rota = rotas.cod_rota LEFT JOIN usuarios ON fusion.usuario = usuarios.idusuarios WHERE 1 AND fusion.situacao = 'Finalizada' ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM fusion LEFT JOIN veiculos ON fusion.veiculo = veiculos.cod_interno_veiculo LEFT JOIN motoristas ON fusion.motorista = motoristas.cod_interno_motorista LEFT JOIN rotas ON fusion.rota = rotas.cod_rota LEFT JOIN usuarios ON fusion.usuario = usuarios.idusuarios WHERE 1 AND fusion.situacao = 'Finalizada' ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "saida"=>date("d/m/Y ", strtotime($row['saida'])) ,
            "termino_rota"=>date("d/m/Y H:i", strtotime($row['termino_rota'])),
            "chegada_empresa"=>date("d/m/Y H:i", strtotime($row['chegada_empresa'])),
            "carregamento"=>$row['carregamento'],
            "placa"=>$row['placa_veiculo'],
            "motorista"=>$row['nome_motorista'],
            "rota"=>$row['nome_rota'],
            "num_entregas"=>$row['num_entregas'],
            "entregas_feitas"=>$row['entregas_feitas'],
            "erros_fusion"=>$row['erros_fusion'],
            "num_dev"=>$row['num_dev'],
            "entregas_liq"=>$row['entregas_liq'],
            "uso_fusion"=>($row['uso_fusion']*100)."%",
            "checklist"=>($row['checklist']*100)."%",
            "media_km"=>($row['media_km']*100)."%",
            "devolucao"=>($row['devolucao']*100)."%",
            "dias_rota"=>($row['dias_rota']*100)."%",
            "vel_max"=>($row['vel_max']*100)."%",
            "premio_possivel"=>"R$".number_format($row['premio_possivel'],2,",",".") ,
            "premio_real"=>"R$".number_format($row['premio_real'],2,",",".") ,
            "premio_alcancado"=>number_format($row['premio_alcancado']*100,2,",",".")."%" ,
            "situacao"=>$row['situacao'],
            "nome_usuario"=>$row['nome_usuario'],
            // "acoes"=> '<a href="javascript:void();" data-id="'.$row['idfusion'].'"  class="btn btn-info btn-sm editbtn" >Editar</a>  <a href="excluir-fusion.php?id='.$row['idfusion'].' " data-id="'.$row['idfusion'].'"  class="btn btn-danger btn-sm deleteBtn" onclick=\'return confirm("Deseja Excluir?");\'>Deletar</a>'
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
