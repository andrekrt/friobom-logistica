<?php include('../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM ordem_servico LEFT JOIN usuarios ON ordem_servico.idusuario = usuarios.idusuarios WHERE idordem_servico='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
