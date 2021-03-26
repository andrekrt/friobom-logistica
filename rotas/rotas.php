<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==1 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];

    $pagina = (isset($_GET['pagina']))? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM rotas");
    
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
        <title>Rotas</title>
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
                        <img src="../assets/images/icones/rotas.png" alt="">
                    </div>
                    <div class="title">
                        <h2> Rotas Cadastradas</h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered"> 
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">Código Rota</th>
                                    <th scope="col" class="text-center">Rota</th>
                                    <th scope="col" class="text-center">Dia 01 Fechamento</th>
                                    <th scope="col" class="text-center">Hora de Fechamento 1</th>
                                    <th scope="col" class="text-center">Dia 02 Fechamento</th>
                                    <th scope="col" class="text-center">Hora de Fechamento 2</th>
                                    <th scope="col" class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalRota = $selecionar->rowCount();
                                $qtdPorPagina = 12;
                                $numPaginas = ceil($totalRota/$qtdPorPagina);
                                $paginaInicial = ($qtdPorPagina*$pagina)-$qtdPorPagina;
                                $limitado = $db->query("SELECT * FROM rotas LIMIT $paginaInicial,$qtdPorPagina ");

                                if($limitado->rowCount()>0){
                                    $dados = $limitado->fetchAll();
                                    foreach($dados as $dado){

                                ?>
                                <tr>
                                    <td scope="col" class="text-center"> <?php echo $dado['cod_rota']; ?> </td>
                                    <td scope="col" class="text-center"> <?php echo $dado['nome_rota']; ?> </td>
                                    <td scope="col" class="text-center"> <?php echo $dado['fechamento1']; ?> </td>
                                    <td scope="col" class="text-center"> <?php echo $dado['hora_fechamento1']; ?> </td>
                                    <td scope="col" class="text-center"> <?php echo $dado['fechamento2']; ?> </td>
                                    <td scope="col" class="text-center"> <?php echo $dado['hora_fechamento2']; ?> </td>
                                    <td scope="col" class="text-center">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?php echo $dado['cod_rota']; ?>" data-whatever="@mdo" value="<?php echo $dado['cod_rota']; ?>" name="idSolic" >Visualisar</button>
                                    </td>
                                </tr>
                                <!-- INICIO MODAL -->
                                <div class="modal fade" id="modal<?php echo $dado['cod_rota']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Rota</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza.php" method="post">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="codRota" class="col-form-label">Código Rota</label>
                                                            <input type="text" name="codRota" class="form-control"  id="codRota" Readonly value="<?php echo $dado['cod_rota'];  ?>">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="rota" class="col-form-label">Rota</label>
                                                            <input type="text" class="form-control" name="rota"  id="rota" value="<?php echo $dado['nome_rota'] ?>">
                                                        </div>
                                                        
                                                    </div>  
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="fechamento1" class="col-form-label">Fechamento 1</label>
                                                            <select name="fechamento1" id="fechamento1" class="form-control">
                                                                <option value="<?=$dado['fechamento1']?>"><?=$dado['fechamento1']?></option>
                                                                <option value="Segunda-Feira">Segunda-Feira</option>
                                                                <option value="Terça-Feira">Terça-Feira</option>
                                                                <option value="Quarta-Feira">Quarta-Feira</option>
                                                                <option value="Quinta-Feira">Quinta-Feira</option>
                                                                <option value="Sexta-Feira">Sexta-Feira</option>
                                                                <option value="Sábado">Sábado</option>
                                                                <option value="Segunda à Sexta">Segunda à Sexta</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="horaFechamento1" class="col-form-label">Hora Fechamento 1</label>
                                                            <input type="time" class="form-control" name="horaFechamento1"  id="horaFechamento1" value="<?=$dado['hora_fechamento1'] ?>">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="fechamento2" class="col-form-label">Fechamento 2</label>
                                                            <select name="fechamento2" id="fechamento2" class="form-control">
                                                                <option value="<?=$dado['fechamento2']?>"><?=$dado['fechamento2']?></option>
                                                                <option value="Segunda-Feira">Segunda-Feira</option>
                                                                <option value="Terça-Feira">Terça-Feira</option>
                                                                <option value="Quarta-Feira">Quarta-Feira</option>
                                                                <option value="Quinta-Feira">Quinta-Feira</option>
                                                                <option value="Sexta-Feira">Sexta-Feira</option>
                                                                <option value="Sábado">Sábado</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="horaFechamento2" class="col-form-label">Hora Fechamento 2</label>
                                                            <input type="time" class="form-control" name="horaFechamento2"  id="horaFechamento2" value="<?=$dado['hora_fechamento2'] ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="ceps" class="col-form-label">CEPs</label>
                                                            <textarea name="ceps" id="ceps" class="form-control" rows="5"><?=$dado['ceps']?></textarea>
                                                        </div>
                                                    </div>       
                                            </div>
                                            <div class="modal-footer">
                                                    <a href="excluir.php?codRotas=<?php echo $dado['cod_rota']; ?>" class="btn btn-danger" > Excluir </a>
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
                    <?php
            
                        $paginaAnterior = $pagina-1;
                        $paginaPosterior = $pagina+1;
                            
                    ?>
                    <nav aria-label="Navegação de página exemplo" class="paginacao">
                        <ul class="pagination">
                            <li class="page-item">
                            <?php
                                if($paginaAnterior!=0){
                                    echo "<a class='page-link' href='rotas.php?pagina=$paginaAnterior' aria-label='Anterior'>
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
                                    echo "<li class='page-item'><a class='page-link' href='rotas.php?pagina=$i'>$i</a></li>";
                                }
                            ?>
                            <li class="page-item">
                            <?php
                                if($paginaPosterior <= $numPaginas){
                                    echo " <a class='page-link' href='rotas.php?pagina=$paginaPosterior' aria-label='Próximo'>
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