<?php

class Usuario {
    private $idusuario;
    private $usuario;
    private $clave;
    private $nombre;
    private $apellido;
    private $correo;
   

    public function __construct(){
   
    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }
    public function cargarFormulario($request){
        $this->idusuario = isset($request["id"])? $request["id"] : "";
        $this->usuario = isset($request["txtUsuario"])? $request["txtNombre"] : "";
        $this->clave = isset($request["txtClave"])? $request["txtClave"] : "";
        $this->nombre = isset($request["txtNombre"])? $request["txtNombre"] : "";
        $this->apellido = isset($request["txtApellido"])? $request["txtApellido"]: "";
        $this->correo = isset($request["txtCorreo"])? $request["txtCorreo"]: 0;
    }
    public function insertar(){
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        //Arma la query
        $sql = "INSERT INTO usuarios (
            usuario,
            clave,
            nombre, 
            apellido, 
            correo) VALUES (
                '" . $this->usuario ."',
                '" . $this->clave ."',
                '" . $this->nombre ."',
                 '" . $this->apellido ."',
                  '" . $this->correo ."');";
        //Ejecuta la query
        $mysqli->query($sql);
        //Obtiene el id generado por la inserción
        $this->idusuario = $mysqli->insert_id;
        //Cierra la conexión
        $mysqli->close();
    }
    public function actualizar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE usuarios SET
                usuario = '".$this->usuario."',
                clave = '".$this->clave."',
                nombre = '".$this->nombre."',
                apellido = '".$this->apellido."',
                correo = '".$this->correo."',
                WHERE idusuario = " . $this->idusuario;
          
        $mysqli->query($sql);
        $mysqli->close();
    }
    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM usuarios WHERE idusuario = " . $this->idusuario;
        //Ejecuta la query
        $mysqli->query($sql);
        $mysqli->close();
    }
    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
        idusuario,
        clave,
        usuario,
        nombre, 
        apellido,
        correo
        FROM usuarios
        WHERE idusuario = " . $this->idusuario;
        $resultado = $mysqli->query($sql);

        if($resultado){
            //Convierte el resultado en un array asociativo
            $fila = $resultado->fetch_assoc();
            $this->idusuario = $fila["idusuario"];
            $this->clave = $fila["clave"];
            $this->usuario = $fila["usuario"];
            $this->nombre = $fila["nombre"];
            $this->apellido = $fila["apellido"];
            $this->correo = $fila["correo"];
        }
        $mysqli->close();

    }
    public function obtenerPorUsuario($usuario){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
            idusuario,
            clave,
            usuario,
            nombre, 
            apellido,
            correo
        FROM usuarios
        WHERE usuario = '$usuario'";
        $resultado = $mysqli->query($sql);

        if($resultado){
            //Convierte el resultado en un array asociativo
            $fila = $resultado->fetch_assoc();
            $this->idusuario = $fila["idusuario"];
            $this->clave = $fila["clave"];
            $this->usuario = $fila["usuario"];
            $this->nombre = $fila["nombre"];
            $this->apellido = $fila["apellido"];
            $this->correo = $fila["correo"];
        }
        $mysqli->close();

    }
    public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT 
        idusuario,
         clave,
         usuario,
         nombre, 
         apellido, 
         correo
          FROM usuarios";
        $resultado = $mysqli->query($sql);

        $aResultado = array();
        if($resultado){
            //Convierte el resultado en un array asociativo
            while($fila = $resultado->fetch_assoc()){
                $productoAux = new Producto();
                $productoAux->idusuario = $fila["idusuario"];
                $productoAux->usuario = $fila["usuario"];
                $productoAux->clave = $fila["clave"];
                $productoAux->nombre = $fila["nombre"];
                $productoAux->apellido = $fila["apellido"];
                $productoAux->correo = $fila["correo"];
                $aResultado[] = $productoAux;
            }
        }
        return $aResultado;
    }
    public function encriptarClave($clave){
        $claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);
        return $claveEncriptada;
    }
    public function verificarClave($claveIngresada, $claveEnBBDD){
        return password_verify($claveIngresada, $claveEnBBDD);
    }

    

}


?>