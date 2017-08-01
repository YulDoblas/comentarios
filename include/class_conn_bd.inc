<?php
	/**
	 * Clase para el modelo capaBBDD.
	 * La clase tiene cinco propiedades, un constructor y cinco métodos.
	 * 
	 */
	class capaBBDD{
		/**
		 * Propiedad que se encarga de almacenar nombre del servidor
		 * @var string
		 * @access private
		 */
		private $servername = "localhost";
		/**
		 * Propiedad que se encarga de almacenar nombre de usuario de la base de datos
		 * @var string
		 * @access private
		 */
		private $username = "root";
		/**
		 * Propiedad que se encarga de almacenar contraseña de la base de datos
		 * @var string
		 * @access private
		 */
		private $password = "";
		/**
		 * Propiedad que se encarga de almacenar nombre de la base de datos
		 * @var string
		 * @access private
		 */
		private $dbname = "___mibbdd";
		/**
		 * Propiedad que se encarga de la conexión con la BBDD
		 * @var resource
		 * @access private
		 */
		private $conn="";


		/**
		 * Constructor de modelo capaBBDD
		 * @return void
		 */
		function __construct() { 
			try {
	    		$this->conn = new PDO("mysql:host=$this->servername;dbname=$this->dbname", $this->username, $this->password);
    			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  			}catch(PDOException $e) {

	    		throw $e;
			}
 		}

 		/**
 		 * Metodo que ddevolve la conexión a la bbdd
 		 * @return resource
 		 */
 		function get_Conexion() {
			return $this->conn;
		}

		/**
		 * Metodo que se encarga de ejecutar consultas de insertar, crear, borrar y actualizar datos en la bbdd
		 * @param stirng $sql 
		 * @return void
		 */
 		function EjecutarConsultaNoDevolucion($sql){
 			try {
 				$this->conn->exec($sql);
 			}catch(PDOException $e) {
	    		throw $e;
			}
 		}
 
 		/**
		 * Metodo que se encarga de ejecutar consultas de devolucion de datos en la bbdd
		 * @param stirng $sql 
		 * @return void
		 */
 		function DevolverConjuntoRegistros($sql){
 			try {
	 			$stmt = $this->conn->prepare($sql); 
    			$stmt->execute();
    			//Guarda los resultados en un array asociativo 'clave->valor'
    			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 

    			return $stmt->fetchAll();
    		}catch(PDOException $e) {
    			throw $e;
    		}
 		}

 		/**
		 * Metodo que se encarga de devolver el ultimo id del usuario.
		 * @return int
		 */
 		function UltimoId(){
 			return $this->conn->lastInsertId();
 		}

 		/**
		 * Metodo que se encarga de cerrar la conexión con la bbdd
		 */
 		function closeConn(){
 			$this->conn=null;
 		}
	}	
?>