<?php include('../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM peca_estoque LEFT JOIN usuarios ON peca_estoque.id_usuario = usuarios.idusuarios WHERE idpeca='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
