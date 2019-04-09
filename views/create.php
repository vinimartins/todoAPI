<?php
$data = json_decode(file_get_contents($url));
?>

<h2>Sua URL</h2>
<?php echo $url; ?>
<hr/>

<h4>Para listar todos os items:</h4>
Método: GET<br/>
URL: <?php echo $url; ?>
<hr/>

<h4>Para INSERIR um item:</h4>
Método: POST<br/>
URL: <?php echo $url; ?><br/>
Parâmetros:<br/>
- item (string, obrigatório) -> "Texto do item"
<hr/>

<h4>Para ALTERAR um item:</h4>
Método: PUT<br/>
<?php echo $url; ?>/<?php echo $data->todo[0]->id; ?><br/>
Parâmetros:<br/>
item (string, opcional) -> novo texto<br/>
done (string, opcional) -> sim ou nao
<hr/>

<h4>Para DELETAR um item:</h4>
Método: DELETE<br/>
<?php echo $url; ?>/<?php echo $data->todo[0]->id; ?><br/>