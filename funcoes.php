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

?>