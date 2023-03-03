<?php 
include('../conexao.php');
require_once('../conexao-oracle.php');

$id = $_POST['id'];
$sql=$db->query("SELECT cod_cliente FROM localizacao WHERE id = '$id'");
$cliente = $sql->fetch(PDO::FETCH_ASSOC);
$cliente = $cliente['cod_cliente'];

$sql = "SELECT ENDERENT, BAIRROENT, MUNICENT, CODCLI FROM FRIOBOM.pcclient WHERE CODCLI ='$cliente' ";
$query = $dbora->query($sql);

while($row = $query->fetch(PDO::FETCH_ASSOC)){
    $linhas=mb_convert_encoding($row, 'ISO-8859-1', 'UTF-8');
}

echo json_encode($linhas);
?>
