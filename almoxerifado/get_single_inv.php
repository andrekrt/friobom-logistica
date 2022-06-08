<?php include('../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM inventario_almoxarifado LEFT JOIN peca_estoque ON inventario_almoxarifado.peca = peca_estoque.idpeca LEFT JOIN usuarios ON inventario_almoxarifado.usuario = usuarios.idusuarios WHERE idinventario='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
