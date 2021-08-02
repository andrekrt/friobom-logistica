<?php 

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==1 || $_SESSION['tipoUsuario'] == 99){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];

    $pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM complementos_combustivel");
    
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
        <title>Complementos</title>
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
                        <img src="../assets/images/icones/despesas.png" alt="">
                    </div>
                    <div class="title">
                        <h2> Complementos </h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <!-- dados exclusivo da página-->
                <div class="menu-principal">
                <div class="filtro">
                        <form  action="" class="form-inline " method="post">
                            <div class="form-row">
                                <select name="veiculo" id="" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $filtro = $db->query("SELECT * FROM veiculos");
                                    if ($filtro->rowCount() > 0) {
                                        $dados = $filtro->fetchAll();
                                        foreach ($dados as $dado):

                                    ?>
                                            <option value="<?=$dado['cod_interno_veiculo'] ?>"> <?=$dado['placa_veiculo']?> </option>
                                    <?php

                                        endforeach;
                                    }

                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <div class="area-opcoes-button">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalComplemento" data-whatever="@mdo" name="idpeca">Novo Complemento</button>
                        </div>
                        <a href="complementos-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>
                    </div>
                    <!-- MODAL lançamento de complemento -->
                    <div class="modal fade" id="modalComplemento" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Lançar Complemento</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="add-complemento.php" method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="veiculo"> Veículo</label>
                                                <select required name="veiculo" id="veiculo" class="form-control">
                                                    <option value=""></option>
                                                    
                                                    <?php $pecas = $db->query("SELECT * FROM veiculos");
                                                    $pecas = $pecas->fetchAll();
                                                    foreach($pecas as $peca):
                                                    ?>
                                                    <option value="<?=$peca['cod_interno_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="motorista">Motorista</label>
                                                <select required name="motorista" id="motorista" class="form-control">
                                                    <option value=""></option>
                                                    <?php $motoristas = $db->query("SELECT * FROM motoristas");
                                                    $motoristas = $motoristas->fetchAll();
                                                    foreach($motoristas as $motorista):
                                                    ?>
                                                    <option value="<?=$motorista['cod_interno_motorista']?>"><?=$motorista['nome_motorista']?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="kmSaida"> Km Saída </label>
                                                <input type="text" name="kmSaida" id="kmSaida" class="form-control">
                                            </div>
                                        </div>    
                                        <div class="form-row">
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="kmChegada"> Km Chegada</label>
                                                <input type="text" class="form-control" name="kmChegada" id="kmChegada">
                                            </div>
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="ltAbast"> Litros Abast.</label>
                                                <input type="text" class="form-control" name="ltAbast" id="ltAbast">
                                            </div>
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="vlAbast"> Valor Abast.</label>
                                                <input type="text" class="form-control" name="vlAbast" id="vlAbast">
                                            </div>
                                        </div>                                         
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="analisar" class="btn btn-primary">Lançar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIM MODAL lançamento de complemento-->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">ID</th>
                                    <th scope="col" class="text-center text-nowrap">Veículo</th>
                                    <th scope="col" class="text-center text-nowrap">Motorista</th>
                                    <th scope="col" class="text-center text-nowrap">Km Saída</th>
                                    <th scope="col" class="text-center text-nowrap">Km Chegada</th>
                                    <th scope="col" class="text-center text-nowrap"> Litros Abast. </th>
                                    <th scope="col" class="text-center text-nowrap"> Valor Abast. </th>
                                    <th scope="col" class="text-center text-nowrap"> Ações </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                    if(isset($_POST['filtro']) && !empty($_POST['veiculo'])){

                                        $veiculo = filter_input(INPUT_POST, 'veiculo');
                                        echo $veiculo;
                                        $sql = $db->prepare("SELECT * FROM complementos_combustivel LEFT JOIN veiculos ON complementos_combustivel.veiculo = veiculos.cod_interno_veiculo LEFT JOIN motoristas ON complementos_combustivel.motorista = motoristas.cod_interno_motorista WHERE veiculo = :veiculo");
                                        $sql->bindValue(':veiculo', $veiculo);
                                        $sql->execute();
                                    
                                        $totalSaidas = $sql->rowCount();
                                        $qtdPorPagina = 10;
                                        $numPaginas = ceil($totalSaidas / $qtdPorPagina);
                                        $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    }else{

                                        $sql = $db->query("SELECT * FROM complementos_combustivel LEFT JOIN veiculos ON complementos_combustivel.veiculo = veiculos.cod_interno_veiculo LEFT JOIN motoristas ON complementos_combustivel.motorista = motoristas.cod_interno_motorista");

                                        $totalComplementos = $sql->rowCount();
                                        $qtdPorPagina = 20;
                                        $numPaginas = ceil($totalComplementos / $qtdPorPagina);
                                        $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                        $sql = $db->query("SELECT * FROM complementos_combustivel LEFT JOIN veiculos ON complementos_combustivel.veiculo = veiculos.cod_interno_veiculo LEFT JOIN motoristas ON complementos_combustivel.motorista = motoristas.cod_interno_motorista LIMIT $paginaInicial,$qtdPorPagina");
                                        
                                    }

                                    $dados = $sql->fetchAll();
                                    foreach($dados as $dado):
                                    ?>
                                    <tr id="<?=$dado['id_complemento']?>">
                                        <td scope="col" class="text-center text-nowrap"> <?=$dado['id_complemento']; ?> </td>
                                        <td scope="col" class="text-center text-nowrap"> <?=$dado['placa_veiculo']; ?> </td>
                                        <td scope="col" class="text-center text-nowrap"> <?=$dado['nome_motorista']; ?> </td>
                                        <td scope="col" class="text-center text-nowrap"> <?=$dado['km_saida']; ?> </td>
                                        <td scope="col" class="text-center text-nowrap"> <?=$dado['km_chegada']; ?> </td>
                                        <td scope="col" class="text-center text-nowrap"> <?= str_replace(".",",",$dado['litros_abast'] ) ; ?> </td>
                                        <td scope="col" class="text-center text-nowrap"> <?= "R$ ". str_replace(".",",",$dado['valor_abast'] ) ; ?> </td>
                                        <td scope="col" class="text-center text-nowrap">
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$dado['id_complemento']; ?>" data-whatever="@mdo" value="<?=$dado['id_complemento']; ?>" name="idSaida">Visualisar</button>
                                        </td>
                                    </tr>
                                    <!-- INICIO MODAL Editar-->
                                    <div class="modal fade" id="modal<?=$dado['id_complemento']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Complemento</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="atualiza-complemento.php" method="post">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-2">
                                                                <label for="id" class="col-form-label">ID</label>
                                                                <input type="text" readonly name="id" class="form-control" id="id" value="<?= $dado['id_complemento']; ?>">
                                                            </div>
                                                            <div class="form-group col-md-3 ">
                                                                <label for="veiculo" class="col-form-label">Veículo</label>
                                                                <select required name="veiculo" id="veiculo" class="form-control">
                                                                    <option value="<?=$dado['veiculo']?>"><?=$dado['placa_veiculo']?></option>
                                                                    
                                                                    <?php $pecas = $db->query("SELECT * FROM veiculos");
                                                                    $pecas = $pecas->fetchAll();
                                                                    foreach($pecas as $peca):
                                                                    ?>
                                                                    <option value="<?=$peca['cod_interno_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                            <div class="form-group col-md-7">
                                                                <label for="motorista" class="col-form-label">Motorista</label>
                                                                <select required name="motorista" id="motorista" class="form-control">
                                                                    <option value="<?=$dado['motorista']?>"><?=$dado['nome_motorista']?></option>
                                                                    <?php $motoristas = $db->query("SELECT * FROM motoristas");
                                                                    $motoristas = $motoristas->fetchAll();
                                                                    foreach($motoristas as $motorista):
                                                                    ?>
                                                                    <option value="<?=$motorista['cod_interno_motorista']?>"><?=$motorista['nome_motorista']?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>    
                                                        </div> 
                                                        <div class="form-row">
                                                            <div class="form-group col-md-3  ">
                                                                <label for="kmSaida" class="col-form-label"> Km Saída</label>
                                                                <input type="text" value="<?=$dado['km_saida']?>" class="form-control" name="kmSaida" id="kmSaida">
                                                            </div>
                                                            <div class="form-group col-md-3  ">
                                                                <label for="kmChegada" class="col-form-label"> Km Chegada</label>
                                                                <input type="text" value="<?=$dado['km_chegada']?>" class="form-control" name="kmChegada" id="kmChegada">
                                                            </div>
                                                            <div class="form-group col-md-3  ">
                                                                <label for="ltAbast" class="col-form-label"> Litros Abast.</label>
                                                                <input type="text" value="<?=$dado['litros_abast']?>"  class="form-control" name="ltAbast" id="ltAbast">
                                                            </div>
                                                            <div class="form-group col-md-3  ">
                                                                <label for="vlAbast" class="col-form-label"> Valor Abast.</label>
                                                                <input type="text" value="<?=$dado['valor_abast']?>" class="form-control" name="vlAbast" id="vlAbast">
                                                            </div>
                                                        </div>     
                                                </div>
                                                <div class="modal-footer">
                                                <?php if($tipoUsuario==99): ?>
                                                    <a href="excluir-complemento.php?idComplemento=<?=$dado['id_complemento']; ?>" class="btn btn-danger" onclick="return confirm('Confirmar Exclusão?');"> Excluir </a>
                                                    
                                                <?php endif; ?>
                                                    <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- FIM MODAL -->    
                                    <?php
                                    endforeach;
                                
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php

                        $paginaAnterior = $pagina - 1;
                        $paginaPosterior = $pagina + 1;

                    ?>
                    <nav aria-label="Navegação de página exemplo" class="paginacao">
                        <ul class="pagination">
                            <li class="page-item">
                                <?php
                                if ($paginaAnterior != 0) {
                                    echo "<a class='page-link' href='veiculos.php?pagina=$paginaAnterior' aria-label='Anterior'>
                                        <span aria-hidden='true'>&laquo;</span>
                                        <span class='sr-only'>Anterior</span>
                                    </a>";
                                } else {
                                    echo "<a class='page-link' aria-label='Anterior'> 
                                            <span aria-hidden='true'>&laquo;</span>
                                            <span class='sr-only'>Anterior</span>
                                        </a>";
                                }
                                ?>

                            </li>
                            <?php
                            for ($i = 1; $i < $numPaginas + 1; $i++) {
                                echo "<li class='page-item'><a class='page-link' href='despesas.php?pagina=$i'>$i</a></li>";
                            }
                            ?>
                            <li class="page-item">
                                <?php
                                if ($paginaPosterior <= $numPaginas) {
                                    echo " <a class='page-link' href='despesas.php?pagina=$paginaPosterior' aria-label='Próximo'>
                                        <span aria-hidden='true'>&raquo;</span>
                                        <span class='sr-only'>Próximo</span>
                                    </a>";
                                } else {
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
        <script src="../assets/js/jquery.mask.js"></script>
        <script>
            jQuery(function($){
                $("#vlAbast").mask('###0,00', {reverse: true});
                $("#ltAbast").mask('###0,00', {reverse: true});
                
            })
        </script>
    </body>
</html>