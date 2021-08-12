<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 3 || $_SESSION['tipoUsuario'] == 99) {

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
        <div class="menu-lateral" id="menu-lateral">
            <div class="logo">
                <img src="../assets/images/logo.png" alt="">
            </div>
            <div class="opcoes">
                <div class="item">
                    <a href="../index.php">
                        <img src="../assets/images/menu/inicio.png" alt="">
                    </a>
                </div>
                <div class="item">
                    <a class="" onclick="menuVeiculo()">
                        <img src="../assets/images/menu/veiculos.png" alt="">
                    </a>
                    <nav id="submenu">
                        <ul class="nav flex-column">
                            <li class="nav-item"> <a class="nav-link" href="../veiculos/veiculos.php"> Veículos </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../veiculos/form-veiculos.php"> Cadastrar Veículo </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../veiculos/revisao.php"> Revisões </a> </li>
                            <li class="nav-item"> <a href="../veiculos/relatorio.php" class="na-link">Despesas por Veículo</a> </li>
                            <li class="nav-item"> <a href="../veiculos/gastos.php" class="na-link">Relatório</a> </li>
                        </ul>
                    </nav>
                </div>
                <div class="item">
                    <a onclick="menuRota()">
                        <img src="../assets/images/menu/rotas.png" alt="">
                    </a>
                    <nav id="submenuRota">
                        <ul class="nav flex-column">
                            <li class="nav-item"> <a class="nav-link" href="../rotas/rotas.php"> Rotas </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../rotas/form-rota.php"> Cadastrar Rota </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../rotas/relatorio.php"> Relatório</a> </li>
                        </ul>
                    </nav>
                </div>
                <div class="item">
                    <a onclick="menuMotorista()">
                        <img src="../assets/images/menu/motoristas.png" alt="">
                    </a>
                    <nav id="submenuMotorista">
                        <ul class="nav flex-column">
                            <li class="nav-item"> <a class="nav-link" href="../motoristas/motoristas.php"> Motoristas </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../motoristas/form-motorista.php"> Cadastrar Motorista </a> </li>
                            <li class="nav-item"> <a href="../motoristas/dados.php" class="nav-link"> Relatório</a> </li>
                        </ul>
                    </nav>
                </div>
                <div class="item">
                    <a onclick="menuOcorrencias()">
                        <img src="../assets/images/menu/ocorrencias.png" alt="">
                    </a>
                    <nav id="submenuOcorrencias">
                        <ul class="nav flex-column">
                            <li class="nav-item"> <a class="nav-link" href="../ocorrencias/form-ocorrencias.php"> Registrar Nova Ocorrência </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../ocorrencias/ocorrencias.php"> Listar Ocorrências </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../ocorrencias/relatorio.php"> Ocorrências por Motorista</a> </li>

                        </ul>
                    </nav>
                </div>
                <div class="item">
                    <a onclick="menuDespesas()">
                        <img src="../assets/images/menu/despesas.png" alt="">
                    </a>
                    <nav id="submenuDespesa">
                        <ul class="nav flex-column">
                            <li class="nav-item"> <a class="nav-link" href="../controle-despesas/despesas.php"> Despesas </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../controle-despesas/complementos.php"> Complementos </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../controle-despesas/form-lancar-despesas.php"> Lançar Despesa </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../controle-despesas/gerar-planilha.php"> Planilha de Despesas </a> </li>
                            <li class="nav-item"> <a onclick="menuEntregas()"> Entregas Capital </a>
                                <nav id="submenuCapital">
                                    <ul class="nav flex-column">
                                        <li class="nav-item"> <a href="../controle-despesas/entregas-capital/form-entregas.php"> Registrar Entregas </a> </li>
                                        <li class="nav-item"> <a href="../controle-despesas/entregas-capital/entregas.php">  Entregas </a> </li>
                                    </ul> 
                                </nav>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="item">
                    <a onclick="menuCheck()">
                        <img src="../assets/images/menu/check-list.png" alt="">
                    </a>
                    <nav id="submenuCheck">
                        <ul class="nav flex-column">
                            <li class="nav-item"> <a class="nav-link" href="../check-list/check-list.php"> Check-Lists </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../check-list/form-check.php"> Fazer Check-List </a> </li>
                        </ul>
                    </nav>
                </div>
                <div class="item">
                    <a onclick="menuReparos()">
                        <img src="../assets/images/menu/reparos.png" alt="">
                    </a>
                    <nav id="submenuReparos">
                        <ul class="nav flex-column">
                            <li class="nav-item"> <a class="nav-link" href="../reparos/solicitacoes.php"> Solicitações </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../reparos/form-solicitacao.php"> Nova Solicitação </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../reparos/relatorio.php"> Valores Gastos</a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../reparos/local-reparo.php">Local de Reparo</a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../reparos/pecas.php">Peças/Serviços</a> </li>
                        </ul>
                    </nav>
                </div>
                <div class="item">
                    <a onclick="menuAlmoxerifado()">
                        <img src="../assets/images/menu/almoxerifado.png" alt="">
                    </a>
                    <nav id="submenuAlmoxerifado">
                        <ul class="nav flex-column">
                            <li class="nav-item"> <a href="../almoxerifado/pecas.php" class="nav-link"> Estoque </a> </li>
                            <li class="nav-item"> <a href="../almoxerifado/entradas.php" class="nav-link"> Entrada </a> </li>
                            <li class="nav-item"> <a href="../almoxerifado/saidas.php" class="nav-link"> Saída </a> </li>
                            <li class="nav-item"> <a href="../almoxerifado/ordem-servico.php" class="nav-link"> Ordem de Serviço </a> </li>
                            <li class="nav-item"> <a href="../fornecedores/fornecedores.php" class="nav-link"> Fornecedores </a> </li>
                        </ul>
                    </nav>
                </div>
                <div class="item">
                    <a href="../sair.php">
                        <img src="../assets/images/menu/sair.png" alt="">
                    </a>
                </div>
            </div>
        </div>
        <!-- finalizando menu lateral -->
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
                            <div class="form-grupo col-md-6 espaco">
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
                            <div class="form-group col-md-6 espaco ">
                                <label for="local">Local para Reparo</label>
                                <select name="localReparo" class="form-control" id="localReparo">
                                    <option value=""></option>
                                    <?php

                                    $sql = $db->query("SELECT * FROM local_reparo");
                                    $categorias = $sql->fetchAll();
                                    foreach ($categorias as $categoria):

                                    ?>
                                        <option value="<?=$categoria['nome_local'] ?>"><?=$categoria['nome_local'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-7 espaco">
                                <label for="descricao">Descrição do Problema</label>
                                <input type="text" required name="descricao" class="form-control" id="descricao">
                            </div>
                            <div class="input-group mb-3 form-grupo col-md-5 centro-file">
                                <div class="custom-file">
                                    <input type="file" name="imagem" class="custom-file-input" id="imagem">
                                    <label for="imagem" class="custom-file-label">Imagem relatando o problema</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-grupo col-md-5 espaco">
                                <label for="peca">Peça/Serviço</label>
                                <select name="peca[]" class="form-control" id="peca">
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
                                <label for="qtd">Quantidade</label>
                                <input type="text" required name="qtd[]" id="qtd" class="form-control">
                            </div>
                            <div class="form-grupo col-md-2 espaco">
                                <label for="vlUnit">Valor Unit.</label>
                                <input type="text" required name="vlUnit[]" id="vlUnit" class="form-control">
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

                $('#formulario').append('<div class="form-row"> <div class="form-grupo col-md-5 espaco"> <label for="peca">Peça/Serviço</label> <select name="peca[]" class="form-control" id="peca"> <option value=""></option> <?php $sql = $db->query("SELECT * FROM peca_reparo"); $pecas = $sql->fetchAll(); foreach ($pecas as $peca): ?> <option value="<?=$peca['id_peca_reparo'] ?>"><?=$peca['id_peca_reparo']." - ". $peca['descricao'] ?></option> <?php endforeach; ?> </select> </div> <div class="form-grupo col-md-1 espaco"> <label for="qtd">Quantidade</label> <input type="text" required name="qtd[]" id="qtd" class="form-control"> </div> <div class="form-grupo col-md-2 espaco"> <label for="vlUnit">Valor Unit.</label> <input type="text" required name="vlUnit[]" id="vlUnit" class="form-control"> </div> <div style="margin: auto; margin-left: 0;"> </div> </div>');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#veiculo').select2();
            $('#localReparo').select2();
            $('#peca').select2();
        });
    </script>
</body>

</html>