<?php

session_start();
require("../conexao.php");

if (isset($_SESSION['idUsuario']) && empty($_SESSION['idUsuario']) == false && $_SESSION['tipoUsuario'] != 3 && $_SESSION['tipoUsuario'] != 4) {

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

    <!-- arquivos para datatable -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.css"/>

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
                <div class="icon-exp">
                    <div class="area-opcoes-button">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalOrdemServico" data-whatever="@mdo" name="idOrdemServico">Nova Ordem de Serviço</button>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id='tableOS' class='table table-striped table-bordered nowrap text-center' style="width: 100%;">
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
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/menu.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.10.25/af-2.3.7/date-1.1.0/r-2.2.9/rg-1.1.3/sc-2.0.4/sp-1.3.0/datatables.min.js"></script>
    
    <script>
        $(document).ready(function(){
            $('#tableOS').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_pesq_os.php'
                },
                'columns': [
                    { data: 'idordem_servico' },
                    { data: 'data_abertura' },
                    { data: 'placa' },
                    { data: 'descricao_problema' },
                    { data: 'tipo_manutencao' },
                    { data: 'causador' },
                    { data: 'requisicao_saida' },
                    { data: 'solicitacao_peca' },
                    { data: 'num_nf' },
                    { data: 'obs' },
                    { data: 'situacao' },
                    { data: 'data_encerramento' },
                    { data: 'nome_usuario' },
                    { data: 'acoes' },
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
                "aoColumnDefs":[
                    {'bSortable':false, 'aTargets':[13]}
                ],
            });
        });

        //abrir modal
        $('#tableOS').on('click', '.editbtn', function(event){
            var table = $('#tableOS').DataTable();
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');

            $('#modalEditar').modal('show');

            $.ajax({
                url:"get_single_os.php",
                data:{id:id},
                type:'post',
                success: function(data){
                    var json = JSON.parse(data);
                    $('#idOrdemServico').val(json.idordem_servico);
                    $('#dataAbertura').val(json.data_abertura);
                    $('#placa').val(json.placa);
                    $('#problema').val(json.descricao_problema);
                    $('#manutencao').val(json.tipo_manutencao);
                    $('#causador').val(json.causador);
                    $('#requisicao').val(json.requisicao_saida);
                    $('#solicitacao').val(json.solicitacao_peca);
                    $('#numNf').val(json.num_nf);
                    $('#situacao').val(json.situacao);
                    $('#obs').val(json.obs);                 
                }
            })
        });
    </script>

<!-- modal visualisar e editar -->
<div class="modal fade" id="modalEditar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Ordem de Serviço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="atualiza-ordemservico.php" method="post">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="form-row">
                        <div class="form-group col-md-1">
                            <label for="idOrdemServico" class="col-form-label">ID</label>
                            <input type="text" readonly name="idOrdemServico" class="form-control" id="idOrdemServico" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="dataAbertura" class="col-form-label">Data Abertura</label>
                            <input type="date-timelocal" readonly name="dataAbertura" class="form-control" id="dataAbertura" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="placa" readonly  class="col-form-label">Placa</label>
                            <select required name="placa" id="placa" class="form-control">
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
                            <input type="text" required name="problema" class="form-control" id="problema" value="">
                        </div>
                    </div>   
                    <div class="form-row">
                        <div class="form-group col-md-2">
                            <label for="manutencao" class="col-form-label">Tipo de Manutenção</label>
                            <select name="manutencao" required id="manutencao" class="form-control">
                                <option value="Corretiva">Corretiva</option>
                                <option value="Preventiva">Preventiva</option>
                                <option value="Manutenção Externa">Manutenção Externa</option>
                                <option value="Troca de Óleo">Troca de Óleo</option>
                            </select>
                        </div>
                        <div class="form-group col-md-2">
                            <label for="causador" class="col-form-label">Causador</label>
                            <input type="text" class="form-control" name="causador" id="causador" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="requisicao" class="col-form-label">Nº Requisição de Saída</label>
                            <input type="text" class="form-control" name="requisicao" id="requisicao" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="solicitacao" class="col-form-label">Nº Solicitação de Peças</label>
                            <input type="text" class="form-control" name="solicitacao" id="solicitacao" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="numNf" class="col-form-label">Nº NF</label>
                            <input type="text" class="form-control" name="numNf" id="numNf" value="">
                        </div>
                        <div class="form-group col-md-2">
                            <label for="situacao" class="col-form-label">Situação</label>
                            <select name="situacao" id="situacao" class="form-control">
                                <option value="Encerrada">Encerrada</option>
                                <option value="Em Aberto">Em Aberto</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="obs" class="col-form-label">Observações</label>
                            <input type="text" class="form-control" name="obs" id="obs" value="">
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                    <div class="text-center">
                            <button type="submit" class="btn btn-primary">Atualizar</button>
                        </div>
                    <button type="button"  class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </form> 
            </div>
        </div>
    </div>
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

</body>
</html>