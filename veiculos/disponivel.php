<?php 
$status = filter_input(INPUT_GET, 'status');

switch ($status) {
    case 'viagem':
        $status = "Em Viagem";
        break;
    case 'interna':
        $status="Manutenção Interna";
        break;
    case 'externa':
        $status="Manutenção Externa";
        break;
    case "disponivel":
        $status="Disponível";
        break;
}

?>
<div class="menu-superior">
    <div class="icone-menu-superior">
        <img src="../assets/images/icones/veiculo.png" alt="">
    </div>
    <div class="title">
        <h2>Frota Disponível</h2>
    </div>
</div>
<div class="menu-principal">
    
    <div class="table-responsive">
        <table id='tableVeiculos' class='table table-striped table-bordered nowrap' style="width: 100%;">
            <thead>
                <tr>
                    <th scope="col" class="text-center text-nowrap" >  Código Veículo </th>
                    <th scope="col" class="text-center text-nowrap">Tipo Veículo</th>
                    <th scope="col" class="text-center text-nowrap">Categoria</th>
                    <th scope="col" class="text-center text-nowrap">Marca</th>
                    <th scope="col" class="text-center text-nowrap">Placa Veículo</th>
                </tr>
            </thead>
            
        </table>
    </div>
</div>
    
    <script>
        
        $(document).ready(function(){
            var status = <?php echo json_encode($status)?>;
            $('#tableVeiculos').DataTable({
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url':'proc_disponivel.php?status='+status
                },
                'columns': [
                    { data: 'cod_interno_veiculo' },
                    { data: 'tipo_veiculo' },
                    { data: 'categoria' },
                    {data: 'marca'},
                    { data: 'placa_veiculo' },
                ],
                "language":{
                    "url":"//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Portuguese-Brasil.json"
                },
            });
        });
    </script>