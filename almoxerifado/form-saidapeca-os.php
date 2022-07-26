<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4) {
    $idOs = filter_input(INPUT_GET, 'idOs');
    $os = $db->prepare("SELECT * FROM ordem_servico WHERE idordem_servico = :idOrdemServico");
    $os->bindValue(':idOrdemServico', $idOs);
    $os->execute();
    $os = $os->fetch();

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

    <!-- select02 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

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
                    <h2>Ordem de Serviço</h2>
                </div>
                <div class="menu-mobile">
                    <img src="../assets/images/icones/menu-mobile.png" onclick="abrirMenuMobile()" alt="">
                </div>
            </div>
            <!-- dados exclusivo da página-->
            <div class="menu-principal">
                <form action="add-saida.php" method="post" style="margin-left:10px;">
                    <div id="formulario">
                        <div class="form-row">
                            <div class="form-group col-md-1">
                                <label for="idOrdemServico" class="col-form-label">OS</label>
                                <input type="text" readonly name="idOrdemServico" class="form-control" id="idOrdemServico" value="<?=$idOs?>">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="dataAbertura" class="col-form-label">Data Abertura</label>
                                <input type="date-timelocal" readonly name="dataAbertura" class="form-control" id="dataAbertura" value="<?=date("d/m/Y H:i", strtotime($os['data_abertura'])) ?>">
                            </div>
                            <div class="form-group col-md-2">
                                <label for="placa" readonly  class="col-form-label">Placa</label>
                                <input type="text" readonly name="placa" class="form-control" value="<?=$os['placa']?>">
                            </div>
                            <div class="form-group col-md-7">
                                <label for="problema" class="col-form-label">Descrição Problema</label>
                                <input type="text" readonly required name="problema" class="form-control" id="problema" value="<?=$os['descricao_problema']?>">
                            </div>
                        </div>   
                        <div class="form-row">
                            <div class="form-group col-md-4  ">
                                <label for="causador"> Agente Causador </label>
                                <input type="text" readonly name="causador" id="causador" class="form-control" value="<?=$os['causador']?>">
                            </div>
                            <div class="form-group col-md-3  ">
                                <label for="nf"> NF </label>
                                <input type="text" readonly name="nf" class="form-control" id="nf" value="<?=$os['num_nf']?>">
                            </div>
                            <div class="form-group col-md-5  ">
                                <label for="obs">Obs. </label>
                                <input type="text" readonly name="obs" class="form-control" id="obs" value="<?=$os['obs']?>">
                            </div>
                        </div>  
                        <div class="form-row">
                            <div class="form-group col-md-3  ">
                                <label for="servico"> Serviço </label>
                                <select required name="servico[]" id="servico" class="form-control">
                                    <option value=""></option>
                                    <?php $servicos = $db->query("SELECT * FROM servicos_almoxarifado");
                                    $servicos = $servicos->fetchAll();
                                    foreach($servicos as $servico):
                                    ?>
                                    <option value="<?=$servico['idservicos']?>"><?=$servico['descricao']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-3  ">
                                <label for="peca"> Peça </label>
                                <select required name="peca[]" id="peca" class="form-control">
                                    <option value=""></option>
                                    <?php $pecas = $db->query("SELECT * FROM peca_estoque");
                                        $pecas = $pecas->fetchAll();
                                        foreach($pecas as $peca):
                                        ?>
                                        <option value="<?=$peca['idpeca']?>"><?=$peca['idpeca']." - ". $peca['descricao_peca']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group col-md-2  ">
                                <label for="qtd"> Qtd </label>
                                <input type="text" name="qtd[]" id="qtd" class="form-control" value="">
                            </div>
                            <div class="form-group col-md-2  ">
                                <label for="requisicao"> Nº Requisição de Peça </label>
                                <input type="text" name="requisicao[]" id="requisicao" class="form-control" value="">
                            </div>
                            <div style="margin: auto; margin-left: 0;">
                                <button type="button" class="btn btn-danger" id="add-peca">Adicionar Peça</button>
                            </div>
                        </div>
                    </div>
                    
                    <button class="btn btn-primary"> Lançar</button>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <script>
        
        $(document).ready(function(){
            corretiva = <?=$os['corretiva']?>;
            preventiva = <?=$os['preventiva']?>;
            externa = <?=$os['externa']?>;
            higienizacao = <?=$os['higienizacao']?>;
           
            (corretiva==1)?$('#corretiva').attr("checked", "") :$('#corretiva').removeAttr("checked");
            (preventiva==1)?$('#preventiva').attr("checked", "") :$('#preventiva').removeAttr("checked");
            (externa==1)?$('#externa').attr("checked", "") :$('#externa').removeAttr("checked");
            (higienizacao==1)?$('#higienizacao').attr("checked", "") :$('#higienizacao').removeAttr("checked");
            
        });

    </script>

<!-- Verificando checkboxs preenchidos -->

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){

        var cont = 1;
        $('#add-peca').click(function(){
            cont++;

            $('#formulario').append('<div class="form-row"><div class="form-group col-md-3 "> <label for="servico"> Serviço </label> <select required name="servico[]" id="servico" class="form-control"> <option value=""></option> <?php $servicos = $db->query("SELECT * FROM servicos_almoxarifado"); $servicos = $servicos->fetchAll(); foreach($servicos as $servico): ?> <option value="<?=$servico['idservicos']?>"><?=$servico['descricao']?></option> <?php endforeach; ?> </select> </div> <div class="form-group col-md-3  "> <label for="peca"> Peça </label> <select required name="peca[]" id="peca" class="form-control"> <option value=""></option> <?php $pecas = $db->query("SELECT * FROM peca_estoque"); $pecas = $pecas->fetchAll();foreach($pecas as $peca): ?> <option value="<?=$peca['idpeca']?>"><?=$peca['idpeca']." - ". $peca['descricao_peca']?></option> <?php endforeach; ?> </select> </div> <div class="form-group col-md-2 "> <label for="qtd"> Qtd </label> <input type="text" name="qtd[]" id="qtd" class="form-control"> </div> <div class="form-group col-md-2 "> <label for="requisicao"> Nº Requisição de Peça </label> <input type="text" name="requisicao[]" id="requisicao" class="form-control"> </div>  </div>');

        });

        $('#placa').select2({
            width: '100%',
            dropdownParent:"#modalOrdemServico"
        });
        // $('#placaEdit').select2({
        //     width: '100%',
        //     dropdownParent:"#modalEditar"
        // });
        
    });
</script>
</body>
</html>