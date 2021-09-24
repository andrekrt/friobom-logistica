<?php

session_start();
require("../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM revisao_veiculos");
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
        <?php require('../menu-lateral.php') ?>
        <!-- Tela com os dados -->
        <div class="tela-principal">
            <div class="menu-superior">
                <div class="icone-menu-superior">
                    <img src="../assets/images/icones/veiculo.png" alt="">
                </div>
                <div class="title">
                    <h2>Revisões</h2>
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
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalRevisao" data-whatever="@mdo" name="revisao">Lançar Revisão</button>
                    </div>
                    <a href="revisao-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>                   
                </div>
                <!-- MODAL lançamento de revisão -->
                <div class="modal fade" id="modalRevisao" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="add-revisao.php" method="post">
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
                                            <div class="form-group col-md-5 espaco ">
                                                <label for="kmRevisao">Km da Revisão </label>
                                                <input type="text" required name="kmRevisao" id="kmRevisao" class="form-control">
                                            </div>
                                            <div class="form-group col-md-5 espaco ">
                                                <label for="dataRevisao">Data da Revisão </label>
                                                <input type="date" required name="dataRevisao" id="dataRevisao" class="form-control">
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
                    <!-- FIM MODAL CADASTRO DE ordem de serviço-->
                <div class="table-responsive">
                    <table class="table table-striped table-dark table-bordered">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center text-nowrap">Placa Veículo</th>
                                <th scope="col" class="text-center text-nowrap">Km Revisão</th>
                                <th scope="col" class="text-center text-nowrap">Data da Revisão</th>
                                <th scope="col" class="text-center text-nowrap"> Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            
                            if(isset($_POST['filtro']) && !empty($_POST['veiculo'])){
                                $veiculo = filter_input(INPUT_POST, 'veiculo');
                                $sql = $db->prepare("SELECT * FROM revisao_veiculos WHERE placa_veiculo = :veiculo");
                                $sql->bindValue(':veiculo', $veiculo);
                                $sql->execute();

                                $totalRevisao = $sql->rowCount();
                                $qtdPorPagina = 10;
                                $numPaginas = ceil($totalRevisao / $qtdPorPagina);
                                $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                            }else{
                                $veiculo = filter_input(INPUT_POST, 'veiculo');
                                $sql = $db->query("SELECT * FROM revisao_veiculos");

                                $totalRevisao = $sql->rowCount();
                                $qtdPorPagina = 10;
                                $numPaginas = ceil($totalRevisao / $qtdPorPagina);
                                $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                $sql = $db->query("SELECT * FROM revisao_veiculos LIMIT $paginaInicial,$qtdPorPagina");

                            }

                            $dados = $sql->fetchAll();
                            foreach($dados as $dado):
                            ?>
                            <tr id="<?=$dado['id']?>">
                                <td scope="col" class="text-center text-nowrap"> <?=$dado['placa_veiculo']?> </td>
                                <td scope="col" class="text-center text-nowrap"> <?=$dado['km_revisao']?> </td>
                                <td scope="col" class="text-center text-nowrap"> <?=date("d/m/Y", strtotime($dado['data_revisao'])); ?> </td>
                                <td scope="col" class="text-center text-nowrap">
                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?= $dado['id']; ?>" data-whatever="@mdo" value="<?= $dado['id']; ?>" name="idordemServico">Visualisar</button>
                                </td>
                            </tr>
                            <!-- INICIO MODAL -->
                            <div class="modal fade" id="modal<?= $dado['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Revisão</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza-revisao.php" method="post">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-1">
                                                            <label for="id" class="col-form-label">ID</label>
                                                            <input type="text" readonly name="id" class="form-control" id="id" value="<?= $dado['id']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="placa" readonly  class="col-form-label">Placa</label>
                                                            <select required name="placa" id="placa" class="form-control">
                                                                <option value="<?=$dado['placa_veiculo']?>"><?=$dado['placa_veiculo']?></option>
                                                                <?php $pecas = $db->query("SELECT * FROM veiculos");
                                                                $pecas = $pecas->fetchAll();
                                                                foreach($pecas as $peca):
                                                                ?>
                                                                <option value="<?=$peca['placa_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="kmRevisao" class="col-form-label">Km da Revisão</label>
                                                            <input type="text" required name="kmRevisao" class="form-control" id="kmRevisao" value="<?= $dado['km_revisao'] ; ?>">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="dataRevisao" class="col-form-label">Data da Revisão</label>
                                                            <input type="date" required name="dataRevisao" class="form-control" id="dataRevisao" value="<?= $dado['data_revisao'] ; ?>">
                                                        </div>
                                                    </div>    
                                            </div>
                                            <div class="modal-footer">
                                                <a href="excluir-revisao.php?idRevisao=<?=$dado['id']; ?>" onclick="return confirm('Confirmar Exclusão?');" class="btn btn-danger"> Excluir </a>
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
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <script>
        $(document).ready(function() {
            $('#veiculo').select2();
        });
    </script>
</body>
</html>