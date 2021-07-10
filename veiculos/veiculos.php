<?php

use Mpdf\Tag\Input;

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM veiculos");
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
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/veiculo.png" alt="">
                </div>
                <div class="title">
                    <h2>Veículos Cadastrados</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <div class="filtro">
                    <form action="" class="form-inline" method="post">
                        <div class="form-row">
                            <select name="veiculo" id="veiculo" class="form-control">
                                <option value=""></option>
                                <?php
                                $filtro = $db->query("SELECT placa_veiculo FROM veiculos ORDER BY placa_veiculo ASC");
                                if ($filtro->rowCount() > 0) {
                                    $dados = $filtro->fetchAll();
                                    foreach ($dados as $dado) {

                                ?>
                                        <option value="<?php echo $dado['placa_veiculo'] ?>"> <?php echo $dado['placa_veiculo'] ?> </option>
                                <?php

                                    }
                                }

                                ?>
                            </select>
                            <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                        </div>
                    </form>
                    <a href="veiculos-xls.php"><img src="../assets/images/excel.jpg" alt=""></a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-dark table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Código Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Tipo Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Categoria</th>
                                <th scope="col" class="text-center text-nowrap">Placa Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Peso Máximo</th>
                                <th scope="col" class="text-center text-nowrap">Cubagem</th>
                                <th scope="col" class="text-center text-nowrap"> Data Última Revisão</th>
                                <th scope="col" class="text-center text-nowrap"> Última Revisão (KM) </th>
                                <th scope="col" class="text-center text-nowrap"> Km Atual </th>
                                <th scope="col" class="text-center text-nowrap"> Km Restante </th>
                                <th scope="col" class="text-center text-nowrap"> Situação </th>
                                <th scope="col" class="text-center text-nowrap"> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            $totalVeiculo = $selecionar->rowCount();
                            $qtdPorPagina = 12;
                            $numPaginas = ceil($totalVeiculo / $qtdPorPagina);
                            $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                            if (isset($_POST['filtro']) && empty($_POST['veiculo']) == false) {
                                $veiculo = filter_input(INPUT_POST, 'veiculo');
                                $filtrado = $db->prepare("SELECT * FROM veiculos WHERE placa_veiculo = :placaVeiculo");
                                $filtrado->bindValue(':placaVeiculo', $veiculo);
                                $filtrado->execute();

                                if ($filtrado->rowCount() > 0) {
                                    $dados = $filtrado->fetchAll();
                                    foreach ($dados as $dado) {
                            ?>
                                        <tr id="<?php echo $dado['cod_interno_veiculo'] ?>">
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['cod_interno_veiculo']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['tipo_veiculo']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['categoria']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['placa_veiculo']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['peso_maximo'] . " Kg"; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['cubagem'] . " m3"; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo  date("d/m/Y", strtotime($dado['data_revisao'])); ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['km_ultima_revisao']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['km_atual']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?=$dado['km_atual']-$dado['km_ultima_revisao']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap">
                                                <?php
                                                $diferenca = $dado['km_atual'] - $dado['km_ultima_revisao'];

                                                if ($dado['categoria']='Truck' && $diferenca >= 20000) {
                                                    echo $situacao = "Pronto para Revisão";
                                                } elseif($dado['categoria']='Toco' && $diferenca >= 20000) {
                                                    echo $situacao = "Pronto para Revisão";
                                                }elseif($dado['categoria']='3/4' && $diferenca >= 15000){
                                                    echo $situacao = "Pronto para Revisão";
                                                }else{
                                                    echo $situacao = "Aguardando";
                                                }
                                                ?>
                                            </td>
                                            <td scope="col" class="text-center text-nowrap">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?php echo $dado['cod_interno_veiculo']; ?>" data-whatever="@mdo" value="<?php echo $dado['cod_interno_veiculo']; ?>" name="idSolic">Visualisar</button>
                                            </td>

                                            <script type="text/javascript">
                                                var diferenca = "<?php echo $diferenca; ?>";
                                                var situacao = "<?=$situacao?>"
                                                if (situacao == "Aguardando") {
                                                   
                                                    var container = document.getElementById('<?php echo $dado['cod_interno_veiculo'] ?>');
                                                    container.style.background = 'green';
                                                } else {
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
                                                                <input type="hidden" name="idSolicitacao" value="<?php echo $dado['cod_interno_veiculo']; ?>">
                                                                <div class="form-group col-md-12">
                                                                    <label for="codVeiculo" class="col-form-label">Código Veículo</label>
                                                                    <input type="text" name="codVeiculo" class="form-control" id="codVeiculo" Readonly value="<?php echo $dado['cod_interno_veiculo'];  ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="tipoVeiculo" class="col-form-label">Tipo Veículo</label>
                                                                    <input type="text" class="form-control" name="tipoVeiculo" id="tipoVeiculo" value="<?php echo $dado['tipo_veiculo'] ?>">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="categoria" class="col-form-label">Categoria</label>
                                                                    <select class="form-control" name="categoria" id="categoria">
                                                                        <option value="<?php echo $dado['categorai'] ?>"> <?php echo $dado['categoria'] ?> </option>
                                                                        <option value="3/4">3/4</option>
                                                                        <option value="Toco">Toco</option>
                                                                        <option value="Truck">Truck</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="placa" class="col-form-label">Placa</label>
                                                                    <input type="text" class="form-control" name="placa" id="placa" value="<?php echo $dado['placa_veiculo'] ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="peso" class="col-form-label">Peso Máximo</label>
                                                                    <input type="text" class="form-control" name="peso" id="peso" value="<?php echo $dado['peso_maximo'] ?>">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="cubagem" class="col-form-label">Cubagem</label>
                                                                    <input type="text" class="form-control" name="cubagem" id="cubagem" value="<?php echo $dado['cubagem'] ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-5">
                                                                    <label for="kmUltimaRevisao" class="col-form-label">Km da Última Revisão</label>
                                                                    <input type="text" class="form-control" name="kmUltimaRevisao" id="kmUltimaRevisao" required value="<?php echo $dado['km_ultima_revisao'] ?>">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="dataRevisao" class="col-form-label">Data Última Revisão</label>
                                                                    <input type="date" class="form-control" name="dataRevisao" id="dataRevisao" required value="<?php echo $dado['data_revisao'] ?>">
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <label for="kmAtual" class="col-form-label">Km Atual</label>
                                                                    <input type="text" required class="form-control" name="kmAtual" id="kmAtual" value="<?php echo $dado['km_atual'] ?>">
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="excluir.php?codVeiculo=<?php echo $dado['cod_interno_veiculo']; ?>" class="btn btn-danger"> Excluir </a>
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
                            } else {
                                $limitado = $db->query("SELECT * FROM veiculos ORDER BY pLaca_veiculo LIMIT $paginaInicial,$qtdPorPagina ");
                                if ($limitado->rowCount() > 0) {
                                    $dados = $limitado->fetchAll();
                                    foreach ($dados as $dado) {
                                    ?>
                                        <tr id="<?php echo $dado['cod_interno_veiculo'] ?>">
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['cod_interno_veiculo']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['tipo_veiculo']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['categoria']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['placa_veiculo']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['peso_maximo'] . " Kg"; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['cubagem'] . " m3"; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo date("d/m/Y", strtotime($dado['data_revisao'])); ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['km_ultima_revisao']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?php echo $dado['km_atual']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap"> <?=$dado['km_atual']-$dado['km_ultima_revisao']; ?> </td>
                                            <td scope="col" class="text-center text-nowrap">
                                                <?php
                                                $diferenca = $dado['km_atual'] - $dado['km_ultima_revisao'];

                                                if ($dado['categoria']='Truck' && $diferenca >= 20000) {
                                                    echo $situacao = "Pronto para Revisão";
                                                } elseif($dado['categoria']='Toco' && $diferenca >= 20000) {
                                                    echo $situacao = "Pronto para Revisão";
                                                }elseif($dado['categoria']='3/4' && $diferenca >= 15000){
                                                    echo $situacao = "Pronto para Revisão";
                                                }else{
                                                    echo $situacao = "Aguardando";
                                                }
                                                ?>
                                            </td>
                                            <td scope="col" class="text-center text-nowrap">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?php echo $dado['cod_interno_veiculo']; ?>" data-whatever="@mdo" value="<?php echo $dado['cod_interno_veiculo']; ?>" name="idSolic">Visualisar</button>
                                            </td>

                                            <script type="text/javascript">
                                                var diferenca = "<?php echo $diferenca; ?>";
                                                var situacao = "<?=$situacao?>"
                                                if (situacao == "Aguardando") {
                                                   
                                                    var container = document.getElementById('<?php echo $dado['cod_interno_veiculo'] ?>');
                                                    container.style.background = 'green';
                                                } else {
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
                                                                <input type="hidden" name="idSolicitacao" value="<?php echo $dado['cod_interno_veiculo']; ?>">
                                                                <div class="form-group col-md-12">
                                                                    <label for="codVeiculo" class="col-form-label">Código Veículo</label>
                                                                    <input type="text" name="codVeiculo" class="form-control" id="codVeiculo" Readonly value="<?php echo $dado['cod_interno_veiculo'];  ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-4">
                                                                    <label for="tipoVeiculo" class="col-form-label">Tipo Veículo</label>
                                                                    <input type="text" class="form-control" name="tipoVeiculo" id="tipoVeiculo" value="<?php echo $dado['tipo_veiculo'] ?>">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="categoria" class="col-form-label">Categoria</label>
                                                                    <select class="form-control" name="categoria" id="categoria">
                                                                        <option value="<?php echo $dado['categoria'] ?>"> <?php echo $dado['categoria'] ?> </option>
                                                                        <option value="3/4">3/4</option>
                                                                        <option value="Toco">Toco</option>
                                                                        <option value="Truck">Truck</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="placa" class="col-form-label">Placa</label>
                                                                    <input type="text" class="form-control" name="placa" id="placa" value="<?php echo $dado['placa_veiculo'] ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-6">
                                                                    <label for="peso" class="col-form-label">Peso Máximo</label>
                                                                    <input type="text" class="form-control" name="peso" id="peso" required value="<?php echo $dado['peso_maximo'] ?>">
                                                                </div>
                                                                <div class="form-group col-md-6">
                                                                    <label for="cubagem" class="col-form-label">Cubagem</label>
                                                                    <input type="text" required class="form-control" name="cubagem" id="cubagem" value="<?php echo $dado['cubagem'] ?>">
                                                                </div>
                                                            </div>
                                                            <div class="form-row">
                                                                <div class="form-group col-md-5">
                                                                    <label for="kmUltimaRevisao" class="col-form-label">Km da Última Revisão</label>
                                                                    <input type="text" required class="form-control" name="kmUltimaRevisao" id="kmUltimaRevisao" value="<?php echo $dado['km_ultima_revisao'] ?>">
                                                                </div>
                                                                <div class="form-group col-md-4">
                                                                    <label for="dataRevisao" class="col-form-label">Data Última Revisão</label>
                                                                    <input type="date" required class="form-control" name="dataRevisao" id="dataRevisao" value="<?php echo $dado['data_revisao'] ?>">
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <label for="kmAtual" class="col-form-label">Km Atual</label>
                                                                    <input type="text" required class="form-control" name="kmAtual" id="kmAtual" value="<?php echo $dado['km_atual'] ?>">
                                                                </div>
                                                            </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <a href="excluir.php?codVeiculo=<?php echo $dado['cod_interno_veiculo']; ?>" class="btn btn-danger"> Excluir </a>
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
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- finalizando dados exclusivo da página -->
            <!-- Iniciando paginação -->
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
                        echo "<li class='page-item'><a class='page-link' href='veiculos.php?pagina=$i'>$i</a></li>";
                    }
                    ?>
                    <li class="page-item">
                        <?php
                        if ($paginaPosterior <= $numPaginas) {
                            echo " <a class='page-link' href='veiculos.php?pagina=$paginaPosterior' aria-label='Próximo'>
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
    <!--<script src="../assets/js/jquery.js"></script>-->
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <script>
        $(document).ready(function() {
            $('#veiculo').select2();
        });
    </script>
</body>

</html>