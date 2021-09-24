<?php 

session_start();
require("../../conexao.php");

if(isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario'])==false && $_SESSION['tipoUsuario'] ==1 || $_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario']==10){

    $nomeUsuario = $_SESSION['nomeUsuario'];
    $tipoUsuario = $_SESSION['tipoUsuario'];

    $pagina = (isset($_GET['pagina'])) ? $_GET['pagina'] : 1;
    $selecionar = $db->query("SELECT * FROM entregas_capital ORDER BY data_atual DESC");
    
}else{
    echo "<script>alert('Acesso não permitido');</script>";
    echo "<script>window.location.href='../../index.php'</script>";
}

?>

<!DOCTYPE html>
<html lang="pt-bt">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Entregas</title>
        <link rel="stylesheet" href="../../assets/css/style.css">
        <link rel="stylesheet" href="../../assets/css/bootstrap.min.css">
        <link rel="apple-touch-icon" sizes="180x180" href="../../assets/favicon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="../../assets/favicon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="../../assets/favicon/favicon-16x16.png">
        <link rel="manifest" href="../../assets/favicon/site.webmanifest">
        <link rel="mask-icon" href="../../assets/favicon/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>
        <div class="container-fluid corpo">
            <?php require('../../menu-lateral02.php') ?>
            <!-- Tela com os dados -->
            <div class="tela-principal">
                <div class="menu-superior">
                    <div class="icone-menu-superior">
                        <img src="../../assets/images/icones/despesas.png" alt="">
                    </div>
                    <div class="title">
                        <h2> Entregas Capital </h2>
                    </div>
                    <div class="menu-mobile">
                        <img src="../../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
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
                                    $filtro = $db->query("SELECT carga FROM entregas_capital ORDER BY carga DESC");
                                    if ($filtro->rowCount() > 0):
                                        $dados = $filtro->fetchAll();
                                        foreach ($dados as $dado):

                                    ?>
                                        <option value="<?=$dado['carga'] ?>"> <?=$dado['carga'] ?> </option>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </select>
                                <input type="submit" value="Filtrar" name="filtro" class="btn btn-success">
                            </div>
                        </form>
                        <a href="entregas-xls.php" ><img src="../../assets/images/excel.jpg" alt=""></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-dark table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center text-nowrap">Carga</th>
                                    <th scope="col" class="text-center text-nowrap">Sequência</th>
                                    <th scope="col" class="text-center text-nowrap">Motorista</th>
                                    <th scope="col" class="text-center text-nowrap">Veículo</th>
                                    <th scope="col" class="text-center text-nowrap">Entregas Restante</th>
                                    <th scope="col" class="text-center text-nowrap">Tempo em Rota</th>
                                    <th scope="col" class="text-center text-nowrap">Km Rodado</th>
                                    <th scope="col" class="text-center text-nowrap">Valor Abastecido</th>
                                    <th scope="col" class="text-center text-nowrap">Média de Consumo</th>
                                    <th scope="col" class="text-center text-nowrap">Gastos</th>
                                    <th scope="col" class="text-center text-nowrap">Lançado</th>
                                    <th scope="col" class="text-center text-nowrap">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(isset($_POST['filtro']) && !empty($_POST['carregamento'])){
                                    $carga = filter_input(INPUT_POST, 'carregamento');
                                    $sql = $db->prepare("SELECT * FROM entregas_capital LEFT JOIN motoristas ON entregas_capital.motorista = motoristas.cod_interno_motorista LEFT JOIN veiculos ON entregas_capital.veiculo = veiculos.cod_interno_veiculo LEFT JOIN usuarios ON entregas_capital.usuario = usuarios.idusuarios WHERE carga = :carga");
                                    $sql->bindValue(':carga', $carga);
                                    $sql->execute();

                                    $total = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($total / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                }else{

                                    $sql = $db->query("SELECT * FROM entregas_capital LEFT JOIN motoristas ON entregas_capital.motorista = motoristas.cod_interno_motorista LEFT JOIN veiculos ON entregas_capital.veiculo = veiculos.cod_interno_veiculo LEFT JOIN usuarios ON entregas_capital.usuario = usuarios.idusuarios");

                                    $total = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($total / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT * FROM entregas_capital LEFT JOIN motoristas ON entregas_capital.motorista = motoristas.cod_interno_motorista LEFT JOIN veiculos ON entregas_capital.veiculo = veiculos.cod_interno_veiculo LEFT JOIN usuarios ON entregas_capital.usuario = usuarios.idusuarios LIMIT $paginaInicial, $qtdPorPagina");
                                    
                                }

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado): 
                                    $gastos = $dado['vl_abastec']+$dado['diaria_motorista']+$dado['diaria_auxiliar']+$dado['outros_gastos'];
                                ?>
                                <tr id="<?=$dado['identregas_capital']?>">
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['carga']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['sequencia']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['nome_motorista']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['placa_veiculo']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['qtd_falta']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=date("h:i", strtotime($dado['hr_rota'])); ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['km_rodado']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?="R$ ". str_replace(".",",",$dado['vl_abastec']) ; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=str_replace(".",",",$dado['media_consumo']); ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?="R$ ".str_replace(".",",",$gastos) ; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle"> <?=$dado['nome_usuario']; ?> </td>
                                    <td scope="col" class="text-center text-nowrap align-middle">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?=$dado['identregas_capital']; ?>" data-whatever="@mdo" value="<?=$dado['identregas_capital']; ?>" name="token"> Visualisar </button>     
                                    </td>
                                </tr>
                                <!-- INICIO MODAL Editar-->
                                <div class="modal fade" id="modal<?=$dado['identregas_capital'];?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Entrega</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza-entregas.php" method="post">
                                                    <div class="form-row">
                                                        <div class="form-group col-md-1">
                                                            <label for="idEntrega" class="col-form-label">ID</label>
                                                            <input type="text" readonly class="form-control" name="idEntrega" id="idEntrega" value="<?=$dado['identregas_capital']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label for="data" class="col-form-label">Data</label>
                                                            <input type="date" name="data" id="data" class="form-control" value="<?=$dado['data_atual']?>">
                                                        </div>  
                                                        <div class="form-group col-md-2">
                                                            <label for="carga" class="col-form-label">Carga</label>
                                                            <input required type="text" name="carga" id="carga" class="form-control" value="<?=$dado['carga']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label class="col-form-label" for="sequencia">Sequência</label>
                                                            <input required type="text" required value="<?=$dado['sequencia']?>" name="sequencia" class="form-control" id="sequencia">
                                                        </div>
                                                        <div class="form-group col-md-4">
                                                            <label class="col-form-label" for="motorista">Motorista</label>
                                                            <select required name="motorista" class="form-control" id="motorista">
                                                                <option value="<?=$dado['motorista']?>"><?=$dado['nome_motorista']?></option>
                                                                <?php

                                                                $sql = $db->query("SELECT * FROM motoristas");
                                                                $motoristas = $sql->fetchAll();
                                                                foreach ($motoristas as $motorista):
                                                                ?>
                                                                    <option value="<?=$motorista['cod_interno_motorista'] ?>"><?=$motorista['nome_motorista'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="veiculo">Veículo</label>
                                                            <select required name="veiculo" class="form-control" id="veiculo">
                                                                <option value="<?=$dado['veiculo']?>"><?=$dado['placa_veiculo']?></option>
                                                                <?php

                                                                $sql = $db->query("SELECT * FROM veiculos");
                                                                $motoristas = $sql->fetchAll();
                                                                foreach ($motoristas as $motorista):
                                                                ?>
                                                                    <option value="<?=$motorista['cod_interno_veiculo'] ?>"><?=$motorista['placa_veiculo'] ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="defeito">Carro c/ Defeito</label>
                                                            <select required name="defeito" class="form-control" id="defeito">
                                                                <option value="<?=$dado['defeito_carro']?>"><?=$dado['defeito_carro']?></option>
                                                                <option value="SIM">SIM</option>
                                                                <OPTIon value="NÃO">NÃO</OPTIon>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="nEntregas">Nº Entregas</label>
                                                            <input type="text" name="nEntregas" required id="nEntregas" class="form-control" value="<?=$dado['qtd_total']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="nEntregue">Nº Entregue</label>
                                                            <input type="text" name="nEntregue" required id="nEntregue" class="form-control" value="<?=$dado['qtd_entregue']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="nRestante">Entregas Restante</label>
                                                            <input type="text" name="nRestante" readonly id="nRestante" class="form-control" value="<?=$dado['qtd_falta']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="hrSaida">Hora de Saída</label>
                                                            <input type="time" name="hrSaida"  id="hrSaida" class="form-control" value="<?=$dado['hr_saida']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="hrChegada">Hora de Chegada</label>
                                                            <input type="time" name="hrChegada"  id="hrChegada" class="form-control" value="<?=$dado['hr_chegada']?>">
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="hrRota">Tempo em Rota</label>
                                                            <input type="time" readonly name="hrRota"  id="hrRota" class="form-control" value="<?=$dado['hr_rota']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label class="col-form-label" for="kmSaida">Km Saída</label>
                                                            <input type="text" required name="kmSaida"  id="kmSaida" class="form-control" value="<?=$dado['km_saida']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="kmChegada">Km Chegada</label>
                                                            <input type="text" required name="kmChegada"  id="kmChegada" class="form-control" value="<?=$dado['km_chegada']?>">
                                                        </div>
                                                        <div class="form-group col-md-1">
                                                            <label class="col-form-label" for="kmRodado">Km Rodado</label>
                                                            <input type="text" readonly name="kmRodado"  id="kmRodado" class="form-control" value="<?=$dado['km_rodado']?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="vlAbast">Valor Abastecido</label>
                                                            <input type="text" required name="vlAbast"  id="vlAbast" class="form-control" value="<?=str_replace(".",",",$dado['vl_abastec'])?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="ltAbast">Litros Abastecido</label>
                                                            <input type="text" required name="ltAbast"  id="ltAbast" class="form-control" value="<?=str_replace(".",",",$dado['lt_abastec']) ?>">
                                                        </div>
                                                    </div> 
                                                    <div class="form-row">
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="mediaConsumo">Média Consumo</label>
                                                            <input type="text" readonly name="mediaConsumo"  id="mediaConsumo" class="form-control" value="<?=str_replace(".",",",$dado['media_consumo']) ?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="diariaMot">Diária Motorista(R$)</label>
                                                            <input type="text" required name="diariaMot"  id="diariaMot" class="form-control" value="<?=str_replace(".",",",$dado['diaria_motorista']) ?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="diariaAux">Diária Auxiliar(R$)</label>
                                                            <input type="text" required name="diariaAux"  id="diariaAux" class="form-control" value="<?=str_replace(".",",",$dado['diaria_auxiliar']) ?>">
                                                        </div>
                                                        <div class="form-group col-md-2">
                                                            <label class="col-form-label" for="outrosGastos">Outros Gastos(R$)</label>
                                                            <input type="text" required name="outrosGastos"  id="outrosGastos" class="form-control" value="<?=str_replace(".",",",$dado['outros_gastos']) ?>">
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                            <?php if($tipoUsuario==10): ?>
                                                <a href="excluir-entregas.php?idEntregas=<?=$dado['identregas_capital']; ?>" class="btn btn-danger" onclick="return confirm('Confirmar Exclusão?');"> Excluir </a>
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
                                    echo "<a class='page-link' href='entregas.php?pagina=$paginaAnterior' aria-label='Anterior'>
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
                                echo "<li class='page-item'><a class='page-link' href='entregas.php?pagina=$i'>$i</a></li>";
                            }
                            ?>
                            <li class="page-item">
                                <?php
                                if ($paginaPosterior <= $numPaginas) {
                                    echo " <a class='page-link' href='entregas.php?pagina=$paginaPosterior' aria-label='Próximo'>
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

        <script src="../../assets/js/jquery.js"></script>
        <script src="../../assets/js/bootstrap.bundle.min.js"></script>
        <script src="../../assets/js/menu.js"></script>
        <script src="../../assets/js/jquery.mask.js"></script>
        <script>
            $('#vlAbast').mask('#.##0,00', {reverse: true});
            $('#ltAbast').mask('#.##0,00', {reverse: true});
            $('#diariaMot').mask('#.##0,00', {reverse: true});
            $('#diariaAux').mask('#.##0,00', {reverse: true});
            $('#outrosGastos').mask('#.##0,00', {reverse: true});
            $('#consumo').mask('#.##0,00', {reverse: true})
        </script>
    </body>
</html>