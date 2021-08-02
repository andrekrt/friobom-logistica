<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario']==4)) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;

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
        <title>Ocorrências</title>
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
            <div class="tela-principal">
                <div class="menu-superior">
                    <div class="icone-menu-superior">
                        <img src="../assets/images/icones/ocorrencia.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Relatório </h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                    </div>
                </div>
                <div class="menu-principal">
                    <div class="filtro">
                        <form  action="" class="form-inline " method="post">
                            <div class="form-row">
                                <select name="motorista" id="motorista" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $filtro = $db->query("SELECT cod_interno_motorista, nome_motorista FROM motoristas ORDER BY nome_motorista ASC");
                                    if ($filtro->rowCount() > 0) {
                                        $dados = $filtro->fetchAll();
                                        foreach ($dados as $dado):

                                    ?>
                                            <option value="<?=$dado['cod_interno_motorista'] ?>"> <?=$dado['nome_motorista'] ?> </option>
                                    <?php

                                        endforeach;
                                    }

                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <a href="ocorrencias-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a> 
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">Motorista</th>
                                    <th scope="col" class="text-center text-nowrap">Ocorrências por Má Condução </th>
                                    <th scope="col" class="text-center text-nowrap">Ocorrências por Mau Comportamento </th>
                                    <th scope="col" class="text-center text-nowrap">Qtd Ocorrências</th>
                                    <th scope="col" class="text-center text-nowrap">Qtd Advertências</th>
                                    <th scope="col" class="text-center text-nowrap">Total de Custos</th>
                                    <th scope="col" class="text-center text-nowrap">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                if(isset($_POST['filtro']) && !empty($_POST['motorista'])){

                                    $motorista = filter_input(INPUT_POST,'motorista');
                                    $sql = $db->prepare("SELECT ocorrencias.cod_interno_motorista, nome_motorista, COUNT(*) as ocorrencias, SUM(advertencia) as advertencia, SUM(vl_total_custos) as custoTotal FROM ocorrencias LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista WHERE ocorrencias.cod_interno_motorista = :motorista GROUP BY ocorrencias.cod_interno_motorista  ");
                                    $sql->bindValue(':motorista', $motorista);
                                    $sql->execute();

                                    $totalOcorrencias = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalOcorrencias / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                }else{
                                   
                                    $sql = $db->query("SELECT ocorrencias.cod_interno_motorista, nome_motorista, COUNT(*) as ocorrencias, SUM(advertencia) as advertencia, SUM(vl_total_custos) as custoTotal FROM ocorrencias LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista GROUP BY ocorrencias.cod_interno_motorista");

                                    $totalOcorrencias = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalOcorrencias / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT ocorrencias.cod_interno_motorista, nome_motorista, COUNT(*) as ocorrencias, SUM(advertencia) as advertencia, SUM(vl_total_custos) as custoTotal FROM ocorrencias LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista GROUP BY ocorrencias.cod_interno_motorista LIMIT $paginaInicial, $qtdPorPagina");

                                }

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):

                                    //qtd de má condução
                                    $qtdConduta = $db->prepare("SELECT * FROM ocorrencias WHERE cod_interno_motorista = :motorista AND tipo_ocorrencia = :ocorrencia");
                                    $qtdConduta->bindValue(':motorista', $dado['cod_interno_motorista']);
                                    $qtdConduta->bindValue(':ocorrencia', 'Má Condução');
                                    $qtdConduta->execute();
                                    $qtdConduta = $qtdConduta->rowCount();

                                    //qtd de mau comportamento
                                    $qtdComportamneto = $db->prepare("SELECT * FROM ocorrencias WHERE cod_interno_motorista = :motorista AND tipo_ocorrencia = :ocorrencia");
                                    $qtdComportamneto->bindValue(':motorista', $dado['cod_interno_motorista']);
                                    $qtdComportamneto->bindValue(':ocorrencia', 'Mau Comportamento');
                                    $qtdComportamneto->execute();
                                    $qtdComportamneto = $qtdComportamneto->rowCount();

                                ?>
                                <tr id="<?=$dado['cod_interno_motorista']?>" >
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['nome_motorista'];?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$qtdConduta;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$qtdComportamneto;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['ocorrencias'] ;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['advertencia'] ;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?= "R$ ". str_replace(".",",",$dado['custoTotal']) ;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> 
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?= $dado['cod_interno_motorista']; ?>" data-whatever="@mdo" value="<?= $dado['cod_interno_motorista']; ?>" name="cod_interno_motorista">Visualisar</button>
                                    </td>
                                    <script type="text/javascript">
                                        var $qtdOcorrencias = <?=$dado['ocorrencias']?>;
                                        if($qtdOcorrencias == 2){
                                           var container = document.getElementById('<?=$dado['cod_interno_motorista']?>');
                                           container.style.background = 'orange';
                                        }else if($qtdOcorrencias>=3){
                                            var container = document.getElementById('<?=$dado['cod_interno_motorista']?>');
                                           container.style.background = 'red';
                                        }
                                    </script>
                                </tr>
                                <!-- INICIO MODAL -->
                                <div class="modal fade" id="modal<?= $dado['cod_interno_motorista']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Ocorrências <?=$dado['nome_motorista']?></h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                <form action="">
                                                <?php
                                                    $sql = $db->prepare("SELECT * FROM ocorrencias WHERE cod_interno_motorista = :motorista");
                                                    $sql->bindValue(':motorista', $dado['cod_interno_motorista']);
                                                    $sql->execute();
                                                    $ocorrencias = $sql->fetchAll();
                                                    foreach($ocorrencias as $ocorrencia):
                                                ?>
                                                    <div class="form-row">
                                                        <div class="form-group espaco col-md-1">
                                                            <label>Ocorrência</label>
                                                            <input type="text" readonly  class="form-control" value="<?=$ocorrencia['idocorrencia']; ?>">
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label>Data</label>
                                                            <input type="date" readonly  class="form-control" value="<?=$ocorrencia['data_ocorrencia']; ?>">
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label>Veículo</label>
                                                            <input type="text" readonly  class="form-control" value="<?=$ocorrencia['placa']; ?>">
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label>Carregamento</label>
                                                            <input type="text" readonly  class="form-control" value="<?=$ocorrencia['num_carregamento']; ?>">
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label>Tipo</label>
                                                            <input type="text" readonly  class="form-control" value="<?=$ocorrencia['tipo_ocorrencia']; ?>">
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label>Ocorrencia</label>
                                                            <?=$ocorrencia['img_ocorrencia']==""?"<span class='form-control' readonly>Sem Anexo</span>":"<a class='form-control' readonly href='uploads/$ocorrencia[idocorrencia]/ocorrencias' target='_blank'>Anexo</a>" ;?>
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label>Advertência</label>
                                                            <?=$ocorrencia['img_advertencia']==""?"<span class='form-control' readonly>Sem Advêrtencia</span>":"<a class='form-control' readonly href='uploads/$ocorrencia[idocorrencia]/advertencias' target='_blank'>Anexo</a>" ;?>
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label>Laudo</label>
                                                            <?=$ocorrencia['img_laudo']==""?"<span class='form-control' readonly>Sem Laudo</span>":"<a class='form-control' readonly href='uploads/$ocorrencia[idocorrencia]/laudos' target='_blank'>Anexo</a>" ;?>
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label>Custo Total</label>
                                                            <input type="text" readonly  class="form-control" value="<?="R$ ". str_replace(".",",",$ocorrencia['vl_total_custos']) ; ?>">
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label>Resolvido</label>
                                                            <input type="text" readonly  class="form-control" value="<?=$ocorrencia['situacao'] ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6 espaco">
                                                            <label for="descricaoProblema">Descrição do Problema</label>
                                                            <textarea name="descricaoProblema" id="descricaoProblema" class="form-control" readonly rows="3"><?=$ocorrencia['descricao_problema']?></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6 espaco">
                                                            <label for="descricaoCustos">Descrição dos Custos</label>
                                                            <textarea name="descricaoCustos" id="descricaoCustos" class="form-control" readonly rows="3"><?=$ocorrencia['descricao_custos']?></textarea>
                                                        </div>
                                                    </div>
                                                    <hr>
                                                <?php        
                                                    endforeach;

                                                ?>
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
                    <!-- Iniciando paginação -->
                    <?php

                    $paginaAnterior = $pagina - 1;
                    $paginaPosterior = $pagina + 1;

                    ?>
                    <nav aria-label="Navegação de página exemplo" class="paginacao">
                        <ul class="pagination pagination-sm">
                            <li class="page-item">
                                <?php
                                if ($paginaAnterior != 0) {
                                    echo "<a class='page-link' href='relatorio.php?pagina=$paginaAnterior' aria-label='Anterior'>
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
                                echo "<li class='page-item'><a class='page-link' href='relatorio.php?pagina=$i'>$i</a></li>";
                            }
                            ?>
                            <li class="page-item">
                                <?php
                                if ($paginaPosterior <= $numPaginas) {
                                    echo " <a class='page-link' href='relatorio.php?pagina=$paginaPosterior' aria-label='Próximo'>
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
                <!-- finalizando dados exclusivo da página -->
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
        
        </script>        
    </body>
</html>