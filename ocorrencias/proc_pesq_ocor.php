<?php
include '../conexao.php';

session_start();
$tipoUsuario=$_SESSION['tipoUsuario'];

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
    $condicao = "AND ocorrencias.filial=$filial";
}

$searchArray = array();

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (placa LIKE :placa ) OR (num_carregamento LIKE :num_carregamento) OR (tipo_ocorrencia LIKE :tipo_ocorrencia) OR (nome_motorista LIKE :nome_motorista) ";
    $searchArray = array( 
        'nome_motorista'=>"%$searchValue%",
        'placa'=>"%$searchValue%",
        'num_carregamento'=>"%$searchValue%",
        'tipo_ocorrencia'=>"%$searchValue%"
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM ocorrencias WHERE 1 $condicao");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM ocorrencias LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista LEFT JOIN usuarios ON ocorrencias.usuario_lancou = usuarios.idusuarios WHERE 1 $condicao ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM ocorrencias LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista LEFT JOIN usuarios ON ocorrencias.usuario_lancou = usuarios.idusuarios WHERE 1 $condicao ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    if($tipoUsuario==4){
        $botao = '<a href="javascript:void();" data-id="'.$row['idocorrencia'].'"  class="btn btn-info btn-sm editbtn" >Visulizar</a>  <a  data-id="'.$row['idocorrencia'].'"  class="btn btn-danger btn-sm deleteBtn" onclick=\'confirmaDelete(' . $row['idocorrencia'] . ')\'>Deletar</a>';
    }else{
        $botao = null;
    }
    $data[] = array(
        "filial"=>$row['filial'],
        "idocorrencia"=>$row['idocorrencia'],
        "nome_motorista"=>$row['nome_motorista'],
        "data_ocorrencia"=>date("d/m/Y", strtotime($row['data_ocorrencia'])),
        "tipo_ocorrencia"=> $row['tipo_ocorrencia'],
        "placa"=>$row['placa'],
        "num_carregamento"=>$row['num_carregamento'],
        "ocorrencias"=> $row['img_ocorrencia']==""?"Sem Provas de Ocorrências":"<a href='uploads/$row[idocorrencia]/ocorrencias' target='_blank'>Anexo</a>" ,
        "advertencias"=> $row['img_advertencia']==""?"Sem Anexo":"<a href='uploads/$row[idocorrencia]/advertencias' target='_blank'>Anexo</a>" ,
        "laudos"=> $row['img_laudo']==""?"Sem Anexo":"<a href='uploads/$row[idocorrencia]/laudos' target='_blank'>Anexo</a>" ,
        "vl_total"=>"R$ ". str_replace(".",",",$row['vl_total_custos']),
        "situacao"=> $row['situacao'] ,
        "usuario"=>$row['nome_usuario'],
        "acoes"=> $botao
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
