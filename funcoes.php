<?php

include_once "variaveis.php";


//======[ Facilita a criação de select]
function select($sql)
{

global $Servidor, $Usuario,$Senha , $Banco;

   // Create connection
$conn = new mysqli($Servidor, $Usuario,$Senha , $Banco);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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


function insert($sql)
{
   global $Servidor, $Usuario,$Senha , $Banco;

   // Create connection
$conn = new mysqli($Servidor, $Usuario,$Senha , $Banco);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
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
   $r = select("SELECT url FROM chaves WHERE upper(chave) = upper('$chave')");
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

?>