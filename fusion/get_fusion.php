<?php 
include('../conexao.php');

$id = $_POST['id'];
$sql=$db->query("SELECT * FROM fusion WHERE idfusion = '$id'");
$dados = $sql->fetch(PDO::FETCH_ASSOC);

echo json_encode($dados);
?>
