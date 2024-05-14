<?php

session_start();
require("../conexao.php");
include '../conexao-oracle.php';

$idModudulo = 20;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo, PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result > 0)) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $carregamento = filter_input(INPUT_GET, 'carregamento');

    $sql = $db->prepare("SELECT * FROM trocas WHERE carregamento =:carregamento");
    $sql->bindValue(':carregamento', $carregamento);
    $sql->execute();
    $dados = $sql->fetchAll(PDO::FETCH_ASSOC);
} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TROCAS</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
</head>

<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/confere.png" alt="">
                </div>
                <div class="title">
                    <h2>Conferência</h2>
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="confere.php" method="post" >
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="carregamento" class="col-form-label">Carregamento</label>
                            <input type="text" name="carregamento" class="form-control" readonly id="carregamento" value="<?= $dados[0]['carregamento'] ?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="rota" class="col-form-label">Rota</label>
                            <input type="text" readonly name="rota" class="form-control" id="rota" value="<?= $dados[0]['rota'] ?>">
                        </div>
                        <div class="form-group col-md-5">
                            <label for="motorista" class="col-form-label"> Motorista</label>
                            <input type="text" readonly name="motorista" class="form-control" id="motorista" value="<?= $dados[0]['motorista']  ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="veiculo" class="col-form-label">Veículo</label>
                            <input type="text" readonly name="veiculo" class="form-control" id="veiculo" value="<?= $dados[0]['veiculo']  ?>">
                        </div>
                    </div>
                    <?php foreach ($dados as $dado) : ?>
                        <input type="hidden" name="idtroca[]" value="<?=$dado['idtroca']?>">
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="codProd" class="col-form-label"> Cód Produto </label>
                                <input type="text" readonly name="codProd[]" class="form-control" id="codProd" value="<?= $dado['cod_produto'] ?>">
                            </div>
                            <div class="form-group col-md-5">
                                <label for="produto" class="col-form-label"> Descrição </label>
                                <input type="text" readonly name="produto[]" class="form-control" id="produto" value="<?= $dado['nome_produto'] ?>">
                            </div>
                            <div class="form-group col-md-1">
                                <label for="qtd" class="col-form-label"> Qtd </label>
                                <input type="text" readonly name="qtd[]" class="form-control" id="qtd" value="<?= $dado['qtd']  ?>">
                            </div>

                            <div class="form-group col-md-2">
                                <label for="vlTotal" class="col-form-label">Tudo OK? </label>
                                <div class="input-group-text pb-3">
                                    <input type="checkbox" class="align-middle checkbox" value="1"  data-id="<?=$dado['idtroca']?>" id="situacao" name="situacao[<?=$dado['idtroca']?>]">
                                </div>
                            </div>
                            <div class="form-group col-md-2">
                                <label for="falta" class="col-form-label">Qt Falta</label>
                                <input type="number" class="form-control falta" data-id="<?=$dado['idtroca']?>" id="falta" name="falta[<?=$dado['idtroca']?>]">
                            </div>
                        </div>
                    <?php endforeach; ?>
                        <div class="form-row">
                            <div class="form-group col-md-2">
                                <label for="ausencia" class="col-form-label">Motorista Ausente? </label>
                                <div class="input-group-text pb-3">
                                    <input type="checkbox" class="align-middle checkbox" id="ausencia" name="ausencia">
                                </div>
                            </div>
                        </div>
                    <button type="submit" name="" class="btn btn-success">Confirmar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <script>
        $(document).ready(function(){
            $('.checkbox').on("change",function(){
                let id = $(this).data('id');
             
                if($(this).is(":checked")){
                    $('.falta[data-id="' + id + '"]').prop('readonly', true);
                    $('.falta[data-id="' + id + '"]').val("");
                }else{
                    $('.falta[data-id="' + id + '"]').prop('readonly', false);
                }
            });

           $('.falta').on("change", function(){
                let idFalta = $(this).data('id');
                let qtFalta = $('.falta[data-id="' + idFalta + '"]').val();

                if(qtFalta>0){
                    $('.checkbox[data-id="' + idFalta + '"]').prop('disabled', true);
                }else{
                    $('.checkbox[data-id="' + idFalta + '"]').prop('disabled', false);
                }

           });
        });

    </script>
</body>

</html>