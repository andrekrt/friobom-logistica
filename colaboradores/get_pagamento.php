<?php include('../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM folha_pagamento LEFT JOIN usuarios ON folha_pagamento.usuario = usuarios.idusuarios WHERE idpagamento='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
