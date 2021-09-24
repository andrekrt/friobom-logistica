<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] == 99 || $_SESSION['tipoUsuario']==4) {

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
            <?php require('../menu-lateral.php') ?>
            <div class="tela-principal">
                <div class="menu-superior">
                    <div class="icone-menu-superior">
                        <img src="../assets/images/icones/ocorrencia.png" alt="">
                    </div>
                    <div class="title">
                        <h2>Ocorrências</h2>
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
                                    <th scope="col" class="text-center text-nowrap">ID</th>
                                    <th scope="col" class="text-center text-nowrap">Motorista</th>
                                    <th scope="col" class="text-center text-nowrap">Data Ocorrência</th>
                                    <th scope="col" class="text-center text-nowrap">Tipo Ocorrência</th>
                                    <th scope="col" class="text-center text-nowrap">Veículo</th>
                                    <th scope="col" class="text-center text-nowrap">Carregamento</th>
                                    <th scope="col" class="text-center text-nowrap">Provas Ocorrência</th>
                                    <th scope="col" class="text-center text-nowrap">Anexo Advertência</th>
                                    <th scope="col" class="text-center text-nowrap">Anexo Laudo</th>
                                    <th scope="col" class="text-center text-nowrap">Valor Total</th>
                                    <th scope="col" class="text-center text-nowrap">Resolvido</th>
                                    <th scope="col" class="text-center text-nowrap">Lançado por</th>
                                    <th scope="col" class="text-center text-nowrap">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                
                                if(isset($_POST['filtro']) && !empty($_POST['motorista'])){

                                    $motorista = filter_input(INPUT_POST,'motorista');
                                    $sql = $db->prepare("SELECT * FROM `ocorrencias`LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista LEFt JOIN usuarios ON ocorrencias.usuario_lancou = usuarios.idusuarios WHERE ocorrencias.cod_interno_motorista = :motorista ");
                                    $sql->bindValue(':motorista', $motorista);
                                    $sql->execute();

                                    $totalOcorrencias = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalOcorrencias / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                }else{
                                   
                                    $sql = $db->query("SELECT * FROM `ocorrencias`LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista LEFt JOIN usuarios ON ocorrencias.usuario_lancou = usuarios.idusuarios");

                                    $totalOcorrencias = $sql->rowCount();
                                    $qtdPorPagina = 10;
                                    $numPaginas = ceil($totalOcorrencias / $qtdPorPagina);
                                    $paginaInicial = ($qtdPorPagina * $pagina) - $qtdPorPagina;

                                    $sql = $db->query("SELECT * FROM `ocorrencias`LEFT JOIN motoristas ON ocorrencias.cod_interno_motorista = motoristas.cod_interno_motorista LEFt JOIN usuarios ON ocorrencias.usuario_lancou = usuarios.idusuarios LIMIT $paginaInicial, $qtdPorPagina");

                                }

                                $dados = $sql->fetchAll();
                                foreach($dados as $dado):
                                ?>
                                <tr id="<?=$dado['idocorrencia']?>">
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['idocorrencia'];?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['nome_motorista'];?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=date("d/m/Y", strtotime($dado['data_ocorrencia'])) ;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['tipo_ocorrencia'] ;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['placa'] ;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['num_carregamento'] ;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['img_ocorrencia']==""?"Sem Provas de Ocorrências":"<a href='uploads/$dado[idocorrencia]/ocorrencias' target='_blank'>Anexo</a>" ;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"><?=$dado['img_advertencia']==""?"Sem Anexo":"<a href='uploads/$dado[idocorrencia]/advertencias' target='_blank'>Anexo</a>" ;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['img_laudo']==""?"Sem Anexo":"<a href='uploads/$dado[idocorrencia]/laudos' target='_blank'>Anexo</a>" ;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?="R$ ". str_replace(".",",", $dado['vl_total_custos']) ;?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['situacao'];?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> <?=$dado['nome_usuario'];?>  </td>
                                    <td scope="col" class="text-center text-nowrap"> 
                                    <?php if($_SESSION['tipoUsuario']==4): ?>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal<?= $dado['idocorrencia']; ?>" data-whatever="@mdo" value="<?= $dado['idocorrencia']; ?>" name="idocorrencia">Visualisar</button>
                                    <?php endif; ?>
                                    </td>
                                </tr>
                                <!-- INICIO MODAL -->
                                <div class="modal fade" id="modal<?= $dado['idocorrencia']; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Ocorrência</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="atualiza.php" enctype="multipart/form-data" method="post">
                                                    <div class="form-row">
                                                        <div class="form-group espaco col-md-1">
                                                            <label for="idOcorrencia" >ID</label>
                                                            <input type="text" readonly name="idOcorrencia" class="form-control" id="idOcorrencia" value="<?= $dado['idocorrencia']; ?>">
                                                        </div>
                                                        <div class="form-group espaco col-md-4">
                                                            <label for="motorista" >Motorista</label>
                                                            <select name="motorista" id="motorista" class="form-control">
                                                                <option value="<?=$dado['cod_interno_motorista']?>"> <?=$dado['nome_motorista']?> </option>
                                                                <?php
                                                                    $motoristas = $db->query("SELECT cod_interno_motorista, nome_motorista FROM motoristas ORDER BY nome_motorista ASC");
                                                                    if($motoristas->rowCount()>0){
                                                                        $motoristas = $motoristas->fetchAll();
                                                                        foreach($motoristas as $motorista):
                                                                ?>
                                                                    <option value="<?=$motorista['cod_interno_motorista']?>"><?=$motorista['nome_motorista']?></option>
                                                                <?php            
                                                                        endforeach;
                                                                    }

                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group col-md-2 espaco">
                                                            <label for="data" readonly  >Data da Ocorrência</label>
                                                            <input type="date" name="data" class="form-control" id="data" value="<?= $dado['data_ocorrencia']; ?>">
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label for="grupo">Tipo Ocorrência</label>
                                                            <select name="tipo" required id="tipo" class="form-control">
                                                                <option value="<?=$dado['tipo_ocorrencia']?>"><?=$dado['tipo_ocorrencia']?></option>
                                                                <option value="Má Condução">Má Condução</option>
                                                                <option value="Mau Comportamento">Mau Comportamento</option>
                                                            </select>
                                                        </div>
                                                        <div class="input-group mb-3 form-grupo col-md-3 centro-file">
                                                            <div class="custom-file">
                                                                <input type="file" name="anexoOcorrencia[]" multiple="multiple" class="custom-file-input" id="anexoOcorrencia" >
                                                                <label for="anexoOcorrencia" class="custom-file-label">Adicionar Ocorrências</label>
                                                                <input type="hidden" name="ocorrenciaPadrao" value="<?=$dado['img_ocorrencia']?>">
                                                            </div>
                                                        </div>
                                                    </div>    
                                                    <div class="form-row">
                                                        <div class="form-group espaco col-md-2">
                                                            <label for="placa">Veículo</label>
                                                            <select name="placa" id="placa" class="form-control">
                                                                <option value="<?=$dado['placa']?>"><?=$dado['placa']?></option>
                                                                <?php
                                                                    $placas = $db->query("SELECT placa_veiculo FROM veiculos ORDER BY placa_veiculo ASC");
                                                                    if($placas->rowCount()>0){
                                                                        $placas = $placas->fetchAll();
                                                                        foreach($placas as $placa):
                                                                ?>
                                                                    <option value="<?=$placa['placa_veiculo']?>"><?=$placa['placa_veiculo']?></option>
                                                                <?php            
                                                                        endforeach;
                                                                    }

                                                                ?>
                                                            </select>
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label for="carregamento">Carregamento</label>
                                                            <input type="text" name="carregamento" id="carregamento" class="form-control" value="<?=$dado['num_carregamento']?>">
                                                        </div>
                                                        <div class="form-group espaco col-md-2">
                                                            <label for="advertencia">Houve Advertência</label>
                                                            <select name="advertencia" required id="advertencia" class="form-control">
                                                                <option value="<?=$dado['advertencia']?>"><?=$dado['advertencia']?'SIM':'NÃO';?></option>
                                                                <option value=1>SIM</option>
                                                                <option value=0>NÃO</option>
                                                            </select>
                                                        </div>
                                                        <div class="input-group mb-3 form-grupo col-md-3 centro-file">
                                                            <div class="custom-file">
                                                                <input  type="file" name="anexoAdvertencia[]" multiple="multiple" class="custom-file-input" id="anexoAdvertencia" >
                                                                <label for="anexoAdvertencia" class="custom-file-label">Adicionar Advertência</label>
                                                                <input type="hidden" name="advertenciaPadrao" value="<?=$dado['img_advertencia']?>">
                                                            </div>
                                                        </div>
                                                        <div class="form-group col-md-1 espaco">
                                                            <label for="laudo">Laudos</label>
                                                            <select name="laudo" required id="laudo" class="form-control">
                                                                <option value="<?=$dado['laudo']?>"><?=$dado['laudo']?'SIM':'NÃO'; ?></option>
                                                                <option value=1>SIM</option>
                                                                <option value=0>NÃO</option>
                                                            </select>
                                                        </div>
                                                        <div class="input-group mb-3 form-grupo col-md-2 centro-file">
                                                            <div class="custom-file">
                                                                <input type="file" name="anexoLaudo[]" multiple="multiple" class="custom-file-input" id="anexoLaudo" >
                                                                <label for="anexoLaudo" class="custom-file-label">Adicionar Laudos</label>
                                                                <input type="hidden" name="laudoPadrao" value="<?=$dado['img_laudo']?>">
                                                            </div>
                                                        </div>
                                                    </div> 
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6 espaco">
                                                            <label for="descricaoProblema">Descrição do Problema</label>
                                                            <textarea name="descricaoProblema" id="descricaoProblema" class="form-control" rows="5"><?=$dado['descricao_problema']?></textarea>
                                                        </div>
                                                        <div class="form-group col-md-6 espaco">
                                                            <label for="descricaoCusto">Descrição dos Custos</label>
                                                            <textarea name="descricaoCusto" id="descricaoCusto" class="form-control" rows="5"><?=$dado['descricao_custos']?></textarea>
                                                        </div> 
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6 espaco">
                                                            <label for="vlTotal">Valor Total dos Custos</label>
                                                            <input type="text" name="vlTotal" id="vlTotal" class="form-control" value="<?= str_replace(".",",",$dado['vl_total_custos']) ?>">
                                                        </div> 
                                                        <div class="form-group col-md-6 espaco">
                                                            <label for="situacao">Resolvido</label>
                                                            <select name="situacao" class="form-control" id="situacao">
                                                                <option value="<?=$dado['situacao']?>"><?=$dado['situacao']?></option>
                                                                <option value="SIM">SIM</option>
                                                                <option value="NÃo">NÃO</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                            <?php if($dado['usuario_lancou']==$_SESSION['idUsuario']): ?>
                                                <a href="excluir.php?idOcorrencia=<?=$dado['idocorrencia']; ?>" class="btn btn-danger" onclick="return confirm('Certeza que deseja excluir?')"> Excluir </a>
                                                <button type="submit" name="atualizar" class="btn btn-primary">Atualizar</button>
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
                                    echo "<a class='page-link' href='ocorrencias.php?pagina=$paginaAnterior' aria-label='Anterior'>
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
                                echo "<li class='page-item'><a class='page-link' href='ocorrencias.php?pagina=$i'>$i</a></li>";
                            }
                            ?>
                            <li class="page-item">
                                <?php
                                if ($paginaPosterior <= $numPaginas) {
                                    echo " <a class='page-link' href='ocorrencias.php?pagina=$paginaPosterior' aria-label='Próximo'>
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