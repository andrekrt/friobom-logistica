<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] != 4){

    $nomeUsuario = $_SESSION['nomeUsuario'];

    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM viagem");
    
}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='index.php'</script>";
}

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dados</title>
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
            <div class="menu-lateral">
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
                        <img src="../assets/images/icones/despesas.png" alt="">
                   </div>
                   <div class="title">
                        <h2>Resumo de Informações</h2>
                   </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <div class="filtro">
                        <form class="form-inline" action="" method="post">
                            <div class="form-row">
                                <select name="tipo" id="tipo" class="form-control">
                                    <option value=""></option>
                                    <option value="motorista">Por Motorista</option>
                                    <option value="veiculo">Por Veículo</option>
                                    <option value="rota">Por Rota</option>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive analise">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center align-middle">Tipo</th>
                                    <th class="text-center align-middle">Categoria</th>
                                    <th class="text-center align-middle">Qtd</th>
                                    <th class="text-center align-middle"> Custo/Entrega </th>
                                    <th class="text-center align-middle">Valor Transportado</th>
                                    <th class="text-center align-middle"> Valor Devolvido </th>
                                    <th class="text-center align-middle"> Qtde de Entrega</th>
                                    <th class="text-center align-middle">Km Rodado</th>
                                    <th class="text-center align-middle"> Litros </th>
                                    <th class="text-center align-middle">Valor Abastecimento</th>
                                    <th class="text-center align-middle">Média Km/L</th>
                                    <th class="text-center align-middle">Dias em  Rota</th>
                                    <th class="text-center align-middle">Valor Diarias Motorista</th>
                                    <th class="text-center align-middle">Valor Diarias Ajudante</th>
                                    <th class="text-center align-middle">Diarias Motorista</th>
                                    <th class="text-center align-middle">Diarias Ajudante</th>
                                    <th class="text-center align-middle">Outros Gastos</th>
                                    <th class="text-center align-middle">Tomada</th>
                                    <th class="text-center align-middle">Descarga</th>
                                    <th class="text-center align-middle">Travessia</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $totalDespesas = $selecionar->rowCount();
                            $qtdPorPagina = 10;
                            $numPaginas = ceil($totalDespesas / $qtdPorPagina);
                            $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;
                    
                            if(isset($_POST['filtro']) && empty($_POST['tipo'])==false ){
                                $tipo = filter_input(INPUT_POST, 'tipo');
                                switch ($tipo) {
                                    case 'motorista':
                                        $selecionar = $db->query("SELECT * FROM viagem GROUP BY nome_motorista");
                                        $sql = $db->query("SELECT nome_motorista, veiculos.categoria as categoria, nome_rota, viagem.placa_veiculo,COUNT(nome_motorista) as contagem, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(peso_carga) as peso, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(mediaSemTk) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, SUM(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(dias_em_rota) as diasEmRota, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia  FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo GROUP BY nome_motorista, veiculos.categoria ");
                                        if($sql){
                                            $dados = $sql->fetchAll();
                                            foreach($dados as $dado){
                            ?>
                                <tr>
                                    <td class="text-center text-nowrap"><?php echo  $dado['nome_motorista'] ?></td>
                                    <td class="text-center text-nowrap"><?php echo  $dado['categoria'] ?></td>
                                    <td class="text-center text-nowrap"><?php echo  $dado['contagem'] ?></td>
                                    <td class="text-center tex-nowrap"> <?php echo "R$ ". number_format($dado['custoEntrega'],2,",",".") ?> </td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['mediaValorTransportado'], 2, ",",".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['valorDevolvido'],2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['entregas'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['kmRodado'], 2, ",", ".")?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['litros'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ " .number_format($dado['abastecimento'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['kmRodado']/$dado['litros'],2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['diasEmRota'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ " .number_format($dado['diariasMotoristas'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['diariasAjudante'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['diasMotoristas'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['diasAjudante'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['outrosServicos'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['tomada'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['descarga'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['travessia'], 2, ",", ".")  ?></td>
                                </tr>
                            <?php                    
                                            }

                                        }
                                        break;
                                    case 'veiculo':
                                        $sql = $db->query("SELECT nome_motorista, veiculos.categoria as categoria, nome_rota,viagem.placa_veiculo,COUNT(viagem.placa_veiculo) as contagem, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(peso_carga) as peso, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(km_rodado) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, SUM(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(dias_em_rota) as diasEmRota, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia  FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo GROUP BY viagem.placa_veiculo ");
                                        if($sql){
                                            $dados = $sql->fetchAll();
                                            foreach($dados as $dado){
                            ?>
                                <tr>
                                    <td class="text-center text-nowrap"><?php echo  $dado['placa_veiculo'] ?></td>
                                    <td class="text-center text-nowrap"><?php echo  $dado['categoria'] ?></td>
                                    <td class="text-center text-nowrap"><?php echo $dado['contagem'] ?></td>
                                    <td class="text-center tex-nowrap"> <?php echo "R$ ". number_format($dado['custoEntrega'],2,",",".") ?> </td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['mediaValorTransportado'], 2, ",",".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['valorDevolvido'],2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['entregas'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['kmRodado'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['litros'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['abastecimento'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['kmRodado']/$dado['litros'],2, ",", ".")   ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['diasEmRota'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['diariasMotoristas'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['diariasAjudante'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo  number_format($dado['diasMotoristas'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['diasAjudante'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['outrosServicos'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['tomada'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['descarga'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['travessia'], 2, ",", ".")  ?></td>
                                </tr>
                            <?php                    
                                            }
                                        }
                                        break;
                                    case 'rota':
                                        $sql = $db->query("SELECT nome_motorista, veiculos.categoria as categoria, nome_rota, viagem.placa_veiculo,COUNT(nome_rota) as contagem, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(peso_carga) as peso, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(mediaSemTk) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, AVG(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(dias_em_rota) as diasEmRota, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia  FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo GROUP BY nome_rota ");
                                        if($sql){
                                            $dados = $sql->fetchAll();
                                            foreach($dados as $dado){
                            ?>
                                <tr>
                                    <td class="text-center text-nowrap"><?php echo  $dado['nome_rota'] ?></td>
                                    <td class="text-center text-nowrap"><?php echo  $dado['categoria'] ?></td>
                                    <td class="text-center text-nowrap"><?php echo  $dado['contagem'] ?></td>
                                    <td class="text-center tex-nowrap"> <?php echo "R$ ". number_format($dado['custoEntrega'],2,",",".") ?> </td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['mediaValorTransportado'], 2, ",",".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['valorDevolvido'],2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['entregas'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['kmRodado'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['litros'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['abastecimento'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['kmRodado']/$dado['litros'],2, ",", ".")?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['diasEmRota'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['diariasMotoristas'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['diariasAjudante'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['diasMotoristas'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo number_format($dado['diasAjudante'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['outrosServicos'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['tomada'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['descarga'], 2, ",", ".")  ?></td>
                                    <td class="text-center text-nowrap"><?php echo "R$ ". number_format($dado['travessia'], 2, ",", ".")  ?></td>
                                </tr>
                            <?php                    
                                            }
                                        }
                                        break;
                                    default:
                                        break;
                                }
                            }

                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
    </body>
</html>