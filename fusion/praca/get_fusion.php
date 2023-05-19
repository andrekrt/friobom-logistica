<?php 
include('../../conexao.php');

$id = $_POST['id'];
$sql=$db->query("SELECT * FROM fusion_praca WHERE idfusion_praca = '$id'");
$dados = $sql->fetch(PDO::FETCH_ASSOC);

echo json_encode($dados);
?>
