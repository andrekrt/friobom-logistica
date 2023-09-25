<?php include('../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM entrada_estoque LEFT JOIN peca_reparo ON entrada_estoque.peca_idpeca = peca_reparo.id_peca_reparo LEFT JOIN usuarios ON entrada_estoque.id_usuario = usuarios.idusuarios LEFT JOIN fornecedores ON entrada_estoque.fornecedor = fornecedores.id WHERE identrada_estoque='$id' LIMIT 1";
$query = $db->query($sql);
$row = $query->fetch(PDO::FETCH_ASSOC);
echo json_encode($row);
?>
