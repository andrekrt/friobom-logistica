<?php include('../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM servicos_almoxarifado LEFT JOIN usuarios ON servicos_almoxarifado.usuario = usuarios.idusuarios WHERE idservicos='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
