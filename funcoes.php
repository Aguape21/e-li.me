<?php

include_once "variaveis.php";


//======[ Facilita a criação de select]
function select($sql,$variaveis)
{

global $Servidor, $Usuario,$Senha , $Banco;

   // Create connection
$conn = new mysqli($Servidor, $Usuario,$Senha , $Banco);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 


//Fazer Correção das variáveis e montar código

$i=0;
while ($i<count($variaveis))
{
   if (is_string($variaveis[$i]))
   {
      $variaveis[$i]=mysqli_real_escape_string($conn,$variaveis[$i]);
   }

   $sql = str_replace('['.'v'.$i.']', $variaveis[$i], $sql);

   $i++;
}


$result = $conn->query($sql);

$saida=[];

if ($result->num_rows > 0)
{
    // output data of each row
    while($row = $result->fetch_assoc()) {
    $saida[] = $row;
    }
} 
$conn->close();

return $saida;

}


function in_up($sql,$variaveis)
{
   global $Servidor, $Usuario,$Senha , $Banco;

   // Create connection
$conn = new mysqli($Servidor, $Usuario,$Senha , $Banco);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

//correção da codificação
mysqli_set_charset($conn,"utf8");

//Fazer Correção das variáveis e montar código

$i=0;
while ($i<count($variaveis))
{
   if (is_string($variaveis[$i]))
   {
      $variaveis[$i]=mysqli_real_escape_string($conn,$variaveis[$i]);
   }

   // Fornece: <body text='black'>
   $sql = str_replace('['.'v'.$i.']', $variaveis[$i], $sql);

   $i++;
}


if ($conn->query($sql) === TRUE) {
   $saida = $conn->insert_id;
} else {
   $saida = null;
}

$conn->close();

return $saida;

}


function buscarUrl($chave)
{
   $r = select("SELECT url FROM chaves WHERE upper(chave) = upper('[v0]')",[$chave]);
   if (count($r)===0)
   {
      return "";
   }
   else
   {
      return $r[0]['url'];
   }

}


function int2chave($int)
{
   global $chave_gerador;
   $tamanho = strlen($chave_gerador);
   

   while ($int > 0)
   {
      $v=$int % $tamanho;

      $res = $chave_gerador[$v].@$res;

      $int=($int-$v)/$tamanho;
   }

  
   return $res;
}

function palavra()
{
   $silabas = ['ba','be','bi','bo','bu','ca',
               'ce','ci','co','cu','da','de',
               'di','do','du','fa','fe','fi',
               'fo','fu','ga','ge','gi','go',
               'gu','gua','gue','gui','ja','je',
               'ji','jo','ju','la','le',
               'li','lo','lu','ma','me','mi',
               'mo','mu','na','ne','ni','no',
               'nu','pa','pe','pi','po','pu',
               'qua','que','qui','quo','ra',
               're','ri','ro','ru','sa','se',
               'si','so','su','ta','te','ti',
               'to','tu','va','ve','vi','vo',
               'vu','xa','xi','xu','za','ze',
               'zi','zo','zu','lha', 'lhe', 'lhi',
               'lho', 'lhu', 'nha', 'nhe', 'nhi',
               'nho', 'nhu','a','e','i','o','u'];

   $tamanho = rand ( 6 , 12);

   $silabas_id = array_rand($silabas, $tamanho);

   $saida='';
   foreach ($silabas_id as $id) {
      $saida =  $silabas[$id].$saida;
   }

   return $saida;

}

//Criar uma sessão
function sessao()
{

   if (isset($GLOBALS["sessao"]))
   {
      return $GLOBALS["sessao"];
   }
   elseif (isset($_COOKIE['sessao']))
   {
      $GLOBALS["sessao"] = $_COOKIE['sessao'];
      return $GLOBALS["sessao"];
   }
   else
   {
      $pl = palavra();
      setcookie('sessao', $pl );
      $GLOBALS["sessao"] = $pl;
      return $GLOBALS["sessao"];
   }
  
}

//registra o acesso
function acesso($url_)
{

      global $site,$geo_api;

      $link = "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      $chave_url = substr($link,strlen($site)+1);
      $ip = $_SERVER["REMOTE_ADDR"];
      $navegador = $_SERVER['HTTP_USER_AGENT'];
      $origem = @$_SERVER['HTTP_REFERER'];
      $sessao = sessao();


      //acessar geo api
            // Make and decode POST request:
            $geo_api_=str_replace('[ip]', $ip, $geo_api);
            $ch = curl_init($geo_api_);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);
            curl_close($ch);

            $geo_api_ = json_decode($data);
            
            $pais='';
            $regiao='';
            $cidade='';
            $latitude=0;
            $longitude=0;

            if (isset($geo_api_->country_name))
            {
                $pais=$geo_api_->country_name;
            }
            if (isset($geo_api_->region_name))
            {
               $regiao=$geo_api_->region_name;
            }
            if (isset($geo_api_->city))
            {
               $cidade=$geo_api_->city;
            }
            if (isset($geo_api_->latitude))
            {
               $latitude=$geo_api_->latitude;
            }
            if (isset($geo_api_->longitude))
            {
               $longitude=$geo_api_->longitude;
            }

      //acessar geo api


      $sql="
      INSERT INTO acessos(
      url,
      chave,
      ip,
      acesso_em,
      navegador,
      sessao,
      origem,
      pais,
      regiao,
      cidade,
      latitude,
      longitude
      ) VALUES (
      '[v0]',
      '[v1]',
      '[v2]',
      NOW(),
      '[v3]',
      '[v4]',
      '[v5]',
      '[v6]',
      '[v7]',
      '[v8]',
       [v9],
       [v10]
      )";

      in_up($sql,[$url_,
                  $chave_url,
                  $ip,
                  $navegador,
                  $sessao,
                  $origem,
                  $pais,
                  $regiao,
                  $cidade,
                  $latitude,
                  $longitude]);

}

?>