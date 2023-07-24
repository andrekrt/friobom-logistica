<?php include('../conexao.php');
$id = $_POST['id'];
$sql = "SELECT * FROM denegadas LEFT JOIN usuarios ON denegadas.usuario = usuarios.idusuarios WHERE token='$id'";
$query = $db->query($sql);
$registros = $query->fetchAll(PDO::FETCH_ASSOC);
// echo json_encode($row);

foreach($registros as $registro):
?>
<div class="form-row">
    <input type="hidden" value="<?=$registro['token']?>">
    <div class="form-group col-md-2">
        <label for="id" class="col-form-label">ID</label>
        <input type="text" readonly name="id[]" class="form-control" id="id" value="<?=$registro['id_denegadas']?>">
    </div>
    <div class="form-group col-md-3 ">
        <label for="carga" class="col-form-label">Carga</label>
        <input type="text" name="carga[]" class="form-control" id="carga" value="<?=$registro['carga']?>">
    </div>
    <div class="form-group col-md-3">
        <label for="pedido"  class="col-form-label">NF</label>
        <input type="text" name="nf[]" class="form-control" id="pedido" value="<?=$registro['nf']?>">
    </div>
    <div class="form-group col-md-4">
        <label for="status"  class="col-form-label">Status</label>
        <select name="status[]" id="status" class="form-control">
            <option value="<?=$registro['situacao']?>"><?=$registro['situacao']?></option>
            <option value="Confirmado">Confirmado</option>
            <option value="Aguardando Confirmação">Aguardando Confirmação</option>
        </select>
    </div>
</div>  
<?php endforeach;?>
