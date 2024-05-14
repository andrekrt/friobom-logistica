<?php

session_start();
require("../conexao.php");

$idModudulo = 11;
$idUsuario = $_SESSION['idUsuario'];

$sqlPerm = $db->prepare("SELECT COUNT(*) FROM permissoes WHERE idusuario=:usuario AND idmodulo=:modulo");
$sqlPerm->bindValue(':usuario', $idUsuario, PDO::PARAM_INT);
$sqlPerm->bindValue(':modulo', $idModudulo,PDO::PARAM_INT);
$sqlPerm->execute();
$result = $sqlPerm->fetchColumn();

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && ($result>0)  ) {
    $filial = $_SESSION['filial'];

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
                <form action="atualiza-ordemservico.php" method="post" style="margin-left:10px;">
                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <label for="idOrdemServico" class="col-form-label">ID</label>
                            <input type="text" readonly name="idOrdemServico" class="form-control" id="idOrdemServico" value="<?=$idOs?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="dataAbertura" class="col-form-label">Data Abertura</label>
                            <input type="date-timelocal" readonly name="dataAbertura" class="form-control" id="dataAbertura" value="<?=date("d/m/Y H:i", strtotime($os['data_abertura'])) ?>">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="placa" readonly  class="col-form-label">Placa</label>
                            <select required name="placa" id="placaEdit" class="form-control">
                                <?php $pecas = $db->query("SELECT * FROM veiculos WHERE filial=$filial");
                                $pecas = $pecas->fetchAll();
                                foreach($pecas as $peca):
                                ?>
                                <option value="<?=$os['placa']?>"><?=$os['placa']?></option>
                                <option value="<?=$peca['placa_veiculo']?>"><?=$peca['placa_veiculo']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-7">
                            <label for="problema" class="col-form-label">Descrição Problema</label>
                            <input type="text" required name="problema" class="form-control" id="problema" value="<?=$os['descricao_problema']?>">
                        </div>
                    </div>   
                    <div class="form-row">
                        <div class="form-group  form-check check-os">
                            <input type="checkbox" class="form-check-input"  id="corretiva" name="corretiva">
                            <label class="form-check-label" for="corretiva">Corretiva</label>
                        </div>
                        <div class="form-group   form-check check-os">
                            <input type="checkbox" checked="<?php if($os['corretiva']): echo "checked"; endif; ?>" class="form-check-input" id="preventiva" name="preventiva">
                            <label class="form-check-label" for="preventiva">Preventiva</label>
                        </div>
                        <div class="form-group   form-check check-os">
                            <input type="checkbox" class="form-check-input" id="externa" name="externa">
                            <label class="form-check-label" for="externa">Manutenção Externa</label>
                        </div>
                        <div class="form-group  form-check check-os">
                            <input type="checkbox" class="form-check-input" id="higienizacao" name="higienizacao">
                            <label class="form-check-label" for="higienizacao">Higienização</label>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-3">
                            <label for="causador" class="col-form-label">Causador</label>
                            <input type="text" class="form-control" name="causador" id="causador" value="<?=$os['causador']?>">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="numNf" class="col-form-label">Nº NF</label>
                            <input type="text" class="form-control" name="numNf" id="numNf" value="<?=$os['num_nf']?>">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="situacao" class="col-form-label">Situação</label>
                            <select name="situacao" id="situacao" class="form-control">
                                <option value="<?=$os['situacao']?>"><?=$os['situacao']?></option>
                                <option value="Encerrada">Encerrada</option>
                                <option value="Em Aberto">Em Aberto</option>
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="obs" class="col-form-label">Observações</label>
                            <input type="text" class="form-control" name="obs" id="obs" value="<?=$os['obs']?>">
                        </div>
                    </div>
                    <?php 
                    $sqlServicos = $db->prepare("SELECT idsaida_estoque, servicos_almoxarifado.descricao as nomeServico, servico, peca_idpeca,peca_reparo.descricao as nomePeca, qtd, requisicao_saida, os  FROM saida_estoque LEFT JOIN servicos_almoxarifado ON saida_estoque.servico = servicos_almoxarifado.idservicos LEFT JOIN peca_reparo ON saida_estoque.peca_idpeca = peca_reparo.id_peca_reparo WHERE os =:os");
                    $sqlServicos->bindValue(':os', $idOs);
                    $sqlServicos->execute();
                    $dados = $sqlServicos->fetchAll();
                    foreach($dados as $dado){
                     
                    ?>
                    <div class="form-row">
                        <input type="hidden" name="idsaida[]" value="<?=$dado['idsaida_estoque']?>">
                        <div class="form-group col-md-3  ">
                            <label for="servico"> Serviço </label>
                            <select required name="servico[]" id="servico" class="form-control">
                                <option value="<?=$dado['servico']?>"><?=$dado['nomeServico']?></option>
                                <?php $servicos = $db->query("SELECT * FROM servicos_almoxarifado WHERE filial=$filial");
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
                                <option value="<?=$dado['peca_idpeca']?>"><?=$dado['nomePeca']?></option>
                                <?php $pecas = $db->query("SELECT * FROM peca_reparo WHERE filial=$filial");
                                    $pecas = $pecas->fetchAll();
                                    foreach($pecas as $peca):
                                    ?>
                                    <option value="<?=$peca['id_peca_reparo']?>"><?=$peca['id_peca_reparo']." - ". $peca['descricao']?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group col-md-2  ">
                            <label for="qtd"> Qtd </label>
                            <input type="text" name="qtd[]" id="qtd" class="form-control" value="<?=$dado['qtd']?>">
                        </div>
                        <div class="form-group col-md-2  ">
                            <label for="requisicao"> Nº Requisição de Peça </label>
                            <input type="text" name="requisicao[]" id="requisicao" class="form-control" value="<?=$dado['requisicao_saida']?>">
                        </div>
                        <div class="form-group col-md-2"  style="margin-top: auto; margin-left: 0;">
                            <a class="btn btn-danger" onclick="excluirPeca(<?=$dado['idsaida_estoque']?>)">Excluir</a>
                        </div>
                    </div>
                    <?php }?>
                    <button class="btn btn-primary"> Atualizar </button>
                    <a class="btn btn-warning" href="form-saidapeca-os.php?idOs=<?=$idOs?>">Adicionar Peça/Serviço</a>
                </form>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>
    <!-- sweert alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        function excluirPeca(id){
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Você realmente deseja excluir este Serviço e Peça?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Se o usuário confirmar, redirecione para a página de exclusão
                    window.location.href = 'excluir-saida.php?idSaida=' + id;
                }
            });
        }

    </script>

<!-- Verificando checkboxs preenchidos -->

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function(){

        var cont = 1;
        $('#add-peca').click(function(){
            cont++;

            $('#formulario').append('<div class="form-row"><div class="form-group col-md-3 espaco "> <label for="servico"> Serviço </label> <select required name="servico[]" id="servico" class="form-control"> <option value=""></option> <?php $servicos = $db->query("SELECT * FROM servicos_almoxarifado"); $servicos = $servicos->fetchAll(); foreach($servicos as $servico): ?> <option value="<?=$servico['idservicos']?>"><?=$servico['descricao']?></option> <?php endforeach; ?> </select> </div> <div class="form-group col-md-3 espaco "> <label for="peca"> Peça </label> <select required name="peca[]" id="peca" class="form-control"> <option value=""></option> <?php $pecas = $db->query("SELECT * FROM peca_estoque"); $pecas = $pecas->fetchAll();foreach($pecas as $peca): ?> <option value="<?=$peca['idpeca']?>"><?=$peca['idpeca']." - ". $peca['descricao_peca']?></option> <?php endforeach; ?> </select> </div> <div class="form-group col-md-2 espaco "> <label for="qtd"> Qtd </label> <input type="text" name="qtd[]" id="qtd" class="form-control"> </div> <div class="form-group col-md-2 espaco "> <label for="requisicao"> Nº Requisição de Peça </label> <input type="text" name="requisicao[]" id="requisicao" class="form-control"> </div>  </div>');

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