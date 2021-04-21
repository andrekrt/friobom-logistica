<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==1 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    
}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Lançar Nova Despesa</title>
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
                        <a onclick="menuDespesas()">
                            <img src="../assets/images/menu/despesas.png" alt="">
                        </a>
                        <nav id="submenuDespesa">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="../controle-despesas/despesas.php"> Despesas </a> </li>
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
                        <img src="../assets/images/icones/despesas.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Lançar Nova Despesa</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <form action="add-despesas.php" class="despesas" method="post">
                        <div class="form-row"> 
                            <div class="form-group col-md-2 espaco">
                                <label for="codVeiculo">Código do Veículo</label>
                                <input type="text" required name="codVeiculo" class="form-control" id="codVeiculo">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="tipoVeiculo">Tipo do Veículo</label>
                                <input type="text" required name="tipoVeiculo" class="form-control" id="tipoVeiculo">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="placaVeiculo">Placa do Veículo</label>
                                <input type="text" required name="placaVeiculo" class="form-control" id="placaVeiculo">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="codMotorista">Código do Motorista</label>
                                <input type="text" required name="codMotorista" class="form-control" id="codMotorista">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="nomeMotorista">Nome do Motorista</label>
                                <input type="text" required name="motorista" class="form-control" id="nomeMotorista">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="nCarregamento">Nº Carregamento</label>
                                <input type="text" required name="nCarregamento" class="form-control" id="nCarregamento">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="dataCarregamento">Data do Carregamento</label>
                                <input type="DateTime-Local" required name="dataCarregamento" class="form-control" id="dataCarregamento">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="dataSaida">Data e hora de Saída</label>
                                <input type="DateTime-Local" required name="dataSaida" class="form-control" id="dataSaida">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="dataChegada">Data e Hora de Chegada</label>
                                <input type="DateTime-Local" required name="dataChegada" class="form-control" id="dataChegada">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="codRota">Código da Rota</label>
                                <input type="text" required name="codRota" class="form-control" id="codRota">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="rota">Rota</label>
                                <input type="text" required name="rota" class="form-control" id="rota">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="vlTransp">Valor Transportado (R$)</label>
                                <input type="text" required name="vlTransp" class="form-control" id="vlTransp">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="vlDev">Valor Devolvido (R$)</label>
                                <input type="text" required name="vlDev" class="form-control" id="vlDev">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="qtdEntrega">Qtde de Entregas</label>
                                <input type="text" required name="qtdEntrega" class="form-control" id="qtdEntrega">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="nCarga">Nº da Carga</label>
                                <input type="text" required name="nCarga" class="form-control" id="nCarga">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="pesoCarga">Peso da Carga</label>
                                <input type="text" required name="pesoCarga" class="form-control" id="pesoCarga">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="kmSaida">KM de Saída</label>
                                <input type="text" required name="kmSaida" class="form-control" id="kmSaida">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="hrTkSaida">HR TK Saída</label>
                                <input type="text" name="hrTkSaida" class="form-control" id="hrTkSaida">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="km1Abast">KM 1º Abastescimento Externo</label>
                                <input type="text" required name="km1Abast" class="form-control" id="km1Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="hrKm1Abast">HR TK 1º Abastescimento Externo</label>
                                <input type="text" name="hrKm1Abast" class="form-control" id="hrKm1Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="lt1Abast">Litros 1º Abastescimento Externo</label>
                                <input type="text" required name="lt1Abast" class="form-control" id="lt1Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="vl1Abast">Valor 1º Abastescimento Externo</label>
                                <input type="text" name="vl1Abast" class="form-control" id="vl1Abast">
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4 espaco">
                                <label for="local1Abast">Posto / Cidade 1º Abastecimento Externo</label>
                                <input type="text" name="local1Abast" class="form-control" id="local1Abast">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="km2Abast">KM 2º Abastescimento</label>
                                <input type="text" name="km2Abast" class="form-control" id="km2Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="hrKm2Abast">HR TK 2º Abastescimento Externo</label>
                                <input type="text" name="hrKm2Abast" class="form-control" id="hrKm2Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="lt2Abast">Litros 2º Abastescimento Externo</label>
                                <input type="text" name="lt2Abast" class="form-control" id="lt2Abast">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="vl2Abast">Valor 2º Abastescimento Externo</label>
                                <input type="text" name="vl2Abast" class="form-control" id="vl2Abast">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="local2Abast">Posto / Cidade 2º Abastecimento Externo</label>
                                <input type="text" name="local2Abast" class="form-control" id="local2Abast">
                            </div>
                            <div class="form-group col-md-5 espaco">
                                <label for="km3Abast">KM 3º Abastescimento Externo</label>
                                <input type="text" name="km3Abast" class="form-control" id="km3Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="hrKm3Abast">HR TK 3º Abastescimento Externo</label>
                                <input type="text" name="hrKm3Abast" class="form-control" id="hrKm3Abast">
                            </div>
                            <div class="form-group col-md-5 espaco">
                                <label for="lt3Abast">Litros 3º Abastescimento Externo</label>
                                <input type="text" name="lt3Abast" class="form-control" id="lt3Abast">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="vl3Abast">Valor 3º Abastescimento Externo</label>
                                <input type="text" name="vl3Abast" class="form-control" id="vl3Abast">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-4 espaco">
                                <label for="local3Abast">Posto / Cidade 3º Abastecimento Externo</label>
                                <input type="text" name="local3Abast" class="form-control" id="local3Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="km4Abast">KM Abastescimento Interno</label>
                                <input type="text" name="km4Abast" class="form-control" id="km4Abast">
                            </div>
                            <div class="form-group col-md-5 espaco">
                                <label for="hrKm4Abast">HR TK Abastescimento  Interno</label>
                                <input type="text" name="hrKm4Abast" class="form-control" id="hrKm4Abast">
                            </div>
                            
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-3 espaco">
                                <label for="lt4Abast">Litros Abastescimento  Interno</label>
                                <input type="text" name="lt4Abast" class="form-control" id="lt4Abast">
                            </div>
                            <div class="form-group col-md-3 espaco">
                                <label for="vl4Abast">Valor Abastescimento Interno</label>
                                <input type="text" name="vl4Abast" class="form-control" id="vl4Abast">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="local4Abast">Posto / Cidade Abastecimento Interno</label>
                                <input type="text" required name="local4Abast" class="form-control" id="local4Abast">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diariasMot">Valor Diária Motorista</label>
                                <input type="text" required name="diariasMot" class="form-control" id="diariasMot">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diasRota">Dias em Rota Motorista</label>
                                <input type="text" required name="diasRota" class="form-control" id="diasRota">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diariasAjud">Valor Diária Ajudante </label>
                                <input type="text" required name="diariasAjud" class="form-control" id="diariasAjud">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diasRotaAjud">Dias em Rota Ajudante</label>
                                <input type="text" required name="diasRotaAjud" class="form-control" id="diasRotaAjud">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diariasChapa">Valor Diária Chapa</label>
                                <input type="text" required name="diariasChapa" class="form-control" id="diariasChapa">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="diasRotaChapa">Dias em Rota Chapa</label>
                                <input type="text" required name="diasRotaChapa" class="form-control" id="diasRotaChapa">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="gastosAjud">Outros Gastos</label>
                                <input type="text" name="gastosAjud" class="form-control" id="gastosAjud">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-2 espaco">
                                <label for="tomada">Tomada</label>
                                <input type="text"  name="tomada" class="form-control" id="tomada">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="descarga">Descarga</label>
                                <input type="text" name="descarga" class="form-control" id="descarga">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="travessia">Travessia</label>
                                <input type="text" name="travessia" class="form-control" id="travessia">
                            </div>
                            <div class="form-group col-md-2 espaco">
                                <label for="servicos">Serviços</label>
                                <input type="text" name="servicos" class="form-control" id="servicos">
                            </div>
                            <div class="form-group col-md-4 espaco">
                                <label for="nomeAjud">Nome Ajudante</label>
                                <input type="text" name="nomeAjud" class="form-control" id="nomeAjud">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary"> Cadastrar </button>
                    </form>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script type="text/javascript" src="personalizado.js"></script>
        <script type="text/javascript" src="motoristas.js"></script>
        <script type="text/javascript" src="rotas.js"></script>
    </body>
</html>