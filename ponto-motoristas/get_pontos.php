<?php 
include('../conexao.php');

$mdfe = $_POST['mdfe'];
$sql=$db->query("SELECT P.*, M.cargas, data_saida, data_retorno FROM `motoristas_ponto` P LEFT JOIN mdfes M ON M.num_mdfe=P.mdfe WHERE mdfe = '$mdfe'");
$dados = $sql->fetchAll(PDO::FETCH_ASSOC);


echo json_encode($dados);




?>
