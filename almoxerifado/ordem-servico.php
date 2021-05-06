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
                        <h2>Ordem de Serviço</h2>
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
                                <select name="problema" id="problema" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $filtro = $db->query("SELECT descricao_problema FROM ordem_servico ORDER BY descricao_problema ASC");
                                    if ($filtro->rowCount() > 0) {
                                        $dados = $filtro->fetchAll();
                                        foreach ($dados as $dado):

                                    ?>
                                            <option value="<?=$dado['descricao_problema'] ?>"> <?=$dado['descricao_problema'] ?> </option>
                                    <?php

                                        endforeach;
                                    }

                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalOrdemServico" data-whatever="@mdo" name="idOrdemServico">Nova Ordem de Serviço</button>
                    </div>
                    <!-- MODAL CADASTRO DE ordem de serviço -->
                    <div class="modal fade" id="modalOrdemServico" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="add-ordemservico.php" method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-2 espaco ">
                                                <label for="descricao">Placa </label>
                                                <select required name="placa" id="placa" class="form-control">
                                                    <option value=""></option>
                                                    <?php $pecas = $db->query("SELECT * FROM veiculos");
                                                    $pecas = $pecas->fetchAll();
                                                    foreach($pecas as $peca):
                                                    ?>
                                                    <option value="<?=$peca['placa_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-10 espaco ">
                                                <label for="problema"> Descrição Problema </label>
                                                <input type="text" required name="problema" id="problema" class="form-control">
                                            </div>
                                        </div>    
                                        <div class="form-row">
                                            <div class="form-group col-md-2 espaco ">
                                                <label for="manutencao"> Tipo de Manutenção </label>
                                                <select required name="manutencao" id="manutencao" class="form-control">
                                                    <option value=""></option>
                                                    <option value="Corretiva">Corretiva</option>
                                                    <option value="Preventiva">Preventiva</option>
                                                    <option value="Manutenção Externa">Manutenção Externa</option>
                                                    <option value="Troca de Óleo">Troca de Óleo</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-5 espaco ">
                                                <label for="causador"> Agente Causador </label>
                                                <input type="text" name="causador" id="causador" class="form-control">
                                            </div>
                                            <div class="form-group col-md-2 espaco ">
                                                <label for="requisicao">Nº Requisição de Peças </label>
                                                <input type="text" name="requisicao" class="form-control" id="requisicao">
                                            </div>
                                            <div class="form-group col-md-3 espaco ">
                                                <label for="solicitacao">Nº Solicitação de Peças(Serviços) </label>
                                                <input type="text" name="solicitacao" class="form-control" id="solicitacao">
                                            </div>
                                        </div>  
                                        <div class="form-row">
                                            <div class="form-group col-md-3 espaco ">
                                                <label for="numNf"> Nº NF </label>
                                                <input type="text" name="numNf" class="form-control" id="numNf">
                                            </div>
                                            <div class="form-group col-md-9 espaco ">
                                                <label for="obs"> Observações </label>
                                                <input type="text" name="obs" class="form-control" id="obs">
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
                    <!-- FIM MODAL CADASTRO DE ordem de serviço-->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">ID</th>
                                    <th scope="col" class="text-center text-nowrap">Data Abertura</th>
                                    <th scope="col" class="text-center text-nowrap">Placa</th>
                                    <th scope="col" class="text-center text-nowrap">Descrição Problema</th>
                                    <th scope="col" class="text-center text-nowrap">Tipo Manutenção</th>
                                    <th scope="col" class="text-center text-nowrap"> Agente Causador </th>
                                    <th scope="col" class="text-center text-nowrap"> Nº Requisição </th>
                                    <th scope="col" class="text-center text-nowrap"> Nº Solicitação </th>
                                    <th scope="col" class="text-center text-nowrap"> Nº NF </th>
                                    <th scope="col" class="text-center text-nowrap"> Observações </th>
                                    <th scope="col" class="text-center text-nowrap"> Situação  </th>
                                    <th scope="col" class="text-center text-nowrap"> Data Encerramento  </th>
                                    <th scope="col" class="text-center text-nowrap"> Usuário Lançou   </th>
                                    <th scope="col" class="text-center text-nowrap"> Ações  </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php                                

                                if(isset($_POST['filtro']) && !empty($_POST['problema'])){
                                    $descricao = filter_input(INPUT_POST, 'problema');
                                    $sql = $db->prepare("SELECT * FROM `ordem_servico` LEFT JOIN usuarios ON ordem_servico.idusuario = usuarios.idusuarios WHERE descricao_problema = :descricao");
                                    $sql->bindValue(':descricao', $descricao);
                                    $sql->execute();

                                    $totalOrdens = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalOrdens / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                }else{
                                    $sql = $db->query("SELECT * FROM `ordem_servico` LEFT JOIN usuarios ON ordem_servico.idusuario = usuarios.idusuarios");

                                    $totalOrdens = $sql->rowCount();
                                    $qtdPorPagina = 20;
                                    $numPaginas = ceil($totalOrdens / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT * FROM `ordem_servico` LEFT JOIN usuarios ON ordem_servico.idusuario = usuarios.idusuarios LIMIT $paginaInicial,$qtdPorPagina");
                                }

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):
                                                       
                                ?>
                                <tr id="<?=$dado['idordem_servico']?>">
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['idordem_servico']; ?> </td>
                                    <td scope="col" class="text-left text-nowrap"> <?= date("d/m/Y H:i:s", strtotime($dado['data_abertura'])) ; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['placa']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['descricao_problema']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['tipo_manutencao']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['causador']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['requisicao_saida']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['solicitacao_peca']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['num_nf']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['obs'] ; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['situacao']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=date("d/m/Y H:i:s", strtotime($dado['data_encerramento'])); ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['nome_usuario']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap">
                                        
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?= $dado['idordem_servico']; ?>" data-whatever="@mdo" value="<?= $dado['idordem_servico']; ?>" name="idordemServico">Visualisar</button>
                                    </td>
                                </tr>
                                <!-- INICIO MODAL -->
                                <div class="modal fade" id="modal<?= $dado['idordem_servico']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Ordem de Serviço</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza-ordemservico.php" method="post">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-1">
                                                            <label for="idOrdemServico" class="col-form-label">ID</label>
                                                            <input type="text" readonly name="idOrdemServico" class="form-control" id="idOrdemServico" value="<?= $dado['idordem_servico']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="dataAbertura" class="col-form-label">Data Abertura</label>
                                                            <input type="text" readonly name="dataAbertura" class="form-control" id="dataAbertura" value="<?= date("d/m/Y H:i:s", strtotime($dado['data_abertura'])) ; ?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="placa" readonly  class="col-form-label">Placa</label>
                                                            <select required name="placa" id="placa" class="form-control">
                                                                <option value="<?=$dado['placa']?>"><?=$dado['placa']?></option>
                                                                <?php $pecas = $db->query("SELECT * FROM veiculos");
                                                                $pecas = $pecas->fetchAll();
                                                                foreach($pecas as $peca):
                                                                ?>
                                                                <option value="<?=$peca['placa_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-7">
                                                            <label for="problema" class="col-form-label">Descrição Problema</label>
                                                            <input type="text" required name="problema" class="form-control" id="problema" value="<?= $dado['descricao_problema'] ; ?>">
                                                        </div>
                                                    </div>    
                                                    <div class="form-row">
                                                        <div class="form-group col-md-2">
                                                            <label for="manutencao" class="col-form-label">Tipo de Manutenção</label>
                                                            <select name="manutencao" required id="manutencao" class="form-control">
                                                                <option value="<?=$dado['tipo_manutencao']?>"><?=$dado['tipo_manutencao']?></option>
                                                                <option value="Corretiva">Corretiva</option>
                                                                <option value="Preventiva">Preventiva</option>
                                                                <option value="Manutenção Externa">Manutenção Externa</option>
                                                                <option value="Troca de Óleo">Troca de Óleo</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="causador" class="col-form-label">Causador</label>
                                                            <input type="text" class="form-control" name="causador" id="causador" value="<?=$dado['causador']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="requisicao" class="col-form-label">Nº Requisição de Saída</label>
                                                            <input type="text" class="form-control" name="requisicao" id="requisicao" value="<?=$dado['requisicao_saida']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="solicitacao" class="col-form-label">Nº Solicitação de Peças</label>
                                                            <input type="text" class="form-control" name="solicitacao" id="solicitacao" value="<?=$dado['solicitacao_peca']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="numNf" class="col-form-label">Nº NF</label>
                                                            <input type="text" class="form-control" name="numNf" id="numNf" value="<?=$dado['num_nf']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="situacao" class="col-form-label">Situação</label>
                                                            <select name="situacao" id="situacao" class="form-control">
                                                                <option value="<?=$dado['situacao']?>"><?=$dado['situacao']?></option>
                                                                <option value="Encerrada">Encerrada</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="obs" class="col-form-label">Observações</label>
                                                            <input type="text" class="form-control" name="obs" id="obs" value="<?=$dado['obs']?>">
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                            <?php if($dado['situacao']!='Encerrada' || $dado['idusuario']==$_SESSION['idUsuario']): ?>
                                                <a href="excluir-ordemservico.php?idordemServico=<?=$dado['idordem_servico']; ?>" onclick="return confirm('Confirmar Exclusão?');" class="btn btn-danger"> Excluir </a>
                                                <button type="submit" name="analisar" class="btn btn-primary">Atualizar</button>
                                            <?php endif; ?>
                                            <?php if($dado['situacao']!='Encerrada'): ?>
                                                <a class="btn btn-secondary" href="ficha-ordemservico.php?id=<?=$dado['idordem_servico']?>">Imprimir</a>
                                            <?php endif;?>
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