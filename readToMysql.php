<?php

/*******************************************
 *  Arquivo de leitura de um arquivo cache.txt. 
 *  Arquivo cache pode ser substuido por $cache
 *  que define o arquivo da leitura para multiplos
 *  arquivos.
 *  
 *  Premissas assumidas a tabela já existe então será
 *  realizada update, mas a possibilidade de usar o 
 *  insert no código
 * 
 *  Usado prepare para facilitar na entrada dos dados  
 *  $stmt = $con->prepare(UPDATE incricoes SET crm=? WHERE=?");
 *  $stmt->bind_param("ii",$uCRM,$uID);
 *  //i-integer,d double, s string, b -BLOB
 *  // set parameters and execute
 *  $uCRM ="99999";
 *  $uID="1";
 *  $stmt->execute();
 *****************************************/

 //Read from My File Cache.txt
 $fh = fopen("cache.txt", 'r') or
    die("File does not exist or you lack permission to open it");

    echo "<h1>Informações do Arquivo JSON</h1>";
echo "===========================================================<br>";
// while (($buffer = fgets($fh, 4096)) !== false) {echo $buffer;} // Specifies the number of bytes to read

while(! feof($fh)){
    $buffer=fgets($fh)."<br>";
    echo $buffer;

    //$campoArray=array("id","idlive","nome","email","especialidade","senha","empresa","cargo","flag_login","flag_logado","tempo_session","hora_login","token","outro_login","nivel","uf","crm","Telefone","profissao","termo_aceite","cidade","coren","crf");
    $campoArray=array("id","idlive","nome","email","especialidade","senha","empresa","cargo","flag_login","flag_logado","tempo_session","hora_login","token","outro_login","nivel","uf","crm","Telefone","profissao","termo_aceite","cidade","coren","crf");
    foreach ($campoArray as $value){
        if (strpos($buffer,$value) == true){
            $dadoArray=explode(":",$buffer);
            $campo=trim(str_replace(array('\'', '"'), '',$dadoArray[0]));
            $valor=trim(str_replace(array('\'', '"',',','<br>'), '',$dadoArray[1]));
           
            if( $campo=="id"){
                $id=(int)(trim(str_replace(array('\'','"',','),'',$dadoArray[1])));
            }
            if( $campo!="id"){
                //$valor=(int)(str_replace(array('\'', '"',','), '',$dadoArray[1]));
                echo recordDB($campo,$valor,$id);
            }
        };
    }   
}
if (!feof($fh)) {
    echo "Error: unexpected fgets() fail\n";
}
echo "<strong>recordDB</strong>";


// strpos($buffer,)
//echo recordDB ($campo, $dado);
fclose($fh);
//echo $buffer;
?>

<?php
function recordDB($campo,$valor,$id){
echo "<strong>"."****CAMPO:  ".$campo."***</strong><br>";
echo "<strong>"."****VALOR:  ".$valor."***</strong><br>";
echo "<strong>"."****ID: ".$id."***</strong><br>";
// include connection definition
include("conexao.php");
//Connection and Error MSG
    $con= new mysqli($host,$user,$pass,$database);
   
    //$con= new PDO("mysql:host=$host;dbname=$database",$user,$pass);
    if($con->connect_error){ die("falha na conexão".$conn->connect_error);}
    echo "UPDATE incricoes SET `$campo`='$valor' WHERE id=$id <br>";
    //$stmt= $con->prepare("SELECT * FROM incricoes");
    //$stmt=$con->prepare("UPDATE incricoes SET crm=119867 WHERE id=1");
    $stmt=$con->prepare("UPDATE incricoes SET `$campo`='$valor' WHERE id=$id");
    $stmt->execute();
    $result=$stmt->get_result();
    //$outp=$result->fetch_all(MYSQLI_ASSOC);
    //echo $outp;
    //$echo json_encode($outp);
    $stmt->close();
    $con->close();
//}
}


function readDB(){
    //usar performance DB
}

// Multiplos arquivos
//https://www.sitepoint.com/performant-reading-big-files-php/
?>