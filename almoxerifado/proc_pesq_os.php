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

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (placa LIKE :placa OR descricao_problema LIKE :descricao_problema OR tipo_manutencao LIKE :tipo_manutencao OR causador LIKE :causador OR requisicao_saida LIKE :requisicao_saida OR solicitacao_peca LIKE :solicitacao_peca OR num_nf LIKE :num_nf OR situacao LIKE :situacao ) ";
    $searchArray = array( 
        'placa'=>"%$searchValue%", 
        'descricao_problema'=>"%$searchValue%",
        'tipo_manutencao'=>"%$searchValue%",
        'causador'=>"%$searchValue%",
        'requisicao_saida'=>"%$searchValue%",
        'solicitacao_peca'=>"%$searchValue%",
        'num_nf'=>"%$searchValue%",
        'situacao'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM ordem_servico ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM ordem_servico LEFT JOIN usuarios ON ordem_servico.idusuario = usuarios.idusuarios WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM ordem_servico LEFT JOIN usuarios ON ordem_servico.idusuario = usuarios.idusuarios WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $acoes= '' ;
    if($row['situacao']!='Encerrada'):
        $acoes = '<a class=" icon-acoes" target="_blank" href="ficha-ordemservico.php?id='.$row['idordem_servico'].' " data-id="'.$row['idordem_servico'].'"  class="btn btn-danger btn-sm deleteBtn" ><img src="../assets/images/icones/print.png" ></a>  <a class="icon-acoes" href="excluir-ordemservico.php?idordemServico='.$row['idordem_servico'].' " data-id="'.$row['idordem_servico'].'"  class="btn btn-danger btn-sm deleteBtn" ><img src="../assets/images/icones/delete.png" ></a> ';
    endif;
    $data[] = array(
            "idordem_servico"=>$row['idordem_servico'],
            "data_abertura"=>date("d/m/Y",strtotime($row['data_abertura'])) ,
            "placa"=>$row['placa'],
            "descricao_problema"=>$row['descricao_problema'],
            "tipo_manutencao"=>$row['tipo_manutencao'],
            "corretiva"=>$row['corretiva']?"SIM":"NÃO",
            "preventiva"=>$row['preventiva']?"SIM":"NÃO",
            "externa"=>$row['externa']?"SIM":"NÃO",
            "oleo"=>$row['oleo']?"SIM":"NÃO",
            "higienizacao"=>$row['higienizacao']?"SIM":"NÃO",
            "causador"=>$row['causador'],
            "situacao"=>$row['situacao'],
            "data_encerramento"=> date("d/m/Y",strtotime($row['data_encerramento'])) ,
            "requisicao_saida"=>$row['requisicao_saida'] ,
            "solicitacao_peca"=>$row['solicitacao_peca'],
            "num_nf"=>$row['num_nf'],
            "obs"=>$row['obs'],
            "nome_usuario"=>$row['nome_usuario'],
            "acoes"=> '<a  href="javascript:void();"  data-id="'.$row['idordem_servico'].'"  class="editbtn icon-acoes" ><img src="../assets/images/icones/update.png" alt=""></a> ' . $acoes
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
