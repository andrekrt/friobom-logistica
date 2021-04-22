<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM entrada_estoque");
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
                            <img src="assets/images/menu/veiculos.png" alt="">
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
                        <h2>Entradas</h2>
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
                                <select name="peca" id="" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $filtro = $db->query("SELECT * FROM `entrada_estoque` LEFT JOIN peca_estoque ON entrada_estoque.peca_idpeca = peca_estoque.idpeca ORDER BY descricao_peca ASC");
                                    if ($filtro->rowCount() > 0) {
                                        $dados = $filtro->fetchAll();
                                        foreach ($dados as $dado):

                                    ?>
                                            <option value="<?=$dado['idpeca'] ?>"> <?=$dado['descricao_peca'] ?> </option>
                                    <?php

                                        endforeach;
                                    }

                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalEntrada" data-whatever="@mdo" name="idpeca">Nova Entrada</button>
                    </div>
                    <!-- MODAL lançamento de entrada -->
                    <div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Lançar Entrada</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="add-entrada.php" method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="dataNota"> Data Nota</label>
                                                <input type="date"  name="dataNota" class="form-control" id="dataNota">
                                            </div>
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="numNF"> Nº NF</label>
                                                <input type="text"  name="numNF" class="form-control" id="numNF">
                                            </div>
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="pedido"> Nº Pedido</label>
                                                <input type="text"  name="pedido" class="form-control" id="pedido">
                                            </div>
                                        </div>    
                                        <div class="form-row">
                                            <div class="form-group col-md-12 espaco ">
                                                <label for="peca"> Peça </label>
                                                <select required name="peca" id="peca" class="form-control">
                                                    <option value=""></option>
                                                    <?php $pecas = $db->query("SELECT * FROM peca_estoque");
                                                    $pecas = $pecas->fetchAll();
                                                    foreach($pecas as $peca):
                                                    ?>
                                                    <option value="<?=$peca['idpeca']?>"><?=$peca['descricao_peca']?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4 espaco">
                                                <label for="preco"> Preço de Custo </label>
                                                <input type="text" required name="preco" class="form-control" id="preco">
                                            </div>
                                            <div class="form-group col-md-4 espaco">
                                                <label for="qtd"> Quantidade </label>
                                                <input type="text" required name="qtd" class="form-control" id="qtd">
                                            </div>
                                            <div class="form-group col-md-4 espaco">
                                                <label for="desconto"> Desconto </label>
                                                <input type="text" required name="desconto" class="form-control" id="desconto">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 espaco">
                                                <label for="obsEntrada"> Observações </label>
                                                <input type="text"  name="obsEntrada" class="form-control" id="obsEntrada">
                                            </div>
                                            <div class="form-group col-md-6 espaco">
                                                <label for="fornecedor"> Fornecedor </label>
                                                <select required name="fornecedor" id="fornecedor" class="form-control">
                                                    <option value=""></option>
                                                    <?php $fornecedores = $db->query("SELECT * FROM fornecedores");
                                                    $fornecedores = $fornecedores->fetchAll();
                                                    foreach($fornecedores as $fornecedor):
                                                    ?>
                                                    <option value="<?=$fornecedor['id']?>"><?=$fornecedor['apelido']?></option>
                                                    <?php endforeach; ?>
                                                </select>
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
                    <!-- FIM MODAL lançamento de entrada-->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">ID</th>
                                    <th scope="col" class="text-center text-nowrap">Data NF</th>
                                    <th scope="col" class="text-center text-nowrap">Nº NF</th>
                                    <th scope="col" class="text-center text-nowrap">Nº Pedido</th>
                                    <th scope="col" class="text-center text-nowrap">Peça</th>
                                    <th scope="col" class="text-center text-nowrap"> Preço </th>
                                    <th scope="col" class="text-center text-nowrap"> Quantidade </th>
                                    <th scope="col" class="text-center text-nowrap"> Desconto </th>
                                    <th scope="col" class="text-center text-nowrap"> Observações </th>
                                    <th scope="col" class="text-center text-nowrap"> Fornecedor </th>
                                    <th scope="col" class="text-center text-nowrap">  Valor Total  </th>
                                    <th scope="col" class="text-center text-nowrap"> Usuário que Lançou </th>
                                    <th scope="col" class="text-center text-nowrap"> Ações  </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php                                

                                if(isset($_POST['filtro']) && !empty($_POST['peca'])){
                                    $peca = filter_input(INPUT_POST, 'peca');
                                    $sql = $db->prepare("SELECT * FROM `entrada_estoque` LEFT JOIN peca_estoque ON entrada_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON entrada_estoque.id_usuario = usuarios.idusuarios LEFT JOIN fornecedores ON entrada_estoque.fornecedor = fornecedores.id WHERE peca_idpeca = :peca");
                                    $sql->bindValue(':peca', $peca);
                                    $sql->execute();

                                    $totalEntradas = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalEntradas / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;
                                   
                                }else{
                                    $sql = $db->query("SELECT * FROM `entrada_estoque` LEFT JOIN peca_estoque ON entrada_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON entrada_estoque.id_usuario = usuarios.idusuarios LEFT JOIN fornecedores ON entrada_estoque.fornecedor = fornecedores.id");

                                    $totalEntradas = $sql->rowCount();
                                    $qtdPorPagina = 20;
                                    $numPaginas = ceil($totalEntradas / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT * FROM `entrada_estoque` LEFT JOIN peca_estoque ON entrada_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON entrada_estoque.id_usuario = usuarios.idusuarios LEFT JOIN fornecedores ON entrada_estoque.fornecedor = fornecedores.id LIMIT $paginaInicial,$qtdPorPagina");
                                }

                                $totalEntradas = $sql->rowCount();
                                $qtdPorPagina = 10;
                                $numPaginas = ceil($totalEntradas / $qtdPorPagina);
                                $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):
                                
                                ?>
                                <tr id="<?=$dado['identrada_estoque']?>">
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['identrada_estoque']; ?> </td>
                                    <td scope="col" class="text-left text-nowrap"> <?=$dado['data_nf']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['num_nf']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['num_pedido']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['descricao_peca']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?="R$ ". str_replace(".",",",$dado['preco_custo']); ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?= str_replace(".", ",",$dado['qtd']); ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?="R$ ". str_replace(".",",", $dado['desconto']); ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['obs']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['apelido']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['vl_total_comprado']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['nome_usuario']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$dado['identrada_estoque']; ?>" data-whatever="@mdo" value="<?=$dado['identrada_estoque']; ?>" name="idEntrada">Visualisar</button>
                                    </td>
                                </tr>
                                <!-- INICIO MODAL Editar-->
                                <div class="modal fade" id="modal<?=$dado['identrada_estoque']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Entrada</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza-entrada.php" method="post">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-1">
                                                            <label for="idPeca" class="col-form-label">ID</label>
                                                            <input type="text" readonly name="identrada" class="form-control" id="identrada" value="<?= $dado['identrada_estoque']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-2 ">
                                                            <label for="dataNf" class="col-form-label">Data NF</label>
                                                            <input type="date" name="dataNf" class="form-control" id="dataNf" value="<?= $dado['data_nf']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="numNf"  class="col-form-label">Nº NF</label>
                                                            <input type="text" name="numNf" class="form-control" id="numNf" value="<?= $dado['num_nf']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="numPedido"  class="col-form-label">Nº Pedido</label>
                                                            <input type="text" name="numPedido" class="form-control" id="numPedido" value="<?= $dado['num_pedido']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-5">
                                                            <label for="peca" class="col-form-label"> Peça </label>
                                                            <select required name="peca" id="peca" class="form-control">
                                                                <option value="<?=$dado['peca_idpeca']?>"><?=$dado['descricao_peca']?></option>
                                                                <?php $pecas = $db->query("SELECT * FROM peca_estoque");
                                                                $pecas = $pecas->fetchAll();
                                                                foreach($pecas as $peca):
                                                                ?>
                                                                <option value="<?=$peca['idpeca']?>"><?=$peca['descricao_peca']?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>    
                                                    <div class="form-row">
                                                        <div class="form-group col-md-3">
                                                            <label for="preco" class="col-form-label">Preço</label>
                                                            <input type="text" class="form-control" name="preco" id="preco" value="<?=$dado['preco_custo']?>">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="qtd" class="col-form-label">Quantidade</label>
                                                            <input type="text" class="form-control" name="qtd" id="qtd" value="<?=$dado['qtd']?>">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="desconto" class="col-form-label">Desconto</label>
                                                            <input type="text" class="form-control" name="desconto" id="desconto" value="<?=$dado['desconto']?>">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="totalComprado" class="col-form-label">Total</label>
                                                            <input type="text" readonly class="form-control" name="totalComprado" id="totalComprado" value="<?=$dado['vl_total_comprado']?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="obs" class="col-form-label">Observações</label>
                                                            <input type="text" class="form-control" name="obs" id="obs" value="<?=$dado['obs']?>">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="fornecedor" class="col-form-label">Fornecedor</label>
                                                            <select required name="fornecedor" id="fornecedor" class="form-control">
                                                                <option value="<?=$dado['fornecedor']?>"><?=$dado['apelido']?></option>
                                                                <?php $fornecedores = $db->query("SELECT * FROM fornecedores");
                                                                $fornecedores = $fornecedores->fetchAll();
                                                                foreach($fornecedores as $fornecedor):
                                                                ?>
                                                                <option value="<?=$fornecedor['id']?>"><?=$fornecedor['apelido']?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                            <?php if($dado['id_usuario']==$_SESSION['idUsuario']): ?>
                                                <a href="excluir-entrada.php?idEntrada=<?=$dado['identrada_estoque']; ?>" class="btn btn-danger" onclick="return confirm('Confirmar Exclusão?');"> Excluir </a>
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
        <script src="../assets/js/jquery.js"></script>
        <script src="../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../assets/js/menu.js"></script>
        
    </body>
</html>