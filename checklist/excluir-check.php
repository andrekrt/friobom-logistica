<?php

session_start();
require("../conexao.php");

$id = filter_input(INPUT_GET, 'id');

if(isset($id) && empty($id) == false ){
    
    $sql = $db->prepare("DELETE FROM checklist WHERE idchecklist = :id");
    $sql->bindValue(':id',$id);
    
    if($sql->execute()){
        echo "<script>alert('Excluído com Sucesso!');</script>";
        echo "<script>window.location.href='checklists.php'</script>";
    }else{
        echo "erro";
    }
    
}else{
    header("Location:solicitacoes.php");
}

?>