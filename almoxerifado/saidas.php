<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 1 || $_SESSION['tipoUsuario'] == 99) {

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM saida_estoque");
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
                        <img src="../assets/images/icones/almoxerifado.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Saídas</h2>
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
                                    $filtro = $db->query("SELECT * FROM `saida_estoque` LEFT JOIN peca_estoque ON saida_estoque.peca_idpeca = peca_estoque.idpeca ORDER BY descricao_peca ASC");
                                    if ($filtro->rowCount() > 0) {
                                        $dados = $filtro->fetchAll();
                                        foreach ($dados as $dado):

                                    ?>
                                            <option value="<?=$dado['idpeca'] ?>"> <?=$dado['idpeca']. " - ". $dado['descricao_peca'] ?> </option>
                                    <?php

                                        endforeach;
                                    }

                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <div class="area-opcoes-button">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalEntrada" data-whatever="@mdo" name="idpeca">Nova Saída</button>
                        </div>
                        <a href="saidas-xls.php" ><img src="../assets/images/excel.jpg" alt=""></a>
                    </div>
                    <!-- MODAL lançamento de saída -->
                    <div class="modal fade" id="modalEntrada" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Lançar Saída</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="add-saida.php" method="post">
                                        <div class="form-row">
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="dataSaida"> Data Saída</label>
                                                <input type="date" required  name="dataSaida" class="form-control" id="dataSaida">
                                            </div>
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="qtd">Quantidade</label>
                                                <input type="text" required  name="qtd" class="form-control" id="qtd">
                                            </div>
                                            <div class="form-group col-md-4 espaco ">
                                                <label for="peca"> Solicitante </label>
                                                <input type="text" required name="solicitante" id="solicitante" class="form-control">
                                            </div>
                                        </div>    
                                        <div class="form-row">
                                            <div class="form-group col-md-12 espaco ">
                                                <label for="peca"> Peça</label>
                                                <select required name="peca" id="peca" class="form-control">
                                                    <option value=""></option>
                                                    <?php $pecas = $db->query("SELECT * FROM peca_estoque");
                                                    $pecas = $pecas->fetchAll();
                                                    foreach($pecas as $peca):
                                                    ?>
                                                    <option value="<?=$peca['idpeca']?>"><?= $peca['idpeca']." - ". $peca['descricao_peca']?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-4 espaco">
                                                <label for="placa"> Placa </label>
                                                <select required name="placa" id="placa" class="form-control">
                                                    <option value=""></option>
                                                    <option value="Oficina">Oficina</option>
                                                    <?php $pecas = $db->query("SELECT * FROM veiculos");
                                                    $pecas = $pecas->fetchAll();
                                                    foreach($pecas as $peca):
                                                    ?>
                                                    <option value="<?=$peca['placa_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-8 espaco">
                                                <label for="obs"> Observações </label>
                                                <input type="text" name="obs" class="form-control" id="obs">
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
                    <!-- FIM MODAL lançamento de saida-->
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">ID</th>
                                    <th scope="col" class="text-center text-nowrap">Data Saída</th>
                                    <th scope="col" class="text-center text-nowrap">Qtd</th>
                                    <th scope="col" class="text-center text-nowrap">Peça</th>
                                    <th scope="col" class="text-center text-nowrap"> Solicitante </th>
                                    <th scope="col" class="text-center text-nowrap"> Placa </th>
                                    <th scope="col" class="text-center text-nowrap"> Observações </th>
                                    <th scope="col" class="text-center text-nowrap"> Usuário que Lançou </th>
                                    <th scope="col" class="text-center text-nowrap"> Ações  </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php                                

                                if(isset($_POST['filtro']) && !empty($_POST['peca'])){
                                    $peca = filter_input(INPUT_POST, 'peca');
                                    $sql = $db->prepare("SELECT * FROM `saida_estoque` LEFT JOIN peca_estoque ON saida_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON saida_estoque.id_usuario = usuarios.idusuarios WHERE peca_idpeca = :peca");
                                    $sql->bindValue(':peca', $peca);
                                    $sql->execute();
                                   
                                    $totalSaidas = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalSaidas / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;
                                }else{
                                    $sql = $db->query("SELECT * FROM `saida_estoque` LEFT JOIN peca_estoque ON saida_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON saida_estoque.id_usuario = usuarios.idusuarios");

                                    $totalSaidas = $sql->rowCount();
                                    $qtdPorPagina = 20;
                                    $numPaginas = ceil($totalSaidas / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT * FROM `saida_estoque` LEFT JOIN peca_estoque ON saida_estoque.peca_idpeca = peca_estoque.idpeca LEFT JOIN usuarios ON saida_estoque.id_usuario = usuarios.idusuarios ORDER BY idsaida_estoque DESC LIMIT $paginaInicial,$qtdPorPagina");
                                }

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):
                                
                                ?>
                                <tr id="<?=$dado['idsaida_estoque']?>">
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['idsaida_estoque']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?= date("d/m/Y", strtotime($dado['data_saida'])) ; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['qtd']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['descricao_peca']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['solicitante']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?= $dado['placa']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['obs']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['nome_usuario']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$dado['idsaida_estoque']; ?>" data-whatever="@mdo" value="<?=$dado['idsaida_estoque']; ?>" name="idSaida">Visualisar</button>
                                    </td>
                                </tr>
                                <!-- INICIO MODAL Editar-->
                                <div class="modal fade" id="modal<?=$dado['idsaida_estoque']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Saída</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza-saida.php" method="post">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-1">
                                                            <label for="id" class="col-form-label">ID</label>
                                                            <input type="text" readonly name="id" class="form-control" id="id" value="<?= $dado['idsaida_estoque']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-2 ">
                                                            <label for="veiculo" class="col-form-label">Data</label>
                                                            <input type="date" name="data" class="form-control" id="data" value="<?= $dado['data_saida']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label for="qtd"  class="col-form-label">Quantidade</label>
                                                            <input type="text" name="qtd" class="form-control" id="qtd" value="<?= $dado['qtd']; ?>">
                                                        </div>
                                                        <div class="form-group col-md-5">
                                                            <label for="peca"  class="col-form-label">Peça</label>
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
                                                        <div class="form-group col-md-3">
                                                            <label for="solicitante" class="col-form-label">Solicitante</label>
                                                            <input type="text" class="form-control" name="solicitante" id="solicitante" value="<?=$dado['solicitante']?>">
                                                        </div>
                                                    </div>    
                                                    <div class="form-row">
                                                        <div class="form-group col-md-8">
                                                            <label for="obs" class="col-form-label">Observações</label>
                                                            <input type="text" class="form-control" name="obs" id="obs" value="<?=$dado['obs']?>">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label for="placa" class="col-form-label">Placa</label>
                                                            <select required name="placa" id="placa" class="form-control">
                                                                <option value="<?=$dado['placa']?>"><?=$dado['placa']?></option>
                                                                <?php $veiculos = $db->query("SELECT * FROM veiculos");
                                                                $placas = $veiculos->fetchAll();
                                                                foreach($placas as $placa):
                                                                ?>
                                                                <option value="<?=$placa['placa_veiculo']?>"><?=$placa['placa_veiculo']?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="excluir-saida.php?idSaida=<?=$dado['idsaida_estoque']; ?>" class="btn btn-danger" onclick="return confirm('Confirmar Exclusão?');"> Excluir </a>
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
                                echo "<a class='page-link' href='saidas.php?pagina=$paginaAnterior' aria-label='Anterior'>
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
                            echo "<li class='page-item'><a class='page-link' href='saidas.php?pagina=$i'>$i</a></li>";
                        }
                        ?>
                        <li class="page-item">
                            <?php
                            if ($paginaPosterior <= $numPaginas) {
                                echo " <a class='page-link' href='saidas.php?pagina=$paginaPosterior' aria-label='Próximo'>
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
            $('#peca').select2();
        });
        </script>
        
    </body>
</html>