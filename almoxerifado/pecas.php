<?php

session_start();
require("../conexao.php");
include('funcoes.php');

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99) {

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
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                    <div class="icone-menu-superior">
                        <img src="../assets/images/icones/almoxerifado.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Estoque</h2>
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
                                <select name="peca" id="peca" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $filtro = $db->query("SELECT descricao_peca FROM peca_estoque ORDER BY descricao_peca ASC");
                                    if ($filtro->rowCount() > 0) {
                                        $dados = $filtro->fetchAll();
                                        foreach ($dados as $dado):

                                    ?>
                                            <option value="<?=$dado['descricao_peca'] ?>"> <?=$dado['descricao_peca'] ?> </option>
                                    <?php

                                        endforeach;
                                    }

                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <div class="area-opcoes-button">     
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalPeca" data-whatever="@mdo" name="idpeca">Nova Peça</button>
                        </div>
                        <a href="pecas-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>    
                    </div>
                    <!-- MODAL CADASTRO DE PEÇA -->
                    <div class="modal fade" id="modalPeca" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Cadastro de Peça</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="add-peca.php" method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-12 espaco ">
                                                <label for="descricao"> Descrição Peça </label>
                                                <input type="text" required name="descricao" class="form-control" id="descricao">
                                            </div>
                                        </div>    
                                        <div class="form-row">
                                            <div class="form-group col-md-5 espaco ">
                                                <label for="medida"> Medida </label>
                                                <select required name="medida" id="medida" class="form-control">
                                                    <option value=""></option>
                                                    <option value="Litros">Litros</option>
                                                    <option value="UND">UND</option>
                                                    <option value="Metro">Metro</option>
                                                    <option value="Kg">Kg</option>
                                                    <option value="Ferramenta">Ferramenta</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-5 espaco ">
                                                <label for="grupo"> Grupo </label>
                                                <select required name="grupo" id="grupo" class="form-control">
                                                    <option value=""></option>
                                                    <option value="Serviços">Serviços</option>
                                                    <option value="Borracharia">Borracharia</option>
                                                    <option value="Taxas/IPVA/Multas">Taxas/IPVA/Multas</option>
                                                    <option value="Cabos">Cabos</option>
                                                    <option value="Peças">Peças</option>
                                                    <option value="Combustíveis/Lubrificantes">Combustíveis/Lubrificantes</option>
                                                    <option value="Suprimentos da Borracharia">Suprimentos da Borracharia</option>
                                                    <option value="Molas e Suspensão">Molas e Suspensão</option>
                                                    <option value="Eletrica">Eletrica</option>
                                                    <option value="Correias">Correias</option>
                                                    <option value="Acessórios">Acessorios</option>
                                                    <option value="Filtros">Filtros</option>
                                                    <option value="Gás">Gás</option>
                                                    <option value="Limpeza">Limpeza</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-2 espaco ">
                                                <label for="estoqueMinimo"> Estoque Mínimo </label>
                                                <input type="text" required name="estoqueMinimo" class="form-control" id="estoqueMinimo">
                                            </div>
                                        </div>  
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" name="analisar" class="btn btn-primary">Cadastrar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FIM MODAL CADASTRO DE PEÇA-->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">ID</th>
                                    <th scope="col" class="text-center text-nowrap">Descrição</th>
                                    <th scope="col" class="text-center text-nowrap">Medida</th>
                                    <th scope="col" class="text-center text-nowrap">Grupo</th>
                                    <th scope="col" class="text-center text-nowrap">Estoque Mínimo</th>
                                    <th scope="col" class="text-center text-nowrap"> Total Entrada </th>
                                    <th scope="col" class="text-center text-nowrap"> Total Saída </th>
                                    <th scope="col" class="text-center text-nowrap"> Total Estoque </th>
                                    <th scope="col" class="text-center text-nowrap"> Total Comprado </th>
                                    <th scope="col" class="text-center text-nowrap"> Situação </th>
                                    <th scope="col" class="text-center text-nowrap"> Data Cadastro </th>
                                    <th scope="col" class="text-center text-nowrap"> Usuário Cadastrou  </th>
                                    <th scope="col" class="text-center text-nowrap"> Ações  </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php                                

                                if(isset($_POST['filtro']) && !empty($_POST['peca'])){
                                    $peca = filter_input(INPUT_POST, 'peca');
                                    $sql = $db->prepare("SELECT * FROM `peca_estoque` LEFT JOIN usuarios ON peca_estoque.id_usuario = usuarios.idusuarios WHERE descricao_peca = :descricao");
                                    $sql->bindValue(':descricao', $peca);
                                    $sql->execute();

                                    $totalPecas = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalPecas / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                }else{
                                    $sql = $db->query("SELECT * FROM `peca_estoque` LEFT JOIN usuarios ON peca_estoque.id_usuario = usuarios.idusuarios");

                                    $totalPecas = $sql->rowCount();
                                    $qtdPorPagina = 20;
                                    $numPaginas = ceil($totalPecas / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT * FROM `peca_estoque` LEFT JOIN usuarios ON peca_estoque.id_usuario = usuarios.idusuarios LIMIT $paginaInicial,$qtdPorPagina");
                                }

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):
                                    $qtdEntradas = contaEntradas($dado['idpeca'])?contaEntradas($dado['idpeca']):0;
                                    $qtdSaida = contaSaida($dado['idpeca'])?contaSaida($dado['idpeca']):0;
                                    $estoque = $qtdEntradas-$qtdSaida;
                                    $estoqueMinimo = $dado['estoque_minimo'];
                                    $valorComprado = valorTotalPeca($dado['idpeca']);
                                    atualizaEStoque($qtdEntradas, $qtdSaida, $estoque, $estoqueMinimo, $valorComprado,$dado['idpeca']); 
                                    
                                ?>
                                <tr id="<?=$dado['idpeca']?>">
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['idpeca']; ?> </td>
                                    <td scope="col" class="text-left text-nowrap"> <?=$dado['descricao_peca']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['un_medida']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['grupo_peca']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['estoque_minimo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['total_entrada']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['total_saida']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['total_estoque']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['valor_total']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['situacao']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=date("d/m/Y",strtotime($dado['data_cadastro'])) ; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['nome_usuario']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?= $dado['idpeca']; ?>" data-whatever="@mdo" value="<?= $dado['idpeca']; ?>" name="idpeca">Visualisar</button>
                                    </td>
                                </tr>
                                <!-- INICIO MODAL -->
                                <div class="modal fade" id="modal<?= $dado['idpeca']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Peça</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza.php" enctype="multipart/form-data" method="post">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-1">
                                                            <label for="idPeca" class="col-form-label">ID</label>
                                                            <input type="text" readonly name="idPeca" class="form-control" id="idPeca" value="<?= $dado['idpeca']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-5">
                                                            <label for="descricao" class="col-form-label">Descrição</label>
                                                            <input type="text" name="descricao" class="form-control" id="descricao" value="<?= $dado['descricao_peca']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="medida" readonly  class="col-form-label">Medida</label>
                                                            <select required name="medida" id="medida" class="form-control">
                                                                <option value="<?=$dado['un_medida']?>"><?=$dado['un_medida']?></option>
                                                                <option value="Litros">Litros</option>
                                                                <option value="UND">UND</option>
                                                                <option value="Metro">Metro</option>
                                                                <option value="Kg">Kg</option>
                                                                <option value="Ferramenta">Ferramenta</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="grupo" class="col-form-label">Grupo</label>
                                                            <select required name="grupo" id="grupo" class="form-control">
                                                                <option value="<?=$dado['grupo_peca']?>"><?=$dado['grupo_peca']?></option>
                                                                <option value="Serviços">Serviços</option>
                                                                <option value="Borracharia">Borracharia</option>
                                                                <option value="Taxas/IPVA/Multas">Taxas/IPVA/Multas</option>
                                                                <option value="Cabos">Cabos</option>
                                                                <option value="Peças">Peças</option>
                                                                <option value="Combustíveis/Lubrificantes">Combustíveis/Lubrificantes</option>
                                                                <option value="Suprimentos da Borracharia">Suprimentos da Borracharia</option>
                                                                <option value="Molas e Suspensão">Molas e Suspensão</option>
                                                                <option value="Eletrica">Eletrica</option>
                                                                <option value="Correias">Correias</option>
                                                                <option value="Acessórios">Acessorios</option>
                                                                <option value="Filtros">Filtros</option>
                                                                <option value="Gás">Gás</option>
                                                                <option value="Limpeza">Limpeza</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="estoqueMinimo" class="col-form-label">Estoque Mínimo</label>
                                                            <input type="text" class="form-control" name="estoqueMinimo" id="estoqueMinimo" value="<?=$dado['estoque_minimo']?>">
                                                        </div>
                                                    </div>    
                                                    <div class="form-row">
                                                        <div class="form-group col-md-5">
                                                            <label for="totalEntrada" class="col-form-label">Total de Entrada</label>
                                                            <input type="text" class="form-control" name="totalEntrada" id="totalEntrada" readonly value="<?=$dado['total_entrada']?>">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="totalSaida" class="col-form-label">Total de Saída</label>
                                                            <input type="text" class="form-control" name="totalSaida" id="totalSaida" readonly value="<?=$dado['total_saida']?>">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="totalEstoque" class="col-form-label">Total no Estoque</label>
                                                            <input type="text" class="form-control" name="totalEstoque" id="totalEstoque" readonly value="<?=$dado['total_estoque']?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label for="situacao" class="col-form-label">Situação</label>
                                                            <input type="text" class="form-control" name="situacao" id="situacao" readonly value="<?=$dado['situacao']?>">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="dataCadastro" class="col-form-label">Data de Cadastro</label>
                                                            <input type="text" class="form-control" name="dataCadastro" id="dataCadastro" readonly value="<?=date("d/m/Y", strtotime($dado['data_cadastro'])); ?>">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="dataCadastro" class="col-form-label">Usuário Lançou</label>
                                                            <input type="text" class="form-control" name="dataCadastro" id="dataCadastro" readonly value="<?=$dado['nome_usuario']; ?>">
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                            <?php if($dado['id_usuario']==$_SESSION['idUsuario']): ?>
                                                <a href="excl uir.php?idPeca=<?=$dado['idpeca']; ?>" class="btn btn-danger"> Excluir </a>
                                                <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
                                            <?php endif; ?>
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
                                    echo "<a class='page-link' href='pecas.php?pagina=$paginaAnterior' aria-label='Anterior'>
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
                                echo "<li class='page-item'><a class='page-link' href='pecas.php?pagina=$i'>$i</a></li>";
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
                <!-- finalizando dados exclusivo da página -->
                
            </div>
        </div>
        
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        <script>
            $(document).ready(function() {
                $('#peca').select2();
            });
        </script>
    </body>
</html>