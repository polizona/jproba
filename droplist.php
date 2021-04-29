<?php    
    require_once 'config/global.php';
    $tabla = $_POST['tabla'];        
    $conexion = mysqli_connect(HOSTNAME,USER,PASSWORD,BD);
    
    if(!$conexion){
        echo "<script>alert('No fue posible conectarse con la Base de Datos, matando página');</script>";
        die();
    }
    
    $consulta = "SHOW COLUMNS FROM polizona_142.".$tabla.";";
    $resultado = mysqli_query($conexion,$consulta);

    $cadena = '<label for="campo1">Campo 1</label><select class="form-control" id="campo1" name="campo1">';
    $cadena2 = '<label for="campo2">Campo 2</label><select class="form-control" id="campo2" name="campo2">';

    while($registro = mysqli_fetch_row($resultado)){
        $cadena = $cadena."<option value=".$registro[0].">".$registro[0]."</option>";    
        $cadena2 = $cadena2."<option value=".$registro[0].">".$registro[0]."</option>";                                    
    }
    
    // SHOW tables FROM polizona_mercado;  SHOW COLUMNS FROM polizona_mercado.abono; Field
    mysqli_close($conexion);  
    echo $cadena."</select><br/><br/>";    
    echo $cadena2."</select><br/>";
?>


