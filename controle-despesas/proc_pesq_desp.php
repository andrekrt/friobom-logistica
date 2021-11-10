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
    $data[] = array(
            "iddespesas"=>$row['iddespesas'],
            "num_carregemento"=>$row['num_carregemento'],
            "placa_veiculo"=>$row['placa_veiculo'],
            "nome_motorista"=> $row['nome_motorista'],
            "nome_rota"=>$row['nome_rota'],
            "data_carregamento"=>date("d/m/Y H:i", strtotime($row['data_carregamento'])),
            "acoes"=>  '<a class=" icon-acoes" target="_blank" href="gerar-pdf.php?id='.$row['iddespesas'].'"> <img src="../assets/images/icones/print.png" alt=""> </a>
            <?php if($tipoUsuario==99):?>
                <a class="icon-acoes" href="excluir.php?id='.$row['iddespesas'].'"><img src="../assets/images/icones/delete.png" alt=""></a>
            <?php endif;?>
            <a class=" icon-acoes" href="form-atualiza.php?id='.$row['iddespesas'].'"><img src="../assets/images/icones/update.png" alt=""></a>'
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
