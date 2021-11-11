<?php include('../../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM sucos LEFT JOIN pneus ON sucos.pneus_idpneus = pneus.idpneus LEFT JOIN usuarios ON sucos.usuario = usuarios.idusuarios WHERE idsucos='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
