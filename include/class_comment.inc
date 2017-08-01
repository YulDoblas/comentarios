<?php 
	/**
	 * Clase para el modelo de comentario.
	 * La clase tiene cinco propiedades, un constructor y tres métodos.
	 * Uno para mostrar, otro para insertar y el último borra los comentarios.
	 * @author Julia Doblas <someone@somewhere.com>
	 */
	class comentario{
		/**
		 * Propiedad que se encarga de almacenar id del comentario
		 * @var int
		 * @access private
		 */
		private $idComment;
		/**
		 * Propiedad que se encarga de almacenar id del usuario
		 * @var int
		 * @access private
		 */
		private $idUser;
		/**
		 * Propiedad que se encarga de almacenar comentario de usuario
		 * @var string
		 * @access private
		 */
		private $comment;
		/**
		 * Propiedad que se encarga de almacenar la fecha del comentario
		 * @var int
		 * @access private
		 */
		private $fecha;
		/**
		 * Propiedad que se encarga de almacenar mensaje de borrado del comentario
		 * @var string
		 * @access private
		 */
		private $sms;

		/**
		 * Constructor de modelo cometario
		 * @param int $idComment 
		 * @param int $idUser 
		 * @param string $comment 
		 * @return void
		 */
		function __construct($idComment, $idUser, $comment) {
			$this->idComment=$idComment;
			$this->idUser=$idUser;
			$this->comment=$comment;
 		}

 		/**
 		 * Metodo que devuelve mensaje de borrado del comentario
 		 * @return string
 		 */
 		function get_sms(){
 			return $this->sms;
 		}

 		/**
 		 * Metodo que se encarga de insertar un comentario en la base de datos
 		 * Como parametro se pasa la conexion a la base de datos
 		 * @param resource $conn 
 		 * @return void
 		 */
 		function insertComment($conn){
 			try{
 				$sql = "INSERT INTO tb_comment (idUser, comment) 
 			        VALUES ($this->idUser,'$this->comment')";
 				$conn->EjecutarConsultaNoDevolucion($sql);
	
 			}catch(Exception $e){
 				throw $e;
 			}			
 		}

 		/**
 		 * Metodo que se encaraga de borrar un cometario y sacar mensaje de borrado
 		 * @param resource $conn 
 		 * @return void
 		 */
 		function deleteComment($conn){
 			$sql = "DELETE FROM tb_comment WHERE idComment=$this->idComment";
 			try{
 				$conn->EjecutarConsultaNoDevolucion($sql); 				
 				$this->sms='Commentario ha sido borrado!';
 			}catch(Exception $e){
 				throw $e;
 			}		 	
 		}
	}

?>