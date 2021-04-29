<?php    
    require_once 'config/global.php';
    $Salida=''; $respuesta=''; $Parametros=''; $query1 =''; $query2=''; 
    $SuperQuery1 =''; $SuperQuery2 =''; $jsonQuery1 = '';$jsonQuery2 = '';
    $Tablas = array();

    $conexion = mysqli_connect(HOSTNAME,USER,PASSWORD,BD);
    if(!$conexion){
        echo "<script>alert('No fue posible conectarse con la Base de Datos, matando página');</script>";
        die();
    }
         
    $consulta = "SHOW tables FROM polizona_mercado;";
    $resultado = mysqli_query($conexion,$consulta);

    while($registro = mysqli_fetch_array($resultado)){				        
        array_push($Tablas, $registro['Tables_in_polizona_mercado']);             
    }
    
    //SHOW tables FROM polizona_mercado;  T COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'usuario'                    
    
    if(isset($_POST['Generar'])){
        $tabla = $_POST['tabla'];
        $campo1 = $_POST['campo1'];
        $campo2 = $_POST['campo2'];

        $Codigo = " superquery:- read(T), read(C), read(C2), superquery1(T,C), superquery2(T,C,C2).
                    superquery1(T,C):- write('select '), write(C), write(', '), sentenciacount(T), write(') as probabilidad from '), write(T), write(' where '), sentenciain(T,C), write(' group by '),write(C), write(';:').
                    superquery2(T,C,C2):- write('select '), write(C), write(', '), write(C2), write(', '),sentenciacount(T), write(') as Probabilidad from '), write(T), write(' where '), sentenciain(T,C), write(' AND '), sentenciain(T,C2), write(' group by '), write(C2), write(','), write(C), write(' order by '), write(C), write(', '), write(C2), write(';:').
                    sentenciacount(T):- write('count(*)/(select count(*) from '), write(T).
                    sentenciain(T,C):- write(C), write( ' in(select distinct '),write(C), write(' from '), write(T),write(')').
                    :-superquery.";
        
        $Parametros = $tabla.". ".$campo1.". ".$campo2."." ;
        
        $lenguaje = '19';

        //Parametros que pide la API de Rextester
        $datos = array(  "LanguageChoice" => $lenguaje,
                        "Program" => "$Codigo",
                        "Input" => "$Parametros",
                        "CompilerArgs" => "" 
                     );

        $ch = curl_init();                                                      // Iniciamos la conexión        
        curl_setopt($ch, CURLOPT_URL,"https://rextester.com/rundotnet/api");    // definimos la URL a la que hacemos la petición        
        curl_setopt($ch, CURLOPT_POST, TRUE);                                   // indicamos el tipo de petición: POST            
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($datos));         // definimos cada uno de los parámetros
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                         // recibimos la respuesta y la guardamos en una variable
        $respuesta = curl_exec ($ch);                      
        curl_close ($ch);                                                       //cerramos la sesión cURL
        
        $respuesta = json_decode($respuesta , true);        
        $Salida = $respuesta['Result'];

        $queries = explode(':',$Salida);
        $query1 = $queries[0];
        $query2 = $queries[1];
        
    
        $consulta=$queries[0];                
        $resultado=mysqli_query($conexion,$consulta);
        while($registro=mysqli_fetch_array($resultado)){
            $result["$campo1"]=$registro[0];
            $result["Probabilidad"]=$registro[1];
            $jsonQuery1['jproba'][]=$result;
            
            $SuperQuery1.="<tr> <td scope='row'>".$registro[0]."</td><td>".$registro[1]."</td></tr>";
        }        
        $jsonQuery1 = json_encode($jsonQuery1,JSON_NUMERIC_CHECK);        


        $consulta=$queries[1];            
        $resultado=mysqli_query($conexion,$consulta);
        while($registro=mysqli_fetch_array($resultado)){
            $result["$campo1"]=$registro[0];
            $result["$campo2"]=$registro[1];
            $result["Probabilidad"]=$registro[2];
            $jsonQuery2['jproba'][]=$result;
            
            $SuperQuery2.="<tr><td scope='row'>".$registro[0]."</td><td>".$registro[1]."</td><td>".$registro[2]."</td></tr>";
        }        
        $jsonQuery2 = json_encode($jsonQuery2,JSON_NUMERIC_CHECK);
    }
    mysqli_close($conexion);
    require_once 'Vista/agrupacion.view.php'; 
?>
