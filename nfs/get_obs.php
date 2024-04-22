<?php 
include('../conexao.php');

$id = $_POST['id'];
$sql = $db->query("SELECT * FROM nfs_xml WHERE idnf='$id' LIMIT 1");
$row = $sql->fetch(PDO::FETCH_ASSOC);

$dados=[
    'obs'=>$row['obs'],
    'idnf'=>$row['idnf']
];

echo json_encode($dados);
?>
