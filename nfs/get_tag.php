<?php 
include('../conexao.php');

$id = $_POST['id'];
$sql=$db->query("SELECT * FROM tags_xml WHERE idtags = '$id'");
$dados = $sql->fetch(PDO::FETCH_ASSOC);

echo json_encode($dados);
?>
