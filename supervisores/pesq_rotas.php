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
	$searchQuery = " AND (saida LIKE :saida OR nome_supervisor LIKE :nome_supervisor OR placa_veiculo LIKE :placa_veiculo OR chegada LIKE :chegada OR velocidade_max LIKE :velocidade_max OR nome_cidade LIKE :nome_cidade OR rca01 LIKE :rca01 OR rca02 LIKE :rca02 OR obs LIKE :obs OR nome_usuario LIKE :nome_usuario) ";
    $searchArray = array( 
        'saida'=>"%$searchValue%",
        'nome_supervisor'=>"%$searchValue%",
        'placa_veiculo'=>"%$searchValue%",
        'chegada'=>"%$searchValue%",
        'velocidade_max'=>"%$searchValue%",
        'rca01'=>"%$searchValue%",
        'rca02'=>"%$searchValue%",
        'obs'=>"%$searchValue%",
        'nome_usuario'=>"%$searchValue%",
        'nome_cidade'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM rotas_supervisores");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM rotas_supervisores rotas_supervisores LEFT JOIN supervisores ON rotas_supervisores.supervisor = supervisores.idsupervisor LEFT JOIN veiculos ON rotas_supervisores.veiculo = veiculos.cod_interno_veiculo LEFT JOIN cidades cidade1 ON rotas_supervisores.cidade_inicio = cidade1.idcidades LEFT JOIN cidades cidade2 ON rotas_supervisores.cidade_final = cidade2.idcidades LEFT JOIN cidades cidade3 ON supervisores.cidade_residencia = cidade3.idcidades LEFT JOIN usuarios ON rotas_supervisores.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT idrotas, saida, nome_supervisor, placa_veiculo, chegada, velocidade_max, cidade1.nome_cidade as cidade_saida, rca01, rca02, obs, cidade2.nome_cidade as cidade_final, diarias, qtd_visitas, nome_usuario, cidade3.nome_cidade as residencia FROM rotas_supervisores rotas_supervisores LEFT JOIN supervisores ON rotas_supervisores.supervisor = supervisores.idsupervisor LEFT JOIN veiculos ON rotas_supervisores.veiculo = veiculos.cod_interno_veiculo LEFT JOIN cidades cidade1 ON rotas_supervisores.cidade_inicio = cidade1.idcidades LEFT JOIN cidades cidade2 ON rotas_supervisores.cidade_final = cidade2.idcidades LEFT JOIN cidades cidade3 ON supervisores.cidade_residencia = cidade3.idcidades LEFT JOIN usuarios ON rotas_supervisores.usuario = usuarios.idusuarios WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
            "saida"=>date("d/m/Y H:i", strtotime($row['saida'])) ,
            "nome_supervisor"=>$row['nome_supervisor'],
            "placa_veiculo"=>$row['placa_veiculo'],
            "chegada"=>date("d/m/Y H:i", strtotime($row['chegada'])),
            "velocidade_max"=>$row['velocidade_max'],
            "cidade_saida"=>$row['cidade_saida'],
            "rca01"=>$row['rca01'],
            "rca02"=>$row['rca02'],
            "obs"=>$row['obs'],
            "cidade_final"=>$row['cidade_final'],
            "diarias"=>number_format($row['diarias'],1,",",".") ,
            "qtd_visitas"=>$row['qtd_visitas'],
            "cidade_residencia"=>$row['residencia'],
            "nome_usuario"=>$row['nome_usuario'],
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['idrotas'].'"  class="btn btn-info btn-sm editbtn" >Editar</a>  <a href="excluir-rota.php?idRota='.$row['idrotas'].' " data-id="'.$row['idrotas'].'"  class="btn btn-danger btn-sm deleteBtn" onclick=\'return confirm("Deseja Excluir?");\'>Deletar</a>'
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
