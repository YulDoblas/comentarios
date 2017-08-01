<?php  
	session_start();

	include('include/class_conn_bd.inc'); 
	include('include/class_usuario.inc'); 
 	include('include/funciones.inc'); 
 	
	//Inicializacion de las varibles
	$errLogin=$errEmail=$errUser=$nombre=$pass=$email=$userExists='';

	//Creamos una nueva conexion
	$conn = new capaBBDD();

	//Si se ha pulado el boton 'Registrarse'
	if (isset($_POST['login'])){
		//Si el campo 'nombre' o el campo 'password' esten vscios
	 	if(empty($_POST['nombre']) || empty($_POST['pass']) || empty($_POST['email'])){

	 		$errLogin='Debe rellenar todos los campos';

	 	}else{
	 		//Recogemos datos de registro de usuario
			$nombre = test_input($_POST['nombre']);
	 		$pass = test_input($_POST['pass']);
	 		$email = test_input($_POST['email']);

		 	//Guardamos nombre del usuario 
		 	$_SESSION['nombre']=$nombre;

	 		try{
				//Hcemos consulta para comprobar si existe el email introducido
				//por el usuario en la base de datos
				$sql = "SELECT email FROM tb_user WHERE email='$email'";
				//Recogemos datos de la consulta
				$emailBD=$conn->DevolverConjuntoRegistros($sql);

				//Si longitud de array asociativo es cero
				if(count($emailBD)==0){
					//Creamos una nuevo usuario y pasamos datos recogidos anteriormente
					$user=new usuario(-1, $nombre, $email, $pass);
					//Y lo registramos en la base de datos
					$user->insertUser($conn);

					//Recogemos id de usuario registrado
					$sql="SELECT idUser FROM tb_user WHERE email='$email'";
					$idUser=$conn->DevolverConjuntoRegistros($sql);
					//Guardamos id de usuario
		 			$_SESSION['idUser']=$idUser[0]['idUser'];
					//Abtimos la pagina de usuario logueado
					header('Location: commentLogin.php');

				}else{
					$errEmail='Email ya existe!';
				}

		 	}catch(Exception $e){
		 		echo 'ERROR: '.$e;
		 	} 

	 	}
	}

	//Si se ha pulsado enlace 'Entrar como usuario'
	if (isset($_POST['entrarUser'])){

		$userExists= "
			<label>Email</label><br>
			<input type='text' class='ancho100' name='emailUser'>
			<br><br>
			<label>Password</label><br>
			<input type='password' class='ancho100' name='passUser'>
			<br><br>
			<input class='btn_entrar' type='submit' name='entrar' value='Entrar'>";
	}

	//Si se ha pulsado el boton 'Entrar'
	if (isset($_POST['entrar'])){

	 	//Recogemos datos si se ha entrado como administrador
	 	if(test_input($_POST['emailUser'])=='admin@admin.com' && test_input($_POST['passUser'])=='admin'){

	 		header('Location: admin.php');
	 	}else{

	 		//Recojemos datos del usuario
			$emailUser = test_input($_POST['emailUser']);
	 		$passUser = test_input($_POST['passUser']);

	 		//Hacemos la consulta si existe usuario en la base de datos
		 	$sql="SELECT * FROM tb_user WHERE email='".$emailUser."'";
		 	$userBD=$conn->DevolverConjuntoRegistros($sql);

		 	$intentos=$userBD[0]['intentos'];
		 	$idUserBD=$userBD[0]['idUser'];
		 	$nombreUserBD=$userBD[0]['nombre'];
		 	$passBD=$userBD[0]['password'];

		 	if(count($userBD)!=0){
		 		
		 		if($intentos==3){
		 			$errUser= 'Usuario bloqueado';
		 			header('Refresh:3; index.php');

		 		}else{

		 			if($passBD!= $passUser){
						$intentos++;
						$errUser='Usuario incorrecto!';
						echo '';
						$sql="UPDATE tb_user SET intentos=$intentos WHERE idUser=$idUserBD";

						$conn->EjecutarConsultaNoDevolucion($sql);

		 			}else{
		 				//Guardamos id de usuario
		 				$_SESSION['idUser']=$idUserBD;
		 				//Guardamos nombre del usuario 
		 				$_SESSION['nombre']=$nombreUserBD;

		 				$sql="UPDATE tb_user SET intentos=0 WHERE idUser=$idUserBD";
		 				$conn->EjecutarConsultaNoDevolucion($sql);
		 				header('Location: commentLogin.php');
		 			}

		 		}
		 	}
	 	}
	}
		
	//Si se ha pulsado enlace 'Entrar sin registrarse'
	if (isset($_POST['entrarSinLogin'])){
		header('Location: commentSinLogin.php');
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="css/estilos.css">

</head>
<body>
	<div class="container">
		<form method="post" enctype="multipart/form-data">
			<h2>Login</h2>
			<hr>
			<br><br>
			<label>Nombre</label><br>
			<input type="text" class="ancho100" name="nombre" value="<?php echo $nombre; ?>">
			<br><br>

			<label>Email</label><br>
			<input type="text" class="ancho100" name="email" value="<?php echo $email; ?>">
			<span class="red"><?php echo $errEmail; ?></span>
			<br><br>

			<label>Password</label><br>
			<input type="password" class="ancho100" name="pass" value="<?php echo $pass; ?>">
			<span class="red"></span>
			<br><br>

			<input class="btn_reg" type="submit" name="login" value="Registrarse"> <span class="red"><?php echo $errLogin; ?></span>
			<hr>
			<br> 

			<span><?php echo $userExists ?></span>
			<span class='red'><?php echo $errUser ?></span><br><br>

			<input type='submit' class="inputBtnTxt dos" name="entrarUser" value="Entrar como usuario">

			<input type='submit' class="inputBtnTxt" name='entrarSinLogin' value="Entrar sin registrarse"> 
		</form>
	</div>

	<?php 
		//Se cierra la conexion
		$conn->closeConn();
	?>
</body>
</html> 