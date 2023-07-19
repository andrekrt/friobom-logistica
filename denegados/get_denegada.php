<?php include('../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM denegadas LEFT JOIN usuarios ON denegadas.usuario = usuarios.idusuarios WHERE id_denegadas='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
