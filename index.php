<?php 
/*

Página de solicitação de redução de url

*/

include 'funcoes.php';

//registrar acesso
acesso('');



$erro=array();
//verificar se foi enviado algum post
if(isset($_POST["url"])||isset($_POST["chave"])||isset($_POST["email"]))
{
    
   

   //Validar dados

//===recaptcha




    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = $chave_secreta;
    $recaptcha_response = @$_POST['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha_url = $recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response;
   
    $ch = curl_init($recaptcha_url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);

    $recaptcha = json_decode($data);
    
    // Take action based on the score returned:
    if (@$recaptcha->score < 0.5) {
        $erro[]='Olá Robô! Vamos ser amigos?';
    } 
//recaptcha



    $url = @$_POST["url"];

    if(filter_var($url, FILTER_VALIDATE_URL) === FALSE)
    {
       $erro[]="Formato da URL não está válido";
    }
 
    $chave = @$_POST["chave"];

    if (($chave!="") && (buscarUrl($chave)!=""))
    {
        $erro[]="Essa chave já está sendo utilizada";
    }


    //verificar caracteres válidos
    if($chave!="")
    {
        global $chave_permite;
        $chave_min = strtolower($chave);
       $i=0;
       while ($i<strlen($chave_min))
       {
           $pos = strpos($chave_permite, $chave_min[$i]);
           if ($pos === false)
           {
               $erro[]="Essa chave possui letras não permitidas";
               break; 
           } 
           $i++;
       }
    }

   

    $email = @$_POST["email"];
    if(filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE)
    {
        $erro[]="Formato do e-mail não está válido";
    } 

 

        //deu dudo certo criar nova url
    if (count($erro)==0)
    {
        //Criar a chave
        if ($chave=="")
        {   
            $ch_int = select("SELECT MAX(chave_int) as maior FROM chaves",[]);
            $ch_int = $ch_int[0]['maior'];


            $busca = "###";

            while($busca!="")
            {
                
                $ch_int++;
                $chave = int2chave($ch_int);
                $busca = buscarUrl($chave);
                
            }
        }

        

       if(!isset($ch_int))
        {
            $ch_int = "null";
        }

        //Criar novo registro
        $sql = "INSERT INTO `chaves`(
             `url`, 
             `chave`, 
             `chave_int`, 
             `email`, 
             `ativo`, 
             `criado_em`,
             `sessao_criado`
             ) VALUES (
            '[v0]',
            '[v1]',
             [v2],
            '[v3]',
            0,
            NOW(),
            '[v4]'
            )";

          $id = in_up($sql,[$url,$chave,$ch_int,$email,sessao()]);

          if ($id!=null)
          {

            global $secreto,$site;

            $md5 = md5($id.$secreto);
            
            $corpo =
"
Olá!

Clique no link abaixo para aprovar a criação da URL curta para $url.

Caso não tenha feito nenhuma solicitação, desconsiderar essa mensagem.

Aprovar aqui << $prot$site/aprovacao.php?id=$id&md5=$md5  >>.

Att,
Equipe e-licencie 

";

              mail($email, 'Aprovação de URL curta', $corpo);


            $mensagem="Foi ao e-mail $email um link para aprovação!";
            include "mensagem.php";
              exit;
          }

    }

}




?><!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Redutor de URL e-licencie</title>

	<link rel="stylesheet" href="assets/demo.css">
	<link rel="stylesheet" href="assets/form-basic.css">

    <script src="https://www.google.com/recaptcha/api.js?render=<?php global $chave_site; echo $chave_site; ?>"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('<?php global $chave_site; echo $chave_site; ?>', { action: 'contact' }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
    </script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?php global $id_Google_Analytics; echo $id_Google_Analytics; ?>"></script>
        <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', '<?php global $id_Google_Analytics; echo $id_Google_Analytics; ?>');
        </script>


</head>


    

    <div class="main-content">

        <!-- You only need this form and the form-basic.css -->

        <form class="form-basic" method="post" action="">

            <div class="form-title-row">
                <h1>Redutor de URL e-licencie</h1>
            </div>
            <div class="form-row">
                  <span style="color: red;"><?php
                 
                 global $erro;
                  
                  $i=0;
                  while ($i < count($erro))
                  {
                      echo $erro[$i];
                      echo '<br>';
                      $i++;
                  }


                  ?></span>
            </div>            
            

            <div class="form-row">
                <label>
                    <span>URL para reduzir</span>
                    <input value="<?php 
                    global $url;
                    echo $url;
                    ?>" type="text" name="url" placeholder="http://e-licencie.com.br">
                </label>
			</div>
			
			<div class="form-row">
                <label>
                    <span>(Opcional) Chave: e-li.me/</span>
                    <input value="<?php 
                    global $chave;
                    echo $chave;
                    ?>" type="text" name="chave" placeholder="nome-curto">
                </label>
            </div>

            <div class="form-row">
                <label>
                    <span>Email para validação</span>
                    <input value="<?php 
                    global $email;
                    echo $email;
                    ?>" name="email" placeholder="eli@e-licencie.com.br">
                </label>
            </div>

           
 

            <div class="form-row">
                <button type="submit">Reduzir URL</button>

            </div>

            <input type="hidden" name="recaptcha_response" id="recaptchaResponse">

        </form>
        
    </div>
   
</body>

<p>e-li.me est&aacute; disponivel para download no <a href="http://e-li.me/Github" target="_blank" rel="noopener"><strong>Github</strong></a></p>

</html>
