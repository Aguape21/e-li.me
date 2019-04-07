<?php
include_once "variaveis.php";
include_once "funcoes.php";
$link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$chave = substr($link,strlen($site)+1);

if(strpos($chave,"?")!==false)
{
$chave = substr($chave,0,strpos($chave,"?"));
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
   
    
    if(!(filter_var($chave, FILTER_VALIDATE_URL) === false))
    {
       $url = $chave;
       $chave = "";
    }
}



$ip = $_SERVER["REMOTE_ADDR"];
$navegador = $_SERVER['HTTP_USER_AGENT'];


$sql="
INSERT INTO acessos(
url,
chave,
ip,
acesso_em,
navegador
) VALUES (
'[v0]',
'[v1]',
'[v2]',
NOW(),
'[v3]')
";

in_up($sql,[$url,$chave,$ip,$navegador]);


if ($url=="")
{
    $mensagem = "Não encontramos nada em $link";
    include "mensagem.php";
    exit;
}

header("Location: $url"); 