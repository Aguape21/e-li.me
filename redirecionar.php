<?php
include_once "variaveis.php";
include_once "funcoes.php";
$link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$chave_url = substr($link,strlen($site)+1);

if(strpos($chave_url,"?")!==false)
{
    $chave_url = substr($chave_url,0,strpos($chave_url,"?"));
}

$sql = "SELECT url FROM chaves WHERE upper(chave) = upper('[v0]') AND ativo = 1";


$url="";

$busca = select($sql,[$chave_url]);

if(count($busca)!=0)
{
    $url = $busca[0]['url'];
}

if ($url=="")
{
   
    
    if(!(filter_var($chave_url, FILTER_VALIDATE_URL) === false))
    {
       $url = $chave_url;
       $chave_url = "";
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
navegador,
sessao
) VALUES (
'[v0]',
'[v1]',
'[v2]',
NOW(),
'[v3]',
'[v4]',
'[v5]')
";

in_up($sql,[$url,$chave_url,$ip,$navegador,sessao(),@$_SERVER['HTTP_REFERER']]);


if ($url=="")
{
    $mensagem = "Não encontramos nada em $link";
    include "mensagem.php";
    exit;
}

header("Location: $url"); 