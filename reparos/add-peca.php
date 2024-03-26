<?php

session_start();
require("../conexao.php");

$idModudulo = 9;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {

    $descricao = trim(filter_input(INPUT_POST, 'descricao'));
    $categoria = filter_input(INPUT_POST, 'categoria');
    $medida = filter_input(INPUT_POST, 'medida');
    $estoqueMinimo = trim(str_replace(",", ".", filter_input(INPUT_POST, 'estoqueMin')));
    $situacao = 'Solicitar';

    $db->beginTransaction();

    try{
        $verificaPeca = $db->prepare("SELECT * FROM peca_reparo WHERE descricao = :descricao");
        $verificaPeca->bindValue(':descricao', $descricao);
        $verificaPeca->execute();
        if($verificaPeca->rowCount()>0){
            $_SESSION['msg'] = 'Essa Peça já está cadastrada no Estoque!';
            $_SESSION['icon']='warning';
            header("Location: pecas.php");
            exit();
        }
        $inserir = $db->prepare("INSERT INTO peca_reparo (descricao, categoria, un_medida, estoque_minimo, situacao, usuario) VALUES (:descricao, :categoria, :medida, :estoque, :situacao, :usuario)" );
        $inserir->bindValue(':descricao', $descricao);
        $inserir->bindValue(':categoria', $categoria);
        $inserir->bindValue(':medida', $medida);
        $inserir->bindValue(':estoque', $estoqueMinimo);
        $inserir->bindValue(':situacao', $situacao);
        $inserir->bindValue(':usuario', $idUsuario);
        $inserir->execute();

        $db->commit();

        $_SESSION['msg'] = 'Peça Cadastrada com Sucesso';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Cadastrar Peça';
        $_SESSION['icon']='error';
    }    
   
}else{

    $_SESSION['msg'] = 'Acesso Não Permitido';
    $_SESSION['icon']='error';

}
header("Location: pecas.php");
exit();
?>