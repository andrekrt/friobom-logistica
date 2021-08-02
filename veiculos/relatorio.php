<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==1 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];

    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM viagem GROUP BY placa_veiculo");
    
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
        <title>Despesas por Veículo</title>
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
                            <img src="../assets/images/icones/veiculo.png" alt="">
                    </div>
                    <div class="title">
                            <h2>Despesas por Veículos</h2>
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
                                <select name="placa" id="placa" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    
                                    $consulta = $db->query("SELECT placa_veiculo FROM veiculos");
                                    $dados = $consulta->fetchAll();
                                    foreach($dados as $dado){
                                    ?>
                                    <option value="<?php echo $dado['placa_veiculo'] ?>"><?php echo $dado['placa_veiculo'] ?></option>    
                                    <?php    
                                    }

                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <a href="relatorio-xls.php"> <img src="../assets/images/excel.jpg" alt=""> </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <th class="text-center text-nowrap"> Placa </th>
                                <th class="text-center text-nowrap"> Tipo </th>
                                <th class="text-center text-nowrap"> Qtd Viagens </th>
                                <th class="text-center text-nowrap"> Km Rodado </th>
                                <th class="text-center text-nowrap"> Total Abastecido (Lt) </th>
                                <th class="text-center text-nowrap"> Valor Abastecido (R$) </th>
                                <th class="text-center text-nowrap"> Qtd Solicitações </th>
                                <th class="text-center text-nowrap"> Valor das Solicitações (R$) </th>
                                <th class="text-center text-nowrap"> R$/Km </th>
                            </thead>
                            <tbody>
                                <?php 
                                    $totalDespesas = $selecionar->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalDespesas / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    if( isset($_POST['filtro']) && empty($_POST['placa'])==false){
                                        $placa = filter_input(INPUT_POST, 'placa');
                                        
                                        $veiculos = $db->query("SELECT tipo_veiculo, placa_veiculo FROM veiculos WHERE placa_veiculo = '$placa' ");
                                        if($veiculos){
                                            $dados = $veiculos->fetchAll();
                                            foreach($dados as $dado){
                                ?>
                                    <tr>
                                        <td class="text-center text-nowrap"> <?php echo $placa = $dado['placa_veiculo'] ?> </td>
                                        <td class="text-center text-nowrap"> <?php echo $dado['tipo_veiculo'] ?> </td>
                                        <td class="text-center text-nowrap"> 
                                            <?php 
                                                $sql = $db->query("SELECT * FROM viagem WHERE placa_veiculo = '$placa'");
                                                $result = $sql->rowCount();

                                                echo $result;
                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?php 
                                            
                                                $sql = $db->query("SELECT SUM(km_rodado) as totalKmRodado FROM viagem WHERE placa_veiculo = '$placa'");
                                                $result = $sql->fetch();

                                                echo $result['totalKmRodado'];
                                            
                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?php
                                            
                                                $sql = $db->query("SELECT placa_veiculo, SUM(litros) as totalLitros FROM viagem WHERE placa_veiculo = '$placa' ");
                                                $result = $sql->fetch();

                                                echo number_format($result['totalLitros'],2, ",", ".") . " L";

                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap"> 
                                            <?php
                                                
                                                $sql = $db->query("SELECT placa_veiculo, SUM(valor_total_abast) as valorLitros FROM viagem WHERE placa_veiculo = '$placa' ");
                                                $result = $sql->fetch();

                                                echo "R$ ".number_format($result['valorLitros'],2, ",", ".");

                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap"> 
                                            <?php
                                                
                                                $sql = $db->query("SELECT placarVeiculo FROM solicitacoes WHERE placarVeiculo = '$placa' ");
                                                $result = $sql->rowCount();

                                                echo $result;

                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap"> 
                                            <?php
                                                
                                                $sql = $db->query("SELECT solicitacoes.placarVeiculo, SUM(solicitacoes.valor) + SUM(solicitacoes02.valor) as totalDespesas FROM solicitacoes LEFT JOIN solicitacoes02 ON solicitacoes.id = solicitacoes02.idSocPrinc WHERE solicitacoes.placarVeiculo = '$placa' ");
                                                $result = $sql->fetch();

                                                echo "R$ ". number_format($result['totalDespesas'],2, ",", ".");

                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?php
                                            
                                            $sql = $db->query("SELECT placa_veiculo, SUM(valor_total_abast) as valorLitros FROM viagem WHERE placa_veiculo = '$placa' ");
                                            $result = $sql->fetch();
                                            $valorLitros = $result['valorLitros'];

                                            $sql = $db->query("SELECT SUM(km_rodado) as totalKmRodado FROM viagem WHERE placa_veiculo = '$placa'");
                                            $result = $sql->fetch();
                                            $kmRodado = $result['totalKmRodado'];
                                            
                                            $sql = $db->query("SELECT solicitacoes.placarVeiculo, SUM(solicitacoes.valor) + SUM(solicitacoes02.valor) as totalDespesas FROM solicitacoes LEFT JOIN solicitacoes02 ON solicitacoes.id = solicitacoes02.idSocPrinc WHERE solicitacoes.placarVeiculo = '$placa' ");
                                            $result = $sql->fetch();

                                            $despesas = $result['totalDespesas'];

                                            $mediaCusto= ($despesas+$valorLitros)/$kmRodado;

                                            echo number_format($mediaCusto, 2, ",", ".");

                                            ?>
                                        </td>
                                    </tr>
                                <?php                
                                            }
                                        }

                                    }else{
                                        $veiculos = $db->query("SELECT tipo_veiculo, placa_veiculo FROM veiculos LIMIT $paginaInicial,$qtdPorPagina ");

                                        if($veiculos){
                                            $dados = $veiculos->fetchAll();
                                            foreach($dados as $dado){
                                ?>

                                    <tr >
                                        <td class="text-center text-nowrap"> <?php echo $placa = $dado['placa_veiculo'] ?> </td>
                                        <td class="text-center text-nowrap"> <?php echo $dado['tipo_veiculo'] ?> </td>
                                        <td class="text-center text-nowrap"> 
                                            <?php 
                                                $sql = $db->query("SELECT * FROM viagem WHERE placa_veiculo = '$placa'");
                                                $result = $sql->rowCount();

                                                echo $result;
                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?php 
                                            
                                                $sql = $db->query("SELECT SUM(km_rodado) as totalKmRodado FROM viagem WHERE placa_veiculo = '$placa'");
                                                $result = $sql->fetch();

                                                echo $result['totalKmRodado'];
                                            
                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?php
                                            
                                                $sql = $db->query("SELECT placa_veiculo, SUM(litros) as totalLitros FROM viagem WHERE placa_veiculo = '$placa' ");
                                                $result = $sql->fetch();

                                                echo number_format($result['totalLitros'],2, ",", ".") . " L";

                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap"> 
                                            <?php
                                                
                                                $sql = $db->query("SELECT placa_veiculo, SUM(valor_total_abast) as valorLitros FROM viagem WHERE placa_veiculo = '$placa' ");
                                                $result = $sql->fetch();

                                                echo "R$ ".number_format($result['valorLitros'],2, ",", ".");

                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap"> 
                                            <?php
                                                
                                                $sql = $db->query("SELECT placarVeiculo FROM solicitacoes WHERE placarVeiculo = '$placa' ");
                                                $result = $sql->rowCount();

                                                echo $result;

                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap"> 
                                            <?php
                                                
                                                $sql = $db->query("SELECT solicitacoes.placarVeiculo, SUM(solicitacoes.valor) + SUM(solicitacoes02.valor) as totalDespesas FROM solicitacoes LEFT JOIN solicitacoes02 ON solicitacoes.id = solicitacoes02.idSocPrinc WHERE solicitacoes.placarVeiculo = '$placa' ");
                                                $result = $sql->fetch();

                                                echo "R$ ". number_format($result['totalDespesas'],2, ",", ".");

                                            ?>
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <?php
                                            
                                                $sql = $db->query("SELECT placa_veiculo, SUM(valor_total_abast) as valorLitros FROM viagem WHERE placa_veiculo = '$placa' ");
                                                $result = $sql->fetch();
                                                $valorLitros = $result['valorLitros'];

                                                $sql = $db->query("SELECT SUM(km_rodado) as totalKmRodado FROM viagem WHERE placa_veiculo = '$placa'");
                                                $result = $sql->fetch();
                                                $kmRodado = $result['totalKmRodado'];
                                                
                                                $sql = $db->query("SELECT solicitacoes.placarVeiculo, SUM(solicitacoes.valor) + SUM(solicitacoes02.valor) as totalDespesas FROM solicitacoes LEFT JOIN solicitacoes02 ON solicitacoes.id = solicitacoes02.idSocPrinc WHERE solicitacoes.placarVeiculo = '$placa' ");
                                                $result = $sql->fetch();

                                                $despesas = $result['totalDespesas'];

                                                $mediaCusto= ($despesas+$valorLitros)/$kmRodado;

                                                echo number_format($mediaCusto, 2, ",", ".");

                                            ?>
                                        </td>
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
                                    echo "<a class='page-link' href='relatorio.php?pagina=$paginaAnterior' aria-label='Anterior'>
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
                                    echo "<li class='page-item'><a class='page-link' href='relatorio.php?pagina=$i'>$i</a></li>";
                                }
                            ?>
                            <li class="page-item">
                            <?php
                                if($paginaPosterior <= $numPaginas){
                                    echo " <a class='page-link' href='relatorio.php?pagina=$paginaPosterior' aria-label='Próximo'>
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

        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script>
        $(document).ready(function() {
            $('#placa').select2();
        });
    </script>
    </body>
</html>