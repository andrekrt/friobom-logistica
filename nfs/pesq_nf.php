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

$sql = $db->query("SELECT * FROM tags_xml");
$tagsArray = $sql->fetchAll();

$tags = [];
foreach($tagsArray as $tag){
    $tags[$tag['idtags']]=$tag;
}

## Search 
$searchQuery = " ";
if($searchValue != ''){
	$searchQuery = " AND (num_nota LIKE :num_nota OR  nome_emit LIKE :nome_emit OR  cnpj LIKE :cnpj OR cidade LIKE :cidade OR uf LIKE :uf OR fone LIKE :fone OR  cfop LIKE :cfop OR situacao_manifest LIKE :situacao_manifest ) ";
    $searchArray = array( 
        'num_nota'=>"%$searchValue%", 
        'nome_emit'=>"%$searchValue%",
        'cnpj'=>"%$searchValue%",
        'cidade'=>"%$searchValue%",
        'uf'=>"%$searchValue%",
        'fone'=>"%$searchValue%",
        'cfop'=>"%$searchValue%",
        'situacao_manifest'=>"%$searchValue%",
    );
}

## Total number of records without filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM nfs_xml ");
$stmt->execute();
$records = $stmt->fetch();
$totalRecords = $records['allcount'];

## Total number of records with filtering
$stmt = $db->prepare("SELECT COUNT(*) AS allcount FROM nfs_xml WHERE 1 ".$searchQuery);
$stmt->execute($searchArray);
$records = $stmt->fetch();
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$stmt = $db->prepare("SELECT * FROM nfs_xml WHERE 1 ".$searchQuery." ORDER BY ".$columnName." ".$columnSortOrder." LIMIT :limit,:offset");

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
    $tagsHtml = '';
    foreach($tags as $tag){
        if($row[$tag['nome_coluna']]==$tag['valor']){
            $tagsHtml .= ' <span class="tags" style="background-color:'.$tag['cor'].'">'.$tag['legenda'].'</span> ';
        }
    }

    $data[] = array(
            "tags"=>$tagsHtml,
            "idnf"=>$row['idnf'],
            "num_nota"=>$row['num_nota'],
            "data_emissao"=>date('d/m/Y', strtotime($row['data_emissao'])) ,
            "data_entrada"=>!empty($row['data_entrada'])?date('d/m/Y', strtotime($row['data_entrada'])):""  ,
            "nome_emit"=>$row['nome_emit'],
            "cnpj"=> $row['cnpj'],
            "cidade"=>$row['cidade'],
            "uf"=>$row['uf'],
            "fone"=>$row['fone'],
            "cfop"=>$row['cfop'],
            "valor_total"=>"R$ ".number_format($row['valor_total'],2,",",".") ,
            "situacao_manifest"=>$row['situacao_manifest'],
            "fornecedor"=>$row['fornecedor'],
            "carregamento"=>$row['carregamento'],
            "status_carga"=>$row['status_carga'],
            "divergencia"=>$row['divergencia'],
            "obs"=>$row['obs'],
            "situacao"=>$row['situacao']
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
