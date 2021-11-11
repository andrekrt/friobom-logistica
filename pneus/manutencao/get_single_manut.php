<?php include('../../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM manutencao_pneu LEFT JOIN pneus ON manutencao_pneu.pneus_idpneus = pneus.idpneus  WHERE idmanutencao_pneu='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
