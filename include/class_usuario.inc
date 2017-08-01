<?php  
	/**
	 * Clase para el modelo usuario
	 * La clase tiene seis propiedades, un constructor y cuatro métodos.
	 */
	class usuario{
		/**
		 * Propiedad que se encarga de almacenar id de usuario
		 * @var int
		 * @access private
		 */
		private $idUser;
		/**
		 * Propiedad que se encarga de almacenar nombre de usuario
		 * @var string
		 * @access private
		 */
		private $name;
		/**
		 * Propiedad que se encarga de almacenar email de usuario
		 * @var string
		 * @access private
		 */
		private $email;
		/**
		 * Propiedad que se encarga de almacenar contraseña de usuario
		 * @var string
		 * @access private
		 */
		private $pass;
		/**
		 * Propiedad que se encarga de almacenar fecha de registro de usuario
		 * @var string
		 * @access private
		 */
		private $fecha;
		/**
		 * Propiedad que se encarga de almacenar mensaje
		 * @var string
		 * @access private
		 */
		private $sms;

		/**
		 * Constructor de modelo usuario
		 * @param int $p_idUser 
		 * @param string $p_name 
		 * @param string $p_email 
		 * @param string $p_pass 
		 * @return void
		 */
		function __construct($p_idUser, $p_name, $p_email, $p_pass) {
			$this->idUser=$p_idUser;
			$this->name=$p_name;
			$this->email=$p_email;
			$this->pass=$p_pass;
 		}

 		/**
 		 * Metodo que devuelve mensaje de borrado de usuario
 		 * @return string
 		 */
 		function get_sms(){
 			return $this->sms;
 		}

 		/**
 		 * Metodo que se encarga de insertar un usuario en la base de datos
 		 * Como parametro se pasa la conexion a la base de datos
 		 * @param resource $conn 
 		 * @return void
 		 */
 		function insertUser($conn){
 			try{
 				$sql = "INSERT INTO tb_user (nombre, email, password) 
 			        VALUES ('$this->name', '$this->email', '$this->pass')";
 				$conn->EjecutarConsultaNoDevolucion($sql);
	
 			}catch(Exception $e){
 				throw $e;
 			}			
 		}

 		/**
 		 * Metodo que se encaraga de borrar un usuario y sacar mensaje de borrado
 		 * @param resource $conn 
 		 * @return void
 		 */
 		function deleteUser($conn){
 			$sql = "DELETE FROM tb_user WHERE idUser=$this->idUser";
 			try{
 				$conn->EjecutarConsultaNoDevolucion($sql); 				
 				$this->sms='Usuario ha sido borrado!';
 			}catch(Exception $e){
 				throw $e;
 			}		 	
 		}

 		/**
 		 * Metodo que se encaraga de actualizar datos de un usuario
 		 * @param resource $conn 
 		 * @return void
 		 */
 		function unlockedUser($conn){
 			$sql="UPDATE tb_user SET intentos=0 WHERE idUser=$this->idUser";

 			try{
 				$conn->EjecutarConsultaNoDevolucion($sql); 				
 				$this->sms='Usuario ha sido desbloqueado!';
 			}catch(Exception $e){
 				throw $e;
 			}	
 		}

	} 

?>