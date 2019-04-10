<?php
include_once "variaveis.php";
include_once "funcoes.php";


//registrar acess
acesso('');



if(@$_GET['md5']==md5(@$_GET['id'].$secreto))
{
    //pegar dados do acesso
    $id = $_GET['id'];
    $sql = "SELECT  url, chave,ativo FROM chaves WHERE id = [v0]";
    $busca = select($sql,[$id]);
    $busca = $busca[0];
    $chave = $busca['chave'];
    $url = $busca['url'];
    $ativo = $busca['ativo'];
   
   
    if ($ativo==1)
    {
        include 'relatorio.php';
        exit;
    }


    $sql="UPDATE chaves SET ativo=1,ativado_em=NOW(),sessao_ativado='[v1]' WHERE id = [v0]";
    in_up($sql,[$id,sessao()]);



}
else
{
    $mensagem="Não foi possível aprovar essa solicitação!";
    include "mensagem.php";
    exit;
}

?><!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Redutor de URL e-licencie</title>

	<link rel="stylesheet" href="/assets/demo.css">
	<link rel="stylesheet" href="/assets/form-basic.css">

</head>


    

    <div class="main-content">

        <!-- You only need this form and the form-basic.css -->

        <form class="form-basic" method="post" action="#">

            <div class="form-title-row">
                <h1>Redutor de URL e-licencie</h1>
            </div>

            <div class="form-row">
                <label>
                    <span>Essa é sua nova URL para <?php
                    global $url;
                    echo $url;
                    ?></span>
                    <input value="<?php
                    global $chave,$site,$prot;
                    echo $prot.$site.'/'.$chave;
                    ?>" 
                    type="text">
                </label>
			</div>

            <div class="form-row">
               
            </div>

        </form>

    </div>

</body>

</html>
