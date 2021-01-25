<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario']==1 || $_SESSION['tipoUsuario'] == 99){
    
    $nomeUsuario = $_SESSION['nomeUsuario'];
    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM veiculos");

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
        <title>FRIOBOM - TRANSPORTE</title>
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
                                <li class="nav-item"> <a class="nav-link" href="veiculos.php"> Veículos </a> </li>
                                <li class="nav-item"> <a class="nav-link" href="form-veiculos.php"> Cadastrar Veículo </a> </li>
                                <li class="nav-item"> <a href="relatorio.php" class="na-link">Despesas por Veículo</a> </li>
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
                        <a href="../sair.php">
                            <img src="../assets/images/menu/sair.png" alt="">
                        </a>
                    </div>
                </div>                
            </div>
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                   <div class="icone-menu-superior">
                        <img src="../assets/images/icones/veiculo.png" alt="">
                   </div>
                   <div class="title">
                        <h2>Veículos Cadastrados</h2>
                   </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered"> 
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">Código Veículo</th>
                                    <th scope="col" class="text-center text-nowrap">Tipo Veículo</th>
                                    <th scope="col" class="text-center text-nowrap">Placa Veículo</th>
                                    <th scope="col" class="text-center text-nowrap"> Última Revisão (KM) </th>
                                    <th scope="col" class="text-center text-nowrap"> Km Atual </th>
                                    <th scope="col" class="text-center text-nowrap"> Situação </th>
                                    <th scope="col" class="text-center text-nowrap"> Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                $totalVeiculo = $selecionar->rowCount();
                                $qtdPorPagina = 12;
                                $numPaginas = ceil($totalVeiculo/$qtdPorPagina);
                                $paginaInicial = ($qtdPorPagina*$pagina)-$qtdPorPagina;
                                $limitado = $db->query("SELECT * FROM veiculos LIMIT $paginaInicial,$qtdPorPagina ");
                                
                                if($limitado->rowCount()>0){
                                    $dados = $limitado->fetchAll();
                                    foreach($dados as $dado){
                                ?>
                                <tr id="<?php echo $dado['cod_interno_veiculo'] ?>">
                                    <td scope="col" class="text-center text-nowrap"> <?php echo $dado['cod_interno_veiculo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?php echo $dado['tipo_veiculo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?php echo $dado['placa_veiculo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?php echo $dado['km_ultima_revisao']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?php echo $dado['km_atual']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap">
                                        <?php
                                        $diferenca = $dado['km_atual']-$dado['km_ultima_revisao'];
                                        
                                        if($diferenca<15000){
                                            echo "Aguardando";
                                        }else{
                                            echo "Pronto para Revisão";
                                        }
                                        ?>
                                    </td>
                                    <td scope="col" class="text-center text-nowrap">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?php echo $dado['cod_interno_veiculo']; ?>" data-whatever="@mdo" value="<?php echo $dado['cod_interno_veiculo']; ?>" name="idSolic" >Visualisar</button>
                                    </td>
                                    
                                    <script type="text/javascript">
                                       var diferenca = "<?php echo $diferenca;?>";
                                       if(diferenca<15000){
                                            var container = document.getElementById('<?php echo $dado['cod_interno_veiculo'] ?>');
                                            container.style.background = 'green';
                                        }else{
                                            var container = document.getElementById('<?php echo $dado['cod_interno_veiculo'] ?>');
                                            container.style.background = 'red';
                                        }
                                    </script>
                                </tr>
                                <!-- INICIO MODAL -->
                                <div class="modal fade" id="modal<?php echo $dado['cod_interno_veiculo']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Veículo</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza.php" method="post">
                                                    <div class="form-row">
                                                        <input type="hidden" name="idSolicitacao" value="<?php echo $dado['cod_interno_veiculo']; ?>" >
                                                        <div class="form-group col-md-12">
                                                            <label for="codVeiculo" class="col-form-label">Código Veículo</label>
                                                            <input type="text" name="codVeiculo" class="form-control"  id="codVeiculo" Readonly value="<?php echo $dado['cod_interno_veiculo'];  ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="tipoVeiculo" class="col-form-label">Tipo Veículo</label>
                                                            <input type="text" class="form-control" name="tipoVeiculo"  id="tipoVeiculo" value="<?php echo $dado['tipo_veiculo'] ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="categoria" class="col-form-label">Categoria</label>
                                                            <select class="form-control" name="categoria" id="categoria">
                                                                <option value="<?php echo $dado['categorai'] ?>"> <?php echo $dado['categoria'] ?> </option>
                                                                <option value="3/4">3/4</option>
                                                                <option value="Toco">Toco</option>
                                                                <option value="Truck">Truck</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="placa" class="col-form-label">Placa</label>
                                                            <input type="text" class="form-control" name="placa" id="placa" value="<?php echo $dado['placa_veiculo'] ?>">
                                                        </div>
                                                    </div>  
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="kmUltimaRevisao" class="col-form-label">Km da Última Revisão</label>
                                                            <input type="text" class="form-control" name="kmUltimaRevisao" id="kmUltimaRevisao" value="<?php echo $dado['km_ultima_revisao'] ?>">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="kmAtual" class="col-form-label">Km Atual</label>
                                                            <input type="text" class="form-control" name="kmAtual" id="kmAtual" value="<?php echo $dado['km_atual'] ?>">
                                                        </div>
                                                    </div>       
                                            </div>
                                            <div class="modal-footer">
                                                    <a href="excluir.php?codVeiculo=<?php echo $dado['cod_interno_veiculo']; ?>" class="btn btn-danger" > Excluir </a>
                                                    <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- FIM MODAL -->
                                <?php 
                                
                                    }
                                }

                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- finalizando dados exclusivo da página -->
                <!-- Iniciando paginação -->
                <?php
            
                $paginaAnterior = $pagina-1;
                $paginaPosterior = $pagina+1;
                            
                ?>
                <nav aria-label="Navegação de página exemplo" class="paginacao">
                    <ul class="pagination">
                        <li class="page-item">
                        <?php
                            if($paginaAnterior!=0){
                                echo "<a class='page-link' href='veiculos.php?pagina=$paginaAnterior' aria-label='Anterior'>
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
                                echo "<li class='page-item'><a class='page-link' href='veiculos.php?pagina=$i'>$i</a></li>";
                            }
                        ?>
                        <li class="page-item">
                        <?php
                            if($paginaPosterior <= $numPaginas){
                                echo " <a class='page-link' href='veiculos.php?pagina=$paginaPosterior' aria-label='Próximo'>
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
        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
    </body>
</html>