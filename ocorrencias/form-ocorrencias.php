<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 4) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
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
    <title>Cadastro de Ocorrência</title>
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
                            <li class="nav-item"> <a class="nav-link" href="../controle-despesas/despesas.php"> Despesas </a> </li><li class="nav-item"> <a class="nav-link" href="../controle-despesas/complementos.php"> Complementos </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../controle-despesas/form-lancar-despesas.php"> Lançar Despesa </a> </li>
                            <li class="nav-item"> <a class="nav-link" href="../controle-despesas/gerar-planilha.php"> Planilha de Despesas </a> </li>
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
                        </ul> 
                    </nav> 
                </div>
                <div class="item">
                    <a onclick="menuCarregamentos()">
                        <img src="../assets/images/menu/carregamentos.png" alt="">
                    </a>
                    <nav id="submenuCarregamentos">
                        <ul class="nav flex-column">
                            <li class="nav-item"> <a href="../carregamentos/carregamentos.php" class="nav-link"> Carregamentos </a> </li>
                            <li class="nav-item"> <a href="../carregamentos/form-carregamento.php" class="nav-link"> Novo Carregamento </a> </li>
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
                    <img src="../assets/images/icones/ocorrencia.png" alt="">
                </div>
                <div class="title">
                    <h2>Lançar Ocorrência</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="add-ocorrencia.php" method="post" enctype="multipart/form-data">
                    <div class="form-row">
                        <div class="form-group col-md-8 espaco">
                            <label for="motorista"> Motorista </label>
                            <select name="motorista" id="motorista" required class="form-control">
                                <option value=""></option>
                                <?php
                                    $motoristas = $db->query("SELECT cod_interno_motorista, nome_motorista FROM motoristas ORDER BY nome_motorista ASC");
                                    if($motoristas->rowCount()>0){
                                        $dados = $motoristas->fetchAll();
                                        foreach($dados as $dado):
                                ?>
                                    <option value="<?=$dado['cod_interno_motorista']?>"><?=$dado['nome_motorista']?></option>
                                <?php            
                                        endforeach;
                                    }

                                ?>
                            </select>
                        </div>
                        <div class="form-group col-md-4 espaco">
                            <label for="placa">Veículo</label>
                            <select name="placa" id="placa" class="form-control">
                                <option value=""></option>
                                <?php
                                    $placas = $db->query("SELECT placa_veiculo FROM veiculos ORDER BY placa_veiculo ASC");
                                    if($placas->rowCount()>0){
                                        $dados = $placas->fetchAll();
                                        foreach($dados as $dado):
                                ?>
                                    <option value="<?=$dado['placa_veiculo']?>"><?=$dado['placa_veiculo']?></option>
                                <?php            
                                        endforeach;
                                    }

                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2 espaco">
                            <label for="carregamento">Carregamento</label>
                            <input type="text" name="carregamento" id="carregamento" class="form-control">
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="data"> Data da Ocorrência </label>
                            <input type="date" required name="data" class="form-control" id="data">
                        </div>
                        <div class="form-group col-md-4 espaco">
                            <label for="tipo"> Tipo de Ocorrência </label>
                            <select name="tipo" required id="tipo" class="form-control">
                                <option value=""></option>
                                <option value="Má Condução">Má Condução</option>
                                <option value="Mau Comportamento">Mau Comportamento</option>
                            </select>
                        </div>
                        <div class="input-group mb-3 form-grupo col-md-4 centro-file">
                            <div class="custom-file">
                                <input type="file" name="anexoOcorrencia[]" multiple="multiple" class="custom-file-input" id="anexoOcorrencia" >
                                <label for="anexoOcorrencia" class="custom-file-label">Anexos da Ocorrência</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-2 espaco">
                            <label for="advertencia">Houve Advertência</label>
                            <select name="advertencia" required id="advertencia" class="form-control">
                                <option value=""></option>
                                <option value=1>SIM</option>
                                <option value=0>NÃO</option>
                            </select>
                        </div>
                        <div class="input-group mb-3 form-grupo col-md-4 centro-file">
                            <div class="custom-file">
                                <input type="file" name="anexoAdvertencia[]" multiple="multiple" class="custom-file-input" id="anexoAdvertencia" >
                                <label for="anexoAdvertencia" class="custom-file-label">Anexos da Advertência</label>
                            </div>
                        </div>
                        <div class="form-group col-md-2 espaco">
                            <label for="laudo">Laudos</label>
                            <select name="laudo" required id="laudo" class="form-control">
                                <option value=""></option>
                                <option value=1>SIM</option>
                                <option value=0>NÃO</option>
                            </select>
                        </div>
                        <div class="input-group mb-3 form-grupo col-md-4 centro-file">
                            <div class="custom-file">
                                <input type="file" name="anexoLaudo[]" multiple="multiple" class="custom-file-input" id="anexoLaudo" >
                                <label for="anexoLaudo" class="custom-file-label">Anexos Laudos</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 espaco">
                            <label for="descricaoProblema">Descrição do Problema</label>
                            <textarea required name="descricaoProblema" id="descricaoProblema" class="form-control" rows="5"></textarea>
                        </div> 
                        <div class="form-group col-md-6 espaco">
                            <label for="descricaoCusto">Descrição dos Custos</label>
                            <textarea required name="descricaoCusto" id="descricaoCusto" class="form-control" rows="5"></textarea>
                        </div> 
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6 espaco">
                            <label for="vlTotal">Valor Total Custos</label>
                            <input type="text" required name="vlTotal" id="vlTotal" class="form-control">
                        </div>
                        <div class="form-group col-md-6 espaco">
                            <label for="situacao">Situação Resolvida</label>
                            <select name="situacao" required id="situacao" class="form-control">
                                <option value=""></option>
                                <option value="SIM">SIM</option>
                                <option value="NÃo">NÃO</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary"> Lançar </button>
                </form>
            </div>
        </div>
    </div>


    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <script src="../assets/js/jquery.mask.js"></script>
    <script>
        jQuery(function($){
            $("#vlTotal").mask('###0,00', {reverse: true});
        })
    </script>
    <script>
        $(document).ready(function() {
            $('#motorista').select2();
        });
        $(document).ready(function() {
            $('#placa').select2();
        });
    </script>
</body>

</html>