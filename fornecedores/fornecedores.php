<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM fornecedores");
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
                        <img src="../assets/images/icones/veiculo.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Fornecedores</h2>
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
                                <select name="fornecedor" id="" class="form-control">
                                    <option value=""></option>
                                    <?php
                                    $filtro = $db->query("SELECT * FROM fornecedores ORDER BY apelido ASC");
                                    if ($filtro->rowCount() > 0) {
                                        $dados = $filtro->fetchAll();
                                        foreach ($dados as $dado):

                                    ?>
                                            <option value="<?=$dado['id'] ?>"> <?=$dado['apelido'] ?> </option>
                                    <?php

                                        endforeach;
                                    }

                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalFornecedor" data-whatever="@mdo" name="idpeca">Novo Fornecedor</button>
                    </div>
                    <!-- MODAL cadastro de fornecedor -->
                    <div class="modal fade" id="modalFornecedor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Cadastrar Fornecedor</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="add-fornecedor.php" method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-3 espaco ">
                                                <label for="razaoSocial"> Razão Social</label>
                                                <input type="text" required name="razaoSocial" class="form-control" id="razaoSocial">
                                            </div>
                                            <div class="form-group col-md-2 espaco ">
                                                <label for="cnpj"> CNPJ  </label>
                                                <input type="text" required name="cnpj" class="form-control" id="cnpj">
                                            </div> 
                                            <div class="form-group col-md-3 espaco ">
                                                <label for="nomeFantasia"> Nome Fantasia  </label>
                                                <input type="text" required name="nomeFantasia" class="form-control" id="nomeFantasia">
                                            </div>                                          
                                            <div class="form-group col-md-2 espaco ">
                                                <label for="apelido"> Apelido  </label>
                                                <input type="text" required name="apelido" class="form-control" id="apelido">
                                            </div> 
                                            <div class="form-group col-md-2 espaco ">
                                                <label for="telefone"> Telefone  </label>
                                                <input type="text" required name="telefone" class="form-control" id="telefone">
                                            </div>                                          
                                        </div>    
                                        <div class="form-row">
                                            <div class="form-group col-md-3 espaco ">
                                                <label for="endereco"> Endereço </label>
                                                <input type="text" class="form-control" id="endereco" name="endereco">
                                            </div>
                                            <div class="form-group col-md-2 espaco ">
                                                <label for="bairro"> Bairro </label>
                                                <input type="text" class="form-control" id="bairro" name="bairro">
                                            </div>
                                            <div class="form-group col-md-2 espaco ">
                                                <label for="cidade"> Cidade </label>
                                                <input type="text" class="form-control" id="cidade" name="cidade">
                                            </div>
                                            <div class="form-group col-md-2 espaco ">
                                                <label for="estado"> Estado </label>
                                                <select name="estado" id="estado" class="form-control">
                                                    <option value="">Selecione</option>
                                                    <option value="AC">AC</option>
                                                    <option value="AL">AL</option>
                                                    <option value="AP">AP</option>
                                                    <option value="AM">AM</option>
                                                    <option value="BA">BA</option>
                                                    <option value="CE">CE</option>
                                                    <option value="DF">DF</option>
                                                    <option value="ES">ES</option>
                                                    <option value="GO">GO</option>
                                                    <option value="MA">MA</option>
                                                    <option value="MS">MS</option>
                                                    <option value="MT">MT</option>
                                                    <option value="MG">MG</option>
                                                    <option value="PA">PA</option>
                                                    <option value="PB">PB</option>
                                                    <option value="PR">PR</option>
                                                    <option value="PE">PE</option>
                                                    <option value="PI">PI</option>
                                                    <option value="RJ">RJ</option>
                                                    <option value="RN">RN</option>
                                                    <option value="RS">RS</option>
                                                    <option value="RO">RO</option>
                                                    <option value="RR">RR</option>
                                                    <option value="SC">SC</option>
                                                    <option value="SP">SP</option>
                                                    <option value="SE">SE</option>
                                                    <option value="TO">TO</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-3 espaco ">
                                                <label for="cep"> CEP </label>
                                                <input type="text" class="form-control" id="cep" name="cep">
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
                    <!-- FIM MODAL cadastro de fornecedor-->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">Razão Social</th>
                                    <th scope="col" class="text-center text-nowrap">CNPJ</th>
                                    <th scope="col" class="text-center text-nowrap">Nome Fantasia</th>
                                    <th scope="col" class="text-center text-nowrap">Apelido</th>
                                    <th scope="col" class="text-center text-nowrap">Endereço</th>
                                    <th scope="col" class="text-center text-nowrap"> Bairro </th>
                                    <th scope="col" class="text-center text-nowrap"> Cidade </th>
                                    <th scope="col" class="text-center text-nowrap"> Estado </th>
                                    <th scope="col" class="text-center text-nowrap"> CEP </th>
                                    <th scope="col" class="text-center text-nowrap"> Telefone </th>
                                    <th scope="col" class="text-center text-nowrap"> Ações  </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php                                

                                if(isset($_POST['filtro']) && !empty($_POST['fornecedor'])){
                                    $fornecedor = filter_input(INPUT_POST, 'fornecedor');
                                    $sql = $db->prepare("SELECT * FROM fornecedores WHERE id = :fornecedor");
                                    $sql->bindValue(':fornecedor', $fornecedor);
                                    $sql->execute();
                                }else{
                                    $sql = $db->query("SELECT * FROM fornecedores");
                                }

                                $totalFornecedores = $sql->rowCount();
                                $qtdPorPagina = 10;
                                $numPaginas = ceil($totalFornecedores / $qtdPorPagina);
                                $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):
                                
                                ?>
                                <tr id="<?=$dado['id']?>">
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['razao_social']; ?> </td>
                                    <td scope="col" class="text-left text-nowrap"> <?=$dado['cnpj']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['nome_fantasia']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['apelido']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['endereco']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['bairro']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['cidade']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['uf']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['cep']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['telefone']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?= $dado['id']; ?>" data-whatever="@mdo" value="<?= $dado['id']; ?>" name="idpeca">Visualisar</button>
                                    </td>
                                </tr>
                                <!-- INICIO MODAL Editar -->
                                <div class="modal fade" id="modal<?= $dado['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Fornecedor</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza-forn.php" enctype="multipart/form-data" method="post">
                                                    <input type="hidden" name="id" value="<?=$dado['id']?>">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-4">
                                                            <label for="razaoSocial" class="col-form-label">Razão Social</label>
                                                            <input type="text" name="razaoSocial" class="form-control" id="razaoSocial" value="<?= $dado['razao_social']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-2 ">
                                                            <label for="cnpj" class="col-form-label">CNPJ</label>
                                                            <input type="text" name="cnpj" class="form-control" id="cnpjEdi" value="<?= $dado['cnpj']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="nomeFantasia" readonly  class="col-form-label">Nome Fantasia</label>
                                                            <input type="text" name="nomeFantasia" id="nomeFantasia" class="form-control" value="<?= $dado['nome_fantasia']; ?>"> 
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="apelido" class="col-form-label">Apelido</label>
                                                            <input type="text" name="apelido" id="apelido" class="form-control" value="<?= $dado['apelido']; ?>"> 
                                                        </div>
                                                    </div>    
                                                    <div class="form-row">
                                                        <div class="form-group col-md-3">
                                                            <label for="endereco" class="col-form-label">Endereço</label>
                                                            <input type="text" class="form-control" name="endereco" id="endereco" value="<?=$dado['endereco']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="bairro" class="col-form-label">Bairro</label>
                                                            <input type="text" class="form-control" name="bairro" id="bairro" value="<?=$dado['bairro']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="cidade" class="col-form-label">Cidade</label>
                                                            <input type="text" class="form-control" name="cidade" id="cidade" value="<?=$dado['cidade']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="estado" class="col-form-label">Estado</label>
                                                            <select name="estado" id="estado" class="form-control">
                                                                <option value="<?=$dado['uf']?>"><?=$dado['uf']?></option>
                                                                <option value="AC">AC</option>
                                                                <option value="AL">AL</option>
                                                                <option value="AP">AP</option>
                                                                <option value="AM">AM</option>
                                                                <option value="BA">BA</option>
                                                                <option value="CE">CE</option>
                                                                <option value="DF">DF</option>
                                                                <option value="ES">ES</option>
                                                                <option value="GO">GO</option>
                                                                <option value="MA">MA</option>
                                                                <option value="MS">MS</option>
                                                                <option value="MT">MT</option>
                                                                <option value="MG">MG</option>
                                                                <option value="PA">PA</option>
                                                                <option value="PB">PB</option>
                                                                <option value="PR">PR</option>
                                                                <option value="PE">PE</option>
                                                                <option value="PI">PI</option>
                                                                <option value="RJ">RJ</option>
                                                                <option value="RN">RN</option>
                                                                <option value="RS">RS</option>
                                                                <option value="RO">RO</option>
                                                                <option value="RR">RR</option>
                                                                <option value="SC">SC</option>
                                                                <option value="SP">SP</option>
                                                                <option value="SE">SE</option>
                                                                <option value="TO">TO</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="cep" class="col-form-label">CEP</label>
                                                            <input type="text" class="form-control" name="cep" id="cepEdi" value="<?=$dado['cep']?>">
                                                        </div>
                                                        <div class="form-group col-md-2 ">
                                                            <label for="telefone" class="col-form-label"> Telefone  </label>
                                                            <input type="text" required name="telefone" class="form-control" id="telefoneEdi" value="<?=$dado['telefone']?>">
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="excluir-forn.php?id=<?=$dado['id']; ?>" class="btn btn-danger" onclick="return confirm('Confirmar Exclusão?');"> Excluir </a>
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
        <script src="../assets/js/jquery.mask.js"></script>
        <script>
            jQuery(function($){
            $("#cnpj").mask("00.000.000/0000-00");
            $("#cnpjEdi").mask("00.000.000/0000-00");
            $("#cep").mask("00000-000");
            $("#cepEdi").mask("00000-000");
            $("#telefone").mask("(00)00000-0000");
            $("#telefoneEdi").mask("(00)00000-0000");
            });
        </script>
    </body>
</html>