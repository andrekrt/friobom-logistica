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

    $idUsuario = $_SESSION['idUsuario'];
    $nomeUsuario = $_SESSION['nomeUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];
} else {
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Serviço</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="apple-touch-icon" sizes="180x180" href="../assets/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="../assets/favicon/site.webmanifest">
    <link rel="mask-icon" href="../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    <div class="container-fluid corpo">
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/reparos.png" alt="">
                </div>
                <div class="title">
                    <h2>Nova Solicitação</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="add-solicitao-peca.php" class="despesas" enctype="multipart/form-data" method="post" id="">
                    <div id="formulario">
                         
                        <div class="form-row">
                            <div class="form-grupo col-md-3 espaco">
                                <label for="veiculo">Placa do Veículo</label>
                                <select name="veiculo" required id="veiculo" class="form-control">
                                    <option></option>
                                    <option value="Estoque">Estoque</option>
                                    <option value="Serviços">Serviços</option>
                                    <option value="Oficina">Oficina</option>
                                    <?php

                                    $sql = $db->query("SELECT placa_veiculo FROM veiculos ORDER BY placa_veiculo ASC");
                                    if ($sql->rowCount() > 0) {
                                        $dados = $sql->fetchAll();
                                        foreach ($dados as $dado) {
                                            echo "<option value='$dado[placa_veiculo]'>" . $dado['placa_veiculo'] . "</option>";
                                        }
                                    }

                                    ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="motorista">Motorista</label>
                                <select name="motorista" id="motorista" class="form-control">
                                    <option value=""></option>
                                    <?php

                                    $sql = $db->query("SELECT * FROM motoristas");
                                    $motoristas = $sql->fetchAll();
                                    foreach ($motoristas as $motorista):

                                    ?>
                                        <option value="<?=$motorista['nome_motorista'] ?>"><?=$motorista['nome_motorista'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="rota">Rota</label>
                                <select name="rota" id="rota" class="form-control">
                                    <option value=""></option>
                                    <?php

                                    $sql = $db->query("SELECT * FROM rotas");
                                    $rotas = $sql->fetchAll();
                                    foreach ($rotas as $rota):

                                    ?>
                                        <option value="<?=$rota['nome_rota'] ?>"><?=$rota['nome_rota'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-grupo col-md-3 espaco">
                                <label for="fornecedor">Fornecedor</label>
                                <select name="fornecedor" id="fornecedor" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $sql = $db->query("SELECT * FROM fornecedores");
                                    $pecas = $sql->fetchAll();
                                    foreach ($pecas as $peca):
                                    ?>
                                        <option value="<?=$peca['id'] ?>"><?=$peca['id']." - ". $peca['nome_fantasia'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-8 espaco">
                                <label for="descricao">Descrição do Problema</label>
                                <input type="text" required name="descricao" class="form-control" id="descricao">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="frete">Valor Frete</label>
                                <input type="text" required name="frete" class="form-control" id="frete">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="nf">Nº NF</label>
                                <input type="text" required name="nf" class="form-control" id="nf">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-grupo col-md-3 espaco">
                                <label for="peca">Peça/Serviço</label>
                                <select name="peca[]" required class="form-control" id="peca">
                                    <option value=""></option>
                                    <?php
                                    $sql = $db->query("SELECT * FROM peca_reparo");
                                    $pecas = $sql->fetchAll();
                                    foreach ($pecas as $peca):
                                    ?>
                                        <option value="<?=$peca['id_peca_reparo'] ?>"><?=$peca['id_peca_reparo']." - ". $peca['descricao'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-grupo col-md-1 espaco">
                                <label for="qtd">Qtd.</label>
                                <input type="text" required name="qtd[]" id="qtd" class="form-control">
                            </div>
                            <div class="form-grupo col-md-1 espaco">
                                <label for="vlUnit">Valor Unit.</label>
                                <input type="text" required name="vlUnit[]" id="vlUnit" class="form-control">
                            </div>
                            <div class="form-grupo col-md-1 espaco">
                                <label for="desconto">Desconto</label>
                                <input type="text" required name="desconto[]" id="desconto" class="form-control">
                            </div>
                            
                            <div class="mb-3 form-grupo col-md-2 espaco">
                                <label for="imagem" class="form-label">Imagem do problema</label>
                                <input type="file" name="imagem[]" class="form-control" id="imagem" multiple>    
                            </div>
                            <div style="margin: auto; margin-left: 0;">
                                <button type="button" class="btn btn-danger" id="add-peca">Adicionar Peça</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="entradas" class="btn btn-primary"> Enviar Solicitação</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <script>
        $(document).ready(function(){

            var cont = 1;
            $('#add-peca').click(function(){
                cont++;

                $('#formulario').append('<div class="form-row"> <div class="form-grupo col-md-3 espaco"> <label for="peca">Peça/Serviço</label> <select name="peca[]" required class="form-control" id="peca"> <option value=""></option> <?php $sql = $db->query("SELECT * FROM peca_reparo"); $pecas = $sql->fetchAll(); foreach ($pecas as $peca):?> <option value="<?=$peca['id_peca_reparo'] ?>"><?=$peca['id_peca_reparo']." - ". $peca['descricao'] ?></option> <?php endforeach; ?> </select> </div>    <div class="form-grupo col-md-1 espaco"> <label for="qtd">Qtd.</label> <input type="text" required name="qtd[]" id="qtd" class="form-control"> </div> <div class="form-grupo col-md-1 espaco"> <label for="vlUnit">Valor Unit.</label> <input type="text" required name="vlUnit[]" id="vlUnit" class="form-control"> </div> <div class="form-grupo col-md-1 espaco">  <label for="desconto">Desconto</label> <input type="text" required name="desconto[]" id="desconto" class="form-control"> </div>  <div class="mb-3 form-grupo col-md-2 espaco">     <label for="imagem" class="form-label">Imagem do problema</label> <input type="file" name="imagem[]" class="form-control" id="imagem" multiple> </div>  </div>');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#veiculo').select2();
            $('#localReparo').select2();
            $('#peca').select2();
            $('#motorista').select2();
            $('#rota').select2();
            $('#fornecedor').select2();
        });
    </script>
    
</body>

</html>