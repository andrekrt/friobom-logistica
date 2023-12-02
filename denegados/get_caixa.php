<?php 
include('../conexao.php');

$id = $_POST['id'];
$sql=$db->query("SELECT * FROM caixas WHERE idcaixas = '$id'");
$dados = $sql->fetch(PDO::FETCH_ASSOC);

echo json_encode($dados);
?>
