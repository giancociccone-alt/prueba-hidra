<?php

    require_once './conexion.php';

    $conexion = conexionDB();

    $contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

    if ($contentType === "application/json") {

        $content = trim(file_get_contents("php://input"));

        $datosEntradas = preg_split("/[,]+/",$content);

        $titulo = $datosEntradas[0];
        $imagen = $datosEntradas[1];
        $contenido = $datosEntradas[2];
        $descripcion = $datosEntradas[3];
        $usuario = $datosEntradas[4];

    }

    if (!isset( $_FILES ) 
       || !isset( $_FILES["'".$divido[4]."'"]) 
       || !isset( $_FILES["'".$divido[4]."'"]"'".$divido[1]."'"] ) 
       || !isset($_FILES["'".$divido[4]."'"]['tmp_name'])
    ){
        header('HTTP/ 400 Entrada Fallida');
        echo json_encode(array("estado" => "false", "mensaje" => "No se ha insertado la entrada en la BBDD"));
    }

     if( isset( $_FILES ) && isset( $_FILES["'".$divido[4]."'"]) && isset( $_FILES["'".$divido[4]."'"]"'".$divido[1]."'"] ) && isset($_FILES["'".$divido[4]."'"]['tmp_name'])){
         //Colocar el nombre del usuario aqui
         mkdir("./users/manu",0777,false);

         if( ! is_uploaded_file($_FILES["'".$divido[4]."'"]['tmp_name'])){
             echo "ERROR: El fichero encontrado no fue procesado por la subida correctamente";
             exit;
         }

        // Poner la url en la base de datos carpeta_usuario/imagen.extension
         $source = $_FILES["'".$divido[4]."'"]['tmp_name'];
        // Colocar el nombre del usuario aqui
         $destination = __DIR__.'/users/manu/'.$_FILES["'".$divido[4]."'"]"'".$divido[1]."'"];

         if( is_file($destination)){
             echo "Error: Ya existe almacenado un fichero con ese nombre";
             @unlink(ini_get('upload_tmp_dir').$_FILES["'".$divido[4]."'"]['tmp_name']);
             exit;
         }

         if( ! @move_uploaded_file($source, $destination)){
             echo "Error: No se ha podido mover el fichero enviado a la carpeta de destino";
             @unlink(ini_get('upload_tmp_dir').$_FILES["'".$divido[4]."'"]['tmp_name']);
             exit;
         }

         echo "Fichero subido correctamente a: ".$destination;

    
    // $foto = base64_encode(file_get_contents(addslashes($divido[1])));
    
    //$imagenReducido = imagejpeg($divido[1],$divido[1],20);
    
    // $imagen = './users/manu/'.$_FILES[$divido[4]][$divido[1]];
        $sql = 'INSERT INTO entrada (titulo,imagen, entrada, descripcion,usuario) VALUES (:titulo,:imagen,:entrada,:descripcion,:usuario)';
    
        $result = $conexion->prepare($sql);
        $result->bindParam(':titulo', $titulo);
        $result->bindParam(':imagen', $imagen);
        $result->bindParam(':entrada', $contenido);
        $result->bindParam(':descripcion', $descripcion);
        $result->bindParam(':usuario', $usuario);
        $result->execute();

        if($result->rowCount() == 1){
            header('HTTP/ 200 Entrada Exitosa');
            echo json_encode(array("estado" => "true", "mensaje" => "Entrada insertada exitosamente en la BBDD"));
            exit();
        }

        

    // }

?>!