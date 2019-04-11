<?php
include_once "variaveis.php";
include_once "funcoes.php";

$chave_url = substr( $_SERVER['REQUEST_URI'], 1 );


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



//Solicitar link de acesso ao relatório
if (($acao==':r:')&&($chave!=""))
{
    global $secreto,$site;

    $sql = "SELECT id, email FROM `chaves` WHERE chave = '[v0]'";
    $dd1 = select($sql,[$chave]);

  
    if (isset($dd1[0]['id']))
    {
        $id = $dd1[0]['id'];
        $email = $dd1[0]['email'];

        $md5 = md5($id.$secreto);

        $corpo =
        "
        Olá!
        
        Clique no link abaixo para acessar relatórios de acessos de $site/$chave .
        
        Ver relatório aqui << $prot$site/aprovacao.php?id=$id&md5=$md5  >>.

        Caso não tenha feito nenhuma solicitação, desconsiderar essa mensagem.


        Att,
        Equipe e-licencie

        
        ";
        enviar_email($email,'Link para acesso à relatório.',$corpo);

        $mensagem="Foi ao e-mail castrado um link para acesso ao relatório.";
        
        include "mensagem.php";
          exit;

    }


}


//Caso não tenha gerado nenhuma URL
if (($url=="") or ($acao!=""))
{
    $mensagem = "Não encontramos nada em $site/$chave_url";
    include "mensagem.php";
    exit;
}



header("Location: $url"); 