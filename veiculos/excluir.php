<?php 

session_start();
require("../conexao.php");

$idModudulo = 1;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $codVeiculo = filter_input(INPUT_GET, 'codVeiculo');

    $db->beginTransaction();

    try{

        $delete = $db->prepare("DELETE FROM veiculos WHERE cod_interno_veiculo = :codVeiculo ");
        $delete->bindValue(':codVeiculo', $codVeiculo);
        $delete->execute();

        $db->commit();

        $_SESSION['msg'] = 'Veículo Desativado com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){

        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Desativar Veículo';
        $_SESSION['icon']='error';

    }

}else{
    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';
}

header("Location: veiculos.php");
exit();

?>