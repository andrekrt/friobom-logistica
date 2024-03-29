<?php
session_start();
include '../conexao.php';

$idUsuario = $_SESSION['idUsuario'];
$tipoUsuario = $_SESSION['tipoUsuario'];
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
	$searchQuery = " AND (placa_veiculo LIKE :placa_veiculo) OR (num_carregemento LIKE :num_carregemento) OR (nome_rota LIKE :nome_rota) OR (nome_motorista LIKE :nome_motorista) ";
    $searchArray = array( 
        'placa_veiculo'=>"%$searchValue%",
        'num_carregemento'=>"%$searchValue%",
        'nome_rota'=>"%$searchValue%",
        'nome_motorista'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM viagem");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM viagem WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM viagem WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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

    //calculo de diferença de datas
    $dataFinial = new DateTime(date('Y-m-d H:i'));
    $dataInicial = new DateTime($row['data_registro']);
    $diferencaDias = $dataFinial->diff($dataInicial);
    $dias= number_format($diferencaDias->days+($diferencaDias->h/24),2);

    $assinar="";
    $imprimir = "";
    $excluir="";
    $editar = "";
    if(is_dir('uploads/'.$row['iddespesas'])){
        $fotos='<a target="_blank" href="http://192.168.10.32/logistica/controle-despesas/uploads/'.$row['iddespesas'].'">Fotos</a>';
    }else{
        $fotos = "Sem Foto";
    }
    if(($idUsuario==20 || $idUsuario==1) && $row['situacao']=="Não Confirmado"){
        $assinar = ' <a class=" icon-acoes"  onclick=\'confirmaAssina(' . $row['iddespesas'] . ')\'> <img src="../assets/images/icones/confirma.png"> </a> ';
        $editar=' <a class=" icon-acoes" href="form-atualiza.php?id='.$row['iddespesas'].'"><img src="../assets/images/icones/update.png" alt=""></a> ';
    }
    if($row['situacao']=="Confirmado" || $row['situacao']=="Confirmado com Alteração" || $row['situacao']=="Confirmado e 2 Alterações"){
        $imprimir = '<a class=" icon-acoes" target="_blank" href="gerar-pdf02.php?id='.$row['iddespesas'].'"> <img src="../assets/images/icones/print.png" alt=""> </a>';
    }
    if($tipoUsuario==99 && $row['situacao']=="Não Confirmado"){
        $excluir = '<a class="icon-acoes"  onclick=\'confirmaDelete(' . $row['iddespesas'] . ')\'><img src="../assets/images/icones/delete.png" alt=""></a>';
    }
    if(($idUsuario==45 || $idUsuario==1) && ($row['situacao']=="Confirmado" || $row['situacao']=='Confirmado com Alteração') && $dias<=1.5){
        $editar=' <a class=" icon-acoes" href="form-atualiza.php?id='.$row['iddespesas'].'"><img src="../assets/images/icones/update.png" alt=""></a> ';
    }   
    $data[] = array(
        "iddespesas"=>$row['iddespesas'],
        "num_carregemento"=>$row['num_carregemento'],
        "placa_veiculo"=>$row['placa_veiculo'],
        "peso_carga"=>number_format($row['peso_carga'],2,",",".")."Kg" ,
       
        "nome_motorista"=> $row['nome_motorista'],
        "nome_rota"=>$row['nome_rota'],
        "mediatk"=>number_format($row['media_comtk'],2,",",".")."Km/L" ,
        "mediastk"=>number_format($row['mediaSemTk'],2,",",".")."Km/L" ,
        "data_carregamento"=>date("d/m/Y H:i", strtotime($row['data_carregamento'])),
        "data_saida"=>date("d/m/Y H:i", strtotime($row['data_saida'])),
        "data_chegada"=>date("d/m/Y H:i", strtotime($row['data_chegada'])),
        "dias_em_rota"=>number_format($row['dias_em_rota'],2,",","."),
        "diarias_mot"=>number_format($row['dias_motorista'],2,",","."),
        "valor_mot"=>"R$ " .number_format($row['diarias_motoristas'],2,",","."),
        "diarias_ajud"=>number_format($row['dias_ajudante'],2,",","."),
        "valor_ajud"=>"R$ ".number_format($row['diarias_ajudante'],2,",","."),
        "diarias_chapa"=>number_format($row['dias_chapa'],2,",","."),
        "valor_chapa"=>"R$ ".number_format($row['diarias_chapa'],2,",","."),
        "km_rodado"=>$row['km_rodado'],
        "litros"=>number_format($row['litros'],2,",","."),
        "outros_gastos_ajudante"=>number_format($row['outros_gastos_ajudante'],2,",","."),
        "tomada"=>number_format($row['tomada'],2,",","."),
        "descarga"=>number_format($row['descarga'], 2,",","."),
        "outros_servicos"=>number_format($row['outros_servicos'],2,",","."),
        "obs"=>$row['obs_carga'],
        
        "status"=>$row['situacao'],
        "acoes"=>$assinar. $imprimir. $editar . $excluir 
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
