<?php

class Producto {
    private $idproducto;
    private $nombre;
    private $fk_idtipoproducto;
    private $cantidad;
    private $precio;
    private $descripcion;

    public function __construct(){
        $this->cantidad = 0;
        $this->precio = 0.0;

    }

    public function __get($atributo) {
        return $this->$atributo;
    }

    public function __set($atributo, $valor) {
        $this->$atributo = $valor;
        return $this;
    }

    public function cargarFormulario($request){
        $this->idproducto = isset($request["id"])? $request["id"] : "";
        $this->nombre = isset($request["txtNombre"])? $request["txtNombre"] : "";
        $this->fk_idtipoproducto = isset($request["lstTipoProducto"])? $request["lstTipoProducto"]: "";
        $this->cantidad = isset($request["txtCantidad"])? $request["txtCantidad"]: "";
        $this->precio = isset($request["txtPrecio"])? $request["txtPrecio"] : "";
        $this->descripcion = isset($request["txtDescripcion"])? $request["txtDescripcion"] :"";
    }

    public function insertar(){
        //Instancia la clase mysqli con el constructor parametrizado
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        //Arma la query
        $sql = "INSERT INTO productos
                            (nombre,
                            fk_idtipoproducto,
                            cantidad,
                            precio,
                            descripcion) 
                            VALUES 
                            ('" . $this->nombre ."',
                        '" . $this->fk_idtipoproducto ."',
                        '" . $this->cantidad ."',
                        '" . $this->precio ."',
                        '" . $this->descripcion ."');";
                        if (!$mysqli->query($sql)) {
                            printf("Error en query: %s\n", $mysqli->error . " " . $sql);
                        }
        //Ejecuta la query
        $mysqli->query($sql);
        //Obtiene el id generado por la inserción
        $this->idproducto = $mysqli->insert_id;
        //Cierra la conexión
        $mysqli->close();
    }
    public function actualizar(){

        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "UPDATE productos SET
                nombre = '".$this->nombre."',
                fk_idtipoproducto = '".$this->fk_idtipoproducto."',
                cantidad = '".$this->cantidad."',
                precio = '".$this->precio."',
                descripcion =  '".$this->descripcion."'
                WHERE idproducto = " . $this->idproducto;
          
        $mysqli->query($sql);
        $mysqli->close();
    }

    public function eliminar(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "DELETE FROM productos WHERE idproducto = " . $this->idproducto;
        //Ejecuta la query
        $mysqli->query($sql);
        $mysqli->close();
    }

    public function obtenerPorId(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
                    idproducto,
                    nombre,
                    fk_idtipoproducto,
                    cantidad,
                    precio,
                    descripcion
               FROM productos WHERE idproducto = " . $this->idproducto;
        $resultado = $mysqli->query($sql);

        if($resultado){
            //Convierte el resultado en un array asociativo
            $fila = $resultado->fetch_assoc();
            $this->idproducto = $fila["idproducto"];
            $this->nombre = $fila["nombre"];
            $this->fk_idtipoproducto = $fila["fk_idtipoproducto"];
            $this->cantidad = $fila["cantidad"];
            $this->precio = $fila["precio"];
            $this->descripcion = $fila["descripcion"];
        }
        $mysqli->close();

    }

    public function obtenerTodos(){
        $mysqli = new mysqli(Config::BBDD_HOST, Config::BBDD_USUARIO, Config::BBDD_CLAVE, Config::BBDD_NOMBRE);
        $sql = "SELECT
                        idproducto,
                        nombre,
                        fk_idtipoproducto,
                        cantidad,
                        precio,
                        descripcion
                        FROM productos";
        $resultado = $mysqli->query($sql);

        $aResultado = array();
        if($resultado){
            //Convierte el resultado en un array asociativo
            while($fila = $resultado->fetch_assoc()){
                $clienteAux = new Producto();
                $clienteAux->idproducto = $fila["idproducto"];
                $clienteAux->nombre = $fila["nombre"];
                $clienteAux->fk_idtipoproducto = $fila["fk_idtipoproducto"];
                $clienteAux->cantidad = $fila["cantidad"];
                $clienteAux->precio = $fila["precio"];
                $clienteAux->descripcion = $fila["descripcion"];
                $aResultado[] = $clienteAux;
            }
        }
        return $aResultado;
    }

}


?>
