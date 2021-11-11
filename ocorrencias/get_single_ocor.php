<?php include('../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM ocorrencias LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista LEFT JOIN usuarios ON ocorrencias.usuario_lancou = usuarios.idusuarios WHERE idocorrencia='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
