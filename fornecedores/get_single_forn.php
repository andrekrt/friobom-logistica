<?php include('../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM fornecedores WHERE id='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
