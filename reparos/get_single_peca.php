<?php include('../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM peca_reparo WHERE id_peca_reparo='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
