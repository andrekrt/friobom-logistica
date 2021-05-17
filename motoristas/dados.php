<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==1 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];

    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM viagem GROUP BY nome_motorista");
    
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
        <title>Relatório de Motorista</title>
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
                                <li class="nav-item"> <a class="nav-link" href="../check-list/form-chekc.php"> Fazer Check-List </a> </li>
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
                        <a href="sair.php">
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
                        <img src="../assets/images/icones/motoristas.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Relatório dos Motoristas</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <div class="filtro">
                        <form class="form-inline" action="" method="post">
                            <div class="form-row">
                                <select name="motorista" id="motorista" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    
                                    $consulta = $db->query("SELECT nome_motorista FROM motoristas");
                                    $dados = $consulta->fetchAll();
                                    foreach($dados as $dado){
                                    ?>
                                    <option value="<?php echo $dado['nome_motorista'] ?>"><?php echo $dado['nome_motorista'] ?></option>    
                                    <?php    
                                    }

                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <a href="dados-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
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

                                //iniciando relatorio com filtro
                                if(isset($_POST['filtro']) && empty($_POST['motorista'])==false){
                                    $motorista = filter_input(INPUT_POST, 'motorista');

                                    $selecionar = $db->query("SELECT nome_motorista, veiculos.categoria as categoria, nome_rota, viagem.placa_veiculo,COUNT(nome_motorista) as contagem, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(peso_carga) as peso, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(mediaSemTk) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, SUM(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(dias_em_rota) as diasEmRota, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia  FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo WHERE viagem.nome_motorista = '$motorista' GROUP BY nome_motorista, veiculos.categoria  ");

                                    if($selecionar){
                                        $dados = $selecionar->fetchAll();
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

                                    //relatorio padrao sem filtro
                                }else{
                                    $selecionar = $db->query("SELECT nome_motorista, veiculos.categoria as categoria, nome_rota, viagem.placa_veiculo,COUNT(nome_motorista) as contagem, AVG(custo_entrega) as custoEntrega, SUM(valor_transportado) as mediaValorTransportado, SUM(valor_devolvido) as valorDevolvido, SUM(qtd_entregas) as entregas, SUM(peso_carga) as peso, SUM(km_rodado) as kmRodado, SUM(litros) as litros, SUM(valor_total_abast) as abastecimento, SUM(mediaSemTk) as mediaKm, SUM(dias_em_rota) AS diasEmRota, SUM(diarias_motoristas) as diariasMotoristas, SUM(diarias_ajudante) as diariasAjudante, SUM(dias_motorista) as diasMotoristas, SUM(dias_ajudante) as diasAjudante, SUM(outros_servicos) as outrosServicos, SUM(dias_em_rota) as diasEmRota, SUM(tomada) as tomada, SUM(descarga) as descarga, SUM(travessia) as travessia  FROM viagem INNER JOIN veiculos ON viagem.placa_veiculo = veiculos.placa_veiculo GROUP BY nome_motorista, veiculos.categoria LIMIT $paginaInicial,$qtdPorPagina");

                                    if($selecionar){
                                        $dados = $selecionar->fetchAll();
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
                                }
                                
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
            
                        $paginaAnterior = $pagina-1;
                        $paginaPosterior = $pagina+1;
                                    
                    ?>
                    <nav aria-label="Navegação de página exemplo" class="paginacao">
                        <ul class="pagination">
                            <li class="page-item">
                            <?php
                                if($paginaAnterior!=0){
                                    echo "<a class='page-link' href='dados.php?pagina=$paginaAnterior' aria-label='Anterior'>
                                    <span aria-hidden='true'>&laquo;</span>
                                    <span class='sr-only'>Anterior</span>
                                </a>";
                                }else{
                                    echo "<a class='page-link' aria-label='Anterior'> 
                                        <span aria-hidden='true'>&laquo;</span>
                                        <span class='sr-only'>Anterior</span>
                                    </a>";
                                }
                            ?>
                            
                            </li>
                            <?php
                                for($i=1;$i < $numPaginas+1;$i++){
                                    echo "<li class='page-item'><a class='page-link' href='dados.php?pagina=$i'>$i</a></li>";
                                }
                            ?>
                            <li class="page-item">
                            <?php
                                if($paginaPosterior <= $numPaginas){
                                    echo " <a class='page-link' href='dados.php?pagina=$paginaPosterior' aria-label='Próximo'>
                                    <span aria-hidden='true'>&raquo;</span>
                                    <span class='sr-only'>Próximo</span>
                                </a>";
                                }else{
                                    echo " <a class='page-link' aria-label='Próximo'>
                                            <span aria-hidden='true'>&raquo;</span>
                                            <span class='sr-only'>Próximo</span>
                                    </a> ";
                                }
                            ?>
                        
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
    </body>
</html>