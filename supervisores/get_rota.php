<?php 
include('../conexao.php');
require_once('../conexao.php');

$id = $_POST['id'];
$sql=$db->query("SELECT * FROM rotas_supervisores WHERE idrotas = '$id'");
$dados = $sql->fetch(PDO::FETCH_ASSOC);

echo json_encode($dados);
?>
