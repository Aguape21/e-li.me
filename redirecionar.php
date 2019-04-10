<?php
include_once "variaveis.php";
include_once "funcoes.php";
$link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$chave_url = substr($link,strlen($site)+1);


if(strpos($chave_url,"?")!==false)
{
    $chave = substr($chave_url,0,strpos($chave_url,"?"));
}
else
{
    $chave = $chave_url;
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
    }
}

 //registrar acesso
  acesso($url);


if ($url=="")
{
    $mensagem = "Não encontramos nada em $link";
    include "mensagem.php";
    exit;
}

header("Location: $url"); 