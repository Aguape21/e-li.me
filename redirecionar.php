<?php
include_once "variaveis.php";
include_once "funcoes.php";
$link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$chave_url = substr($link,strlen($site)+1);


//remover ação
$er = '/^:\w+:/';

if(preg_match($er, $chave_url, $acao))
{
    $chave_url = preg_replace($er, '', $chave_url);
    $acao = $acao[0];
}
else
{
    $acao="";
}

$chave = $chave_url;

//remover ?xxxxx
$er = '/\?.+$/';
if(preg_match($er, $chave))
{
    $chave = preg_replace($er, '', $chave);
}



$sql = "SELECT url FROM chaves WHERE upper(chave) = upper('[v0]') AND ativo = 1";


$url="";

$busca = select($sql,[$chave]);


if(count($busca)!=0)
{
    $url = $busca[0]['url'];
}


if ($url=="")
{
    
    
    if(!(filter_var($chave_url, FILTER_VALIDATE_URL) === false))
    {
       $url = $chave_url;
       $chave = '';
    }
}

 //registrar acesso
  acesso($url);

//Caso tenha gerado a ação de ir para relatório
if (($acao==':r:')&&($chave==""))
{
    include "relatorio.php";
    exit;
}



//Caso não tenha gerado nenhuma URL
if (($url=="") or ($acao!=""))
{
    $mensagem = "Não encontramos nada em $link";
    include "mensagem.php";
    exit;
}



header("Location: $url"); 