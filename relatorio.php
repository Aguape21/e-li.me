<?php 

include_once "funcoes.php";

if ($chave != "")
{
   $sql = "SELECT origem, acesso_em, pais, regiao, cidade, navegador FROM `acessos` WHERE lower(chave) = lower('[v0]') or lower(chave) LIKE lower('[v0]?%') ORDER by acesso_em DESC";
   $acessos = select($sql,[$chave]);
}
else
{
   $sql = "SELECT origem, acesso_em, pais, regiao, cidade, 
   navegador FROM `acessos` WHERE chave = '[v0]' ORDER by acesso_em DESC";
   $acessos = select($sql,[$url]);
}



?>

<p><strong>URL curta:&nbsp; &nbsp;</strong><?php global $site,$chave; echo $site.'/'.$chave; ?></p>
<p><strong>Direciona para:&nbsp; &nbsp;</strong> <?php global $url; echo $url;?></p>
<p><strong>Quantidade de acessos:&nbsp; &nbsp;</strong> <?php global $acessos; echo count($acessos); ?></p>


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