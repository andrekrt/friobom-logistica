<?php

session_start();
require("conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false){

    $idUsuario = $_SESSION['idUsuario'];

    $sql = $db->prepare("SELECT * FROM usuarios WHERE idusuarios = :idUsuario");
    $sql->bindValue(':idUsuario', $idUsuario);
    $sql->execute();

    if($sql->rowCount()>0){
        $dado = $sql->fetch();

        $nomeUsuario = $dado['nome_usuario'];
        $tipoUsuario = $dado['idtipo_usuario'];
        $_SESSION['tipoUsuario'] = $tipoUsuario;
        $_SESSION['nomeUsuario'] = $nomeUsuario;

        $qtdeVeiculos = $db->query("SELECT * FROM veiculos")->rowCount();
        $qtdRotas = $db->query("SELECT * FROM rotas")->rowCount();
        $qtdViagem = $db->query("SELECT * FROM viagem")->rowCount();
        $qtdMotoristas = $db->query("SELECT * FROM motoristas")->rowCount();
        $qtdeRepatos = $db->query("SELECT * FROM solicitacoes")->rowCount();

        $totalKmRodado = $db->query("SELECT SUM(km_rodado) as kmRodado FROM viagem");
        $totalKmRodado = $totalKmRodado->fetch();
        $totalAbastecido = $db->query("SELECT SUM(litros) as litros FROM viagem");
        $totalAbastecido=$totalAbastecido->fetch();
        $mediaCombustivel = $totalKmRodado['kmRodado']/$totalAbastecido['litros'];
        
        $totalReparo01 = $db->query("SELECT SUM(valor) as total01 FROM solicitacoes");
        $totalReparo02 = $db->query("SELECT SUM(valor) as total02 FROM solicitacoes02");
        $totalReparo01=$totalReparo01->fetch();
        $totalReparo02=$totalReparo02->fetch();
        $totalGeral = $totalReparo01['total01']+$totalReparo02['total02'];
        
    }else{
        header("Location:login.php");
    }

}else{
    header("Location:login.php");
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>FRIOBOM - TRANSPORTE</title>
        <link rel="stylesheet" href="assets/css/style.css">
        <link rel="stylesheet" href="assets/css/bootstrap.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>
        <div class="container-fluid corpo">
            <div class="menu-lateral" id="menu-lateral">
                <div class="logo">  
                    <img src="assets/images/logo.png" alt="">
                </div>
                <div class="opcoes" >
                    <div class="item">
                        <a href="index.php">
                            <img src="assets/images/menu/inicio.png" alt="">
                        </a>
                    </div>
                    <div class="item">
                        <a class="" onclick="menuVeiculo()">
                            <img src="assets/images/menu/veiculos.png" alt="">
                        </a>
                        <nav id="submenu">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="veiculos/veiculos.php"> Veículos </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="veiculos/form-veiculos.php"> Cadastrar Veículo </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="veiculos/revisao.php"> Revisões </a> </li>
                                <li class="nav-item"> <a href="veiculos/relatorio.php" class="na-link">Despesas por Veículo</a> </li>
                                <li class="nav-item"> <a href="veiculos/gastos.php" class="na-link">Relatório</a> </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="item">
                        <a onclick="menuRota()">
                            <img src="assets/images/menu/rotas.png" alt="">
                        </a>
                        <nav id="submenuRota">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="rotas/rotas.php"> Rotas </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="rotas/form-rota.php"> Cadastrar Rota </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="rotas/relatorio.php"> Relatório</a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuMotorista()">
                            <img src="assets/images/menu/motoristas.png" alt="">
                        </a>
                        <nav id="submenuMotorista">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="motoristas/motoristas.php"> Motoristas </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="motoristas/form-motorista.php"> Cadastrar Motorista </a> </li>
                                <li class="nav-item"> <a href="motoristas/dados.php" class="nav-link"> Relatório</a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuOcorrencias()">
                            <img src="assets/images/menu/ocorrencias.png" alt="">
                        </a>
                        <nav id="submenuOcorrencias">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="ocorrencias/form-ocorrencias.php"> Registrar Nova Ocorrência </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="ocorrencias/ocorrencias.php"> Listar Ocorrências </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="ocorrencias/relatorio.php"> Ocorrências por Motorista</a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuDespesas()">
                            <img src="assets/images/menu/despesas.png" alt="">
                        </a>
                        <nav id="submenuDespesa">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="controle-despesas/despesas.php"> Despesas </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="controle-despesas/complementos.php"> Complementos </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="controle-despesas/form-lancar-despesas.php"> Lançar Despesa </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="controle-despesas/gerar-planilha.php"> Planilha de Despesas </a> </li>
                                <li class="nav-item"> <a class="subtitulo" onclick="menuEntregas()"> Entregas Capital </a>
                                    <nav id="submenuCapital">
                                        <ul class="nav flex-column">
                                            <li class="nav-item"> <a href="controle-despesas/entregas-capital/form-entregas.php"> Registrar Entregas </a> </li>
                                            <li class="nav-item"> <a href="controle-despesas/entregas-capital/entregas.php">  Entregas </a> </li>
                                        </ul> 
                                    </nav>
                                </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuCheck()">
                            <img src="assets/images/menu/check-list.png" alt="">
                        </a>
                        <nav id="submenuCheck">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="check-list/check-list.php"> Check-Lists </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="check-list/form-check.php"> Fazer Check-List </a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuReparos()">
                            <img src="assets/images/menu/reparos.png" alt="">
                        </a>
                        <nav id="submenuReparos">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a class="nav-link" href="reparos/solicitacoes.php"> Solicitações </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="reparos/form-solicitacao.php"> Nova Solicitação </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="/reparos/relatorio.php"> Valores Gastos</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="reparos/local-reparo.php">Local de Reparo</a> </li>
                                <li class="nav-item"> <a class="nav-link" href="reparos/pecas.php">Peças/Serviços</a> </li>
                            </ul> 
                        </nav> 
                    </div>
                    <div class="item">
                        <a onclick="menuAlmoxerifado()">
                            <img src="assets/images/menu/almoxerifado.png" alt="">
                        </a>
                        <nav id="submenuAlmoxerifado">
                            <ul class="nav flex-column">
                                <li class="nav-item"> <a href="almoxerifado/pecas.php" class="nav-link"> Estoque </a> </li>
                                <li class="nav-item"> <a href="almoxerifado/entradas.php" class="nav-link"> Entrada </a> </li>
                                <li class="nav-item"> <a href="almoxerifado/saidas.php" class="nav-link"> Saída </a> </li>
                                <li class="nav-item"> <a href="almoxerifado/ordem-servico.php" class="nav-link"> Ordem de Serviço </a> </li>
                                <li class="nav-item"> <a href="fornecedores/fornecedores.php" class="nav-link"> Fornecedores </a> </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="item">
                        <a onclick="menuPneu()">
                            <img src="assets/images/menu/pneu.png" alt="">
                        </a>
                        <nav id="submenuPneu">
                            <ul class="nav flex-column">
                                <li class="nav-item ">  <a class="subtitulo" onclick="menuManutencao()"> Manutenções </a> 
                                    <nav id="submenuManutencao">
                                        <ul class="nav flex-column">
                                            <li class="nav-item"> <a href="pneus/manutencao/form-manutencao.php"> Registrar Manutenção </a> </li>
                                            <li class="nav-item"> <a href="pneus/manutencao/manutencoes.php">  Manutenções Realizadas </a> </li>
                                        </ul> 
                                    </nav>
                                </li>
                                <li class="nav-item ">  <a class="subtitulo" onclick="menuRodizio()"> Rodízios </a> 
                                    <nav id="submenuRodizio">
                                        <ul class="nav flex-column">
                                            <li class="nav-item"> <a href="pneus/rodizio/form-rodizio.php"> Realizar Rodízio </a> </li>
                                            <li class="nav-item"> <a href="pneus/rodizio/rodizio.php">  Rodízios Realizadas </a> </li>
                                        </ul> 
                                    </nav>
                                </li>
                                <li class="nav-item "> <a class="subtitulo" onclick="menuSuco()"> Medição de Suco </a>   
                                    <nav id="submenuSuco">
                                        <ul class="nav flex-column">
                                            <li class="nav-item"> <a href="pneus/suco/form-suco.php"> Medir Suco </a> </li>
                                            <li class="nav-item"> <a href="pneus/suco/sucos.php"> Medidas de Suco </a> </li>
                                        </ul> 
                                    </nav>
                                </li>
                                <li class="nav-item"> <a href="pneus/form-pneus.php" class="nav-link"> Cadastrar Pneu </a> </li>
                                <li class="nav-item"> <a href="pneus/pneus.php" class="nav-link"> Pneu Cadastrados </a> </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="item">
                        <a href="sair.php">
                            <img src="assets/images/menu/sair.png" alt="">
                        </a>
                    </div>
                </div>                
            </div>
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                   <div class="icone-menu-superior">
                        <img src="assets/images/icones/home.png" alt="">
                   </div>
                   <div class="title">
                        <h2>Bem-Vindo <?php echo $nomeUsuario ?></h2>
                   </div>
                   <div class="menu-mobile">
                        <img src="assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                   </div>
                </div>
                <div class="menu-principal">
                    <div class="indices">
                        <div class="indice-area-title">
                            <div class="icone-indice">
                                <img src="assets/images/dados.png" alt="">
                            </div>
                            <div class="title-indice">
                                <p>Dados Logístico</p>
                            </div>
                        </div>
                    </div>
                    <div class="area-indice-val">
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Veículos</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/veiculo.png" alt="">
                                <p class="qtde"> <?php echo $qtdeVeiculos ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Rotas</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/rotas.png" alt="">
                                <p class="qtde"> <?php echo $qtdRotas ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Viagens</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/despesas.png" alt="">
                                <p class="qtde"> <?php echo $qtdViagem ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Motoristas</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/motoristas.png" alt="">
                                <p class="qtde"> <?php echo $qtdMotoristas ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Reparos</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/reparos.png" alt="">
                                <p class="qtde"> <?php echo $qtdeRepatos ?> </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Média Geral Km/L</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/combustivel.png" alt="">
                                <p class="qtde"> <?php echo number_format($mediaCombustivel,2,",", ".")  ; ?>  </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Média 3/4 Km/L</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/combustivel.png" alt="">
                                <p class="qtde"> 
                                
                                <?php

                                    $sql = $db->query("SELECT veiculos.categoria, SUM(km_rodado) as kmRodado, SUM(litros) as litros FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE veiculos.categoria = '3/4' ");
                                    $dados = $sql->fetch();
                                    echo number_format($dados['kmRodado']/$dados['litros'],2, ",", ".");
                                

                                ?>  
                                
                                </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Média Toco Km/L</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/combustivel.png" alt="">
                                <p class="qtde"> 
                                
                                <?php

                                    $sql = $db->query("SELECT veiculos.categoria, SUM(km_rodado) as kmRodado, SUM(litros) as litros FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE veiculos.categoria = 'toco' ");
                                    $dados = $sql->fetch();
                                    echo number_format($dados['kmRodado']/$dados['litros'],2, ",", ".");
                                

                                ?>  
                                
                                </p>
                            </div>
                        </div>
                        <div class="indice-ind">
                            <div class="indice-ind-tittle">
                                <p>Média Truck Km/L</p>
                            </div>
                            <div class="indice-qtde">
                                <img src="assets/images/icones/combustivel.png" alt="">
                                <p class="qtde"> 
                                
                                <?php

                                    $sql = $db->query("SELECT veiculos.categoria, SUM(km_rodado) as kmRodado, SUM(litros) as litros FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE veiculos.categoria = 'truck' ");
                                    $dados = $sql->fetch();
                                    echo number_format($dados['kmRodado']/$dados['litros'],2, ",", ".");
                                

                                ?>  
                                
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="assets/js/jquery.js"></script>
        <script src="assets/js/bootstrap.bundle.min.js"></script>
        <script src="assets/js/menu.js"></script>
    </body>
</html>