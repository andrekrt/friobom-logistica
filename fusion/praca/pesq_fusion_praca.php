<?php
include '../../conexao.php';

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
	$searchQuery = " AND (carga LIKE :carga OR placa_veiculo LIKE :placa_veiculo OR nome_auxiliar LIKE :nome_auxiliar OR nome_rota LIKE :nome_rota ) ";
    $searchArray = array( 
        'carga'=>"%$searchValue%",
        'placa_veiculo'=>"%$searchValue%",
        'nome_auxiliar'=>"%$searchValue%",
        'nome_rota'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM fusion_praca LEFT JOIN veiculos ON fusion_praca.veiculo = veiculos.cod_interno_veiculo LEFT JOIN auxiliares_rota ON fusion_praca.ajudante = auxiliares_rota.idauxiliares LEFT JOIN rotas ON fusion_praca.rota = rotas.cod_rota LEFT JOIN usuarios ON fusion_praca.usuario = usuarios.idusuarios WHERE situacao = 'Pendente'");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM fusion_praca LEFT JOIN veiculos ON fusion_praca.veiculo = veiculos.cod_interno_veiculo LEFT JOIN auxiliares_rota ON fusion_praca.ajudante = auxiliares_rota.idauxiliares LEFT JOIN rotas ON fusion_praca.rota = rotas.cod_rota LEFT JOIN usuarios ON fusion_praca.usuario = usuarios.idusuarios WHERE 1 AND situacao = 'Pendente'".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM fusion_praca LEFT JOIN veiculos ON fusion_praca.veiculo = veiculos.cod_interno_veiculo LEFT JOIN auxiliares_rota ON fusion_praca.ajudante = auxiliares_rota.idauxiliares LEFT JOIN rotas ON fusion_praca.rota = rotas.cod_rota LEFT JOIN usuarios ON fusion_praca.usuario = usuarios.idusuarios WHERE 1 AND situacao ='Pendente' ".$searchQuery. "  ORDER BY idfusion_praca DESC LIMIT :limit,:offset");

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
            "data_saida"=>date("d/m/Y ", strtotime($row['data_saida'])) ,
            "data_finalizacao"=>date("d/m/Y ", strtotime($row['data_finalizacao'])),
            "data_chegada"=>date("d/m/Y ", strtotime($row['data_chegada'])),
            "carga"=>$row['carga'],
            "placa_veiculo"=>$row['placa_veiculo'],
            "nome_auxiliar"=>$row['nome_auxiliar'],
            "nome_rota"=>$row['nome_rota'],
            "num_entregas"=>$row['num_entregas'],
            "entregas_ok"=>$row['entregas_ok'],
            "num_devolucao"=>$row['num_devolucao'],
            "entregas_liq"=>$row['entregas_liq'],
            "num_uso_incorreto"=>$row['num_uso_incorreto'],
            "devolucao_sem_aviso"=>$row['devolucao_sem_aviso'],
            "perc_devolucao"=>($row['perc_devolucao']*100)."%",
            "perc_entregas"=>($row['perc_entregas']*100)."%",
            "perc_contas"=>($row['perc_contas']*100)."%",
            "perc_rota"=>($row['perc_rota']*100)."%",
            "premio_possivel"=>"R$".number_format($row['premio_possivel'],2,",",".") ,
            "premio_real"=>"R$".number_format($row['premio_real'],2,",",".") ,
            "perc_premio"=>number_format($row['perc_premio']*100,2,",",".")."%" ,
            "nome_usuario"=>$row['nome_usuario'],
            "situacao"=>$row['situacao'],
            "acoes"=> '<a href="javascript:void();" data-id="'.$row['idfusion_praca'].'"  class="btn btn-info btn-sm editbtn" >Editar</a>  <a href="excluir-fusion.php?id='.$row['idfusion_praca'].' " data-id="'.$row['idfusion_praca'].'"  class="btn btn-danger btn-sm deleteBtn" onclick=\'return confirm("Deseja Excluir?");\'>Deletar</a>'
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
