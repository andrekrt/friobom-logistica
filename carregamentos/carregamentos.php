<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 5 || $_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario'] == 6) {

    $tipoUsuario = $_SESSION['tipoUsuario'];
    $nomeUsuario = $_SESSION['nomeUsuario'];
    $pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM carregamentos");
    switch ($tipoUsuario) {
        case 6:
            header("Location:carregamentos-iniciado.php");
            break;
        case 5:
            header("Location:carregamentos-iniciado.php");
            break;
    }
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
                            <li class="nav-item"> <a href="carregamentos.php" class="nav-link"> Carregamentos </a> </li>
                            <li class="nav-item"> <a href="form-carregamento.php" class="nav-link"> Novo Carregamento </a> </li>
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
                    <img src="../assets/images/icones/carregamento.png" alt="">
                </div>
                <div class="title">
                    <h2>Carregamentos</h2>
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
                            <select name="carregamento" id="" class="form-control">
                                <option value=""></option>
                                <?php
                                $filtro = $db->query("SELECT num_carreg FROM carregamentos ORDER BY num_carreg ASC");
                                if ($filtro->rowCount() > 0) {
                                    $dados = $filtro->fetchAll();
                                    foreach ($dados as $dado) {

                                ?>
                                        <option value="<?php echo $dado['num_carreg'] ?>"> <?php echo $dado['num_carreg'] ?> </option>
                                <?php

                                    }
                                }

                                ?>
                            </select>
                            <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-dark table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Carregamento</th>
                                <th scope="col" class="text-center text-nowrap">Doca</th>
                                <th scope="col" class="text-center text-nowrap">Peso (Kg)</th>
                                <th scope="col" class="text-center text-nowrap">Placa Veículo</th>
                                <th scope="col" class="text-center text-nowrap"> Hora do Caminhão na Doca </th>
                                <th scope="col" class="text-center text-nowrap"> Carregador Principal </th>
                                <th scope="col" class="text-center text-nowrap"> Situação </th>
                                <th scope="col" class="text-center text-nowrap"> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            if ($tipoUsuario == 5 || $tipoUsuario == 6) {
                                $sql = $db->prepare("SELECT * FROM carregamentos WHERE situacao = :situacao");
                                $sql->bindValue(':situacao', 'Iniciado');
                                $sql->execute();
                            } elseif ($tipoUsuario == 6) {
                                $sql = $db->prepare("SELECT * FROM carregamentos WHERE situacao = :situacao");
                                $sql->bindValue(":situacao", "Iniciado");
                                $sql->execute();
                            } elseif ($tipoUsuario == 99) {
                                $sql = $db->query("SELECT * FROM carregamentos");
                            }

                            $totalCarregamento = $sql->rowCount();
                            $qtdPorPagina = 10;
                            $numPaginas = ceil($totalCarregamento / $qtdPorPagina);
                            $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                            if (isset($_POST['filtro']) && !empty($_POST['carregamento'])) {
                                $numCarreg = filter_input(INPUT_POST, 'carregamento');
                                //COM FILTRO
                                if ($tipoUsuario == 5 || $tipoUsuario == 6) {
                                    $sql = $db->prepare("SELECT * FROM carregamentos WHERE situacao = :situacao AND num_carreg = :carregamento ");
                                    $sql->bindValue(':situacao', 'Iniciado');
                                    $sql->bindValue(':carregamento', $numCarreg);
                                    $sql->execute();
                                } elseif ($tipoUsuario == 6) {
                                    $sql = $db->prepare("SELECT * FROM carregamentos WHERE situacao = :situacao AND num_carreg = :carregamento");
                                    $sql->bindValue(":situacao", "Iniciado");
                                    $sql->bindValue(':carregamento', $numCarreg);
                                    $sql->execute();
                                } elseif ($tipoUsuario == 99) {
                                    $sql = $db->prepare("SELECT * FROM carregamentos WHERE num_carreg = :carregamento");
                                    $sql->bindValue(':carregamento', $numCarreg);
                                    $sql->execute();
                                }
                            } else {
                                //SEM FILTRO
                                if ($tipoUsuario == 5 || $tipoUsuario == 6) {
                                    $sql = $db->prepare("SELECT * FROM carregamentos WHERE situacao = :situacao LIMIT $paginaInicial,$qtdPorPagina ");
                                    $sql->bindValue(':situacao', 'Iniciado');
                                    $sql->execute();
                                } elseif ($tipoUsuario == 6) {
                                    $sql = $db->prepare("SELECT * FROM carregamentos WHERE situacao = :situacao LIMIT $paginaInicial,$qtdPorPagina");
                                    $sql->bindValue(":situacao", "Iniciado");
                                    $sql->execute();
                                } elseif ($tipoUsuario == 99) {
                                    $sql = $db->query("SELECT * FROM carregamentos LIMIT $paginaInicial,$qtdPorPagina");
                                }
                            }

                            $dados = $sql->fetchAll();
                            foreach ($dados as $dado) :
                            ?>
                                <tr id="<?= $dado['num_carreg'] ?>">
                                    <td scope="col" class="text-center text-nowrap"> <?= $dado['num_carreg']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?= $dado['doca']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?= $dado['peso']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?= $dado['placa_veiculo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?= $dado['hora_caminhao_doca']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?= $dado['carregador_principal']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?= $dado['situacao']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?= $dado['id']; ?>" data-whatever="@mdo" value="<?= $dado['id']; ?>" name="idSolic">Visualisar</button>
                                    </td>
                                </tr>
                                <!-- INICIO MODAL -->
                                <div class="modal fade" id="modal<?= $dado['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Carregamento</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="finaliza.php" enctype="multipart/form-data" method="post">
                                                    <div class="form-row">
                                                        <input type="hidden" name="id" value="<?= $dado['id']; ?>">
                                                        <div class="form-group col-md-2">
                                                            <label for="carregamento" class="col-form-label">Carregamento</label>
                                                            <input type="text" readonly name="carregamento" class="form-control" id="carregamento" value="<?= $dado['num_carreg']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="doca" readonly  class="col-form-label">Doca</label>
                                                            <input type="text" readonly class="form-control" name="doca" id="doca" value="<?= $dado['doca'] ?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="peso" readonly  class="col-form-label">Peso</label>
                                                            <input type="text" readonly class="form-control" name="peso" id="peso" value="<?= $dado['peso'] ?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="placa" class="col-form-label">Placa</label>
                                                            <input type="text" class="form-control" readonly name="placa" id="placa" value="<?=$dado['placa_veiculo']?>">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="horaDoca" class="col-form-label">Hora do Caminhão na Doca</label>
                                                            <input type="text" class="form-control" name="horaDoca" id="horaDoca" readonly value="<?=$dado['hora_caminhao_doca']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="tmpoAtraso" class="col-form-label">Tempo de Atraso</label>
                                                            <input type="text" class="form-control" name="tmpoAtraso" id="tmpoAtraso" readonly value="<?=$dado['tempo_atraso']?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-12">
                                                            <label for="carregadorPrincipal " class="col-form-label">Carregador Inicial</label>
                                                            <input type="text" class="form-control" name="carregadorPrincipal" id="carregadorPrincipal" readonly value="<?= $dado['carregador_principal'] ?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <label for="problemaCaminhao " class="col-form-label">Problemas no Caminhão</label>
                                                            <textarea class="form-control" readonly name="problemaCaminhap" id="problemaCaminhao" rows="3"><?=$dado['problema_caminhao']?></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="carregadoresAdicionais " class="col-form-label">Carregadores Adicionais</label>
                                                            <textarea class="form-control" name="carregadoresAdicionais" id="carregadoresAdicionais" readonly rows="3"><?=$dado['carregadores_adicionais']?></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-3">
                                                            <label for="usuarioErro " class="col-form-label">Carregador que Errou</label>
                                                            <input type="text" readonly class="form-control" name="carregadorErro" id="carregadorErro">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="fotoErro " class="col-form-label">Foto Erro</label>
                                                            <?php if(empty($dado['foto_erro'])): ?>
                                                                <input type="text" readonly class="form-control" value="Sem Fotos">
                                                            <?php else: ?>
                                                                <a  href="uploads/<?=$dado['num_carreg']?>/erros" target="_blank" ><input type="text" readonly class="form-control" value="Fotos Erros"></a>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="notaCarregamento "  class="col-form-label">Nota do Carregamento (0 à 10)</label>
                                                            <input type="text" readonly class="form-control" name="notaCarregamento" id="notaCarregamento">
                                                        </div>
                                                        <div class="form-group col-md-3">
                                                            <label for="fotoErro " class="col-form-label">Foto Carregamento</label>
                                                            <?php if(empty($dado['foto_carregamento'])): ?>
                                                                <input type="text" readonly class="form-control" value="Sem Fotos">
                                                            <?php else: ?>
                                                                <a  href="uploads/<?=$dado['num_carreg']?>/carregamentoConcluido" target="_blank" ><input type="text" readonly class="form-control" value="Fotos Carregamentos"></a>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-8">
                                                            <label for="obsRota "  class="col-form-label"> Observações da Rota </label>
                                                            <textarea class="form-control" name="obsRota" id="obsRota" <?=!empty($dado['obs_rota'])?'reandoly':'';?> rows="3"><?=$dado['obs_rota']?></textarea>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="situacao" class="col-form-label">Situação</label>
                                                            <select name="situacao" id="situaca" class="form-control">
                                                                <option value="<?=$dado['situacao']?>"><?=$dado['situacao']?></option>
                                                                <option value="Finalizado"> Finalizado</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="excluir.php?idCarregamento=<?=$dado['id']; ?>" class="btn btn-danger"> Excluir </a>
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
                            echo "<a class='page-link' href='carregamentos.php?pagina=$paginaAnterior' aria-label='Anterior'>
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
                        echo "<li class='page-item'><a class='page-link' href='carregamentos.php?pagina=$i'>$i</a></li>";
                    }
                    ?>
                    <li class="page-item">
                        <?php
                        if ($paginaPosterior <= $numPaginas) {
                            echo " <a class='page-link' href='carregamentos.php?pagina=$paginaPosterior' aria-label='Próximo'>
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