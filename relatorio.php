<?php 

include_once "funcoes.php";

$sql = "SELECT
url,
chave,
(SELECT COUNT(id) FROM acessos WHERE acessos.chave = chaves.chave or 
 lower(acessos.chave) LIKE lower(concat(chaves.chave,'?%'))) as conta
FROM chaves WHERE id = [v0]";

$dados = select($sql,[$_GET['id']]);
$dados = $dados[0];





$sql = "SELECT origem, acesso_em, pais, regiao, cidade, navegador FROM `acessos` WHERE lower(chave) = lower('[v0]') or lower(chave) LIKE lower('[v0]?%') ORDER by acesso_em DESC";
$acessos = select($sql,[$dados['chave']]);



?>

<p><strong>URL curta:&nbsp; &nbsp;</strong><?php global $site,$dados; echo $site.'/'.$dados['chave'] ?></p>
<p><strong>Direciona para:&nbsp; &nbsp;</strong> <?php global $dados; echo $dados['url'] ?></p>
<p><strong>Quantidade de acessos:&nbsp; &nbsp;</strong> <?php global $dados; echo $dados['conta'] ?></p>


   <table style="width:100%" border="1px" >
  <tr>
  <th><b>origem</b></th>
  <th><b>acesso em</b></th>
  <th><b>país</b></th>
  <th><b>região</b></th>
  <th><b>cidade</b></th>
  <th><b>navegador</b></th>
  </tr>
  <?php
     global $acessos;

     foreach ($acessos as $acesso) {
      echo '<tr>'.
            '<th>'.$acesso['origem'].'</th>'.
            '<th>'.$acesso['acesso_em'].'</th>'.
            '<th>'.$acesso['pais'].'</th>'.
            '<th>'.$acesso['regiao'].'</th>'.
            '<th>'.$acesso['cidade'].'</th>'.
            '<th> <xx-small> '.$acesso['navegador'].' </xx-small> </th>'.
            '</tr>';
     }
  ?>
</table>
                 </span>  
                </label>