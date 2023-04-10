<?php 
include('../conexao.php');
require_once('../conexao.php');

$id = $_POST['id'];
$sql=$db->query("SELECT * FROM supervisores WHERE idsupervisor = '$id'");
$dados = $sql->fetch(PDO::FETCH_ASSOC);

echo json_encode($dados);
?>
