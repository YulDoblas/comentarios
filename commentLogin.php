<?php 
	session_start();

	include('include/class_conn_bd.inc'); 
	include('include/class_comment.inc');
	include('include/funciones.inc');

	//Creamos una nueva conexion
	$conn = new capaBBDD();

	$nombreUsuario=$idUser="";

	$idUser = $_SESSION['idUser'];

	if(isset($_SESSION['nombre'])){
		$nombreUsuario=$_SESSION['nombre'];
	}

	if(isset($_POST['comentar'])){
		if(!empty($_POST['comentario'])){
			//Rcogemos comentario introducido 
			$commentUser= test_input($_POST['comentario']);

			//Creamos un nuevo obj de comentario
			$comentario=new comentario(-1, $idUser, $commentUser);
			//Inssertamos comentario en la base de datos
			$comentario->insertComment($conn);
		}
	} 
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset='UTF-8'>
	<title>Comentarios Usuarios Logueados</title>
	<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head>
<body> 
	<div class="containerUser">
		<h2 class="h2User">Bienvenid@ <?php echo $nombreUsuario; ?></h2>
		<a class="salida" href="salir.php">Cerrar sesión</a>
		<br>
		<hr><br>
		  
		<div class='comment'>
			<form method="post">
				<textarea name="comentario" placeholder="Agrega un comentario..."></textarea>
				<br>
				<input class="inputUser" type="submit" name="comentar" value="Comentar">
			</form>
		</div>

		<div class='divBuscar'>
			<!-- Se crean opciones dinamicamente con los usuarios que han hecho algun comentario -->
			<form method="post" name='usuarios'>
				<!-- SELECCION DE USUARIO -->
				<select name="selUser" class="select" onchange="document.usuarios.submit()">
					<option value='selecciona'>Selecciona usuario</option>
					<!-- PHP(Recogemos nombres sin repetir de los usuarios que han comentado algo) -->
					<?php 
						$sql="SELECT nombre FROM tb_user INNER JOIN tb_comment ON tb_comment.idUser=tb_user.idUser GROUP BY nombre;";

						$users=$conn->DevolverConjuntoRegistros($sql) 

					?>
					<!-- PHP(Crea dinamicamente opciones de usuarios a seleccionar ) -->
					<?php for ($i=0; $i<count($users) ; $i++) { ?>
						<?php foreach ($users[$i] as $key => $value){?>
								<option value='<?php echo $value; ?>'>
									<?php echo $value; ?>
								</option>
						<?php }?>
					<?php } ?>
				</select>

				<!-- BUSCAR COMENTARIO -->
				<input class="txtBuscar" type='text' placeholder="Buscar comentarios..." name='palabra'>
				<input class="btnBuscar" type="submit" name="buscar" value="Buscar">

				<!-- FECHA -->
				<label>desde</label>
				<input class="txtBuscar" type='text' placeholder="dd .mm.yyyy" name='desde'>

				<label>hasta</label>
				<input class="txtBuscar" type='text' placeholder="dd.mm.yyyy" name='hasta'>

				<input type="submit" name="verFecha" value="Ver">

				<!-- CUENTA -->
				<input type='submit' class="inputBtnTxt dos" name="ajustes" value="Mi cuenta">
			</form>
		</div>

		<!-- PHP(Saca todos los comentarios del usuario seleccinado) -->
		<?php 	
			//Si select no esta vacio y no esta pulsado boton 'buscar'
			if(!empty($_POST['selUser']) && !isset($_POST['buscar']) && !isset($_POST['ajustes'])&& !isset($_POST['verFecha'])){

				$selectUser=$_POST['selUser'];
					
				//Recogemos todos los comentarios de la persona seleccionada 
				$sql="SELECT U.nombre, C.comment, DATE_FORMAT( C.fecha, '%h:%m:%s - %d.%m.%Y') as fecha FROM tb_comment AS C INNER JOIN tb_user AS U ON C.idUser=U.idUser WHERE U.nombre='".$selectUser."' ORDER BY C.fecha;";
				
				$userComments=$conn->DevolverConjuntoRegistros($sql);

				$_SESSION['comentarios']=$userComments;

				//Total de comentarios de usuario seleccionado
				echo "<h4 style='display:inline-block; margin-right:20px;'>".count($userComments)." comentarios</h4>
						<form style='display:inline-block;' method='post'>
							<input style='cursor:pointer' type='submit' class='inputBtnTxt' name='pdf' formtarget='_blank' value='Descargar en PDF'>
						</form>
					<hr><br>";
				echo"<div class='todoComments todoCommentsAr'>";
					//var_dump($userComments);
					foreach ($userComments as $reg) {
						echo "<div class='usuarios'>";
						echo "<h4 class='nomuser'>".$selectUser."</h4>";
						echo "<span class='fecha'>".$reg['fecha']."</span>";
						echo "<p>".$reg['comment']."</p>";
						echo "</div>";
					}
				echo"</div>";
			}
		?>
 
		<!-- PHP(Descargar PDF)-->
		<?php
			if(isset($_POST['pdf'])){ 
				header('Location: pdf.php');
			}
		?> 

		<!-- PHP(Saca todos los comentarios por palabra introducida) -->
		<?php
			if(isset($_POST['buscar'])){

				if(empty($_POST['palabra'])){
		 			echo '<p class="red errores">Debe introducir una palabra.</p>';
		 		}else{
		 			//Guardamos la palabra introducida
					$palabra=$_POST['palabra'];

					/*Sacamos todos los comentarios que hay para comprobar 
					si tienen la palabra introducida*/
					$sql="SELECT C.comment, U.nombre, DATE_FORMAT( C.fecha, '%h:%m:%s - %d.%m.%Y') as fecha FROM tb_comment AS C INNER JOIN tb_user AS U ON C.idUser=U.idUser WHERE C.comment LIKE '%$palabra%'";

					$comentarios=$conn->DevolverConjuntoRegistros($sql);


					echo"<h4>".count($comentarios)." comentario/s encontrado/s.</h4><hr><br>";

					echo"<div class='todoComments todoCommentsAr'>";
						//var_dump($userComments);
						foreach ($comentarios as $reg) {
							echo "<div class='usuarios'>";
							echo "<h4 class='nomuser'>".$reg['nombre']."</h4>";
							echo "<span class='fecha'>".$reg['fecha']."</span>";
							echo "<p>".$reg['comment']."</p>";
							echo "</div>";
						}
					echo"</div>";
				}
			}	 


			$sql = "SELECT count(*) AS total FROM tb_comment;";
		
			$allCommentBD=$conn->DevolverConjuntoRegistros($sql);
			$total=$allCommentBD[0]['total'];
		?>
		
		<!-- PHP(Ajuastes de cuenta) -->
		<?php
			
			if(isset($_POST['ajustes'])){

				$sql="SELECT nombre, password FROM tb_user WHERE idUser=".$idUser;

				$datosUser=$conn->DevolverConjuntoRegistros($sql);
				$smsCambio=''; 

				echo"<h4>Ajustes</h4><hr><br>";

				echo"<div class='todoComments'>";
					echo "<span class='red'>".$smsCambio."</span>";
					echo "<form method='post'>";
					//Dibujamos la tabla
					echo"<table>";
						echo "<tr>
								<th>Nombre</th>
								<th>Password</th>
								<th></th>
							<tr>";
						
						$x=1;

						foreach ($datosUser as $clave => $valor) {
							echo "<tr>";
							
							foreach ($valor as $key => $value) {

								echo"<td>
								<input type='text' name='cambiar".$x."' value='".$value."'>
								<input type='submit' name='borrar".$x."'  onclick='Update(".$idUser.");' value='cambiar'>
								</td>";

								$x++;
							}
							
							echo "<td>
									<input type='submit' class='inputBtnTxt' name='nocambiar' value='Cerrar'>
								 </td>";

						}
					echo"</table>";
					echo "</form>";
				echo"</div>";	
			}

			if(isset($_POST['borrar1'])){
				$nombre=$_POST['cambiar1'];
				$sql="UPDATE tb_user SET nombre='$nombre' WHERE idUser=".$idUser;
				$conn->EjecutarConsultaNoDevolucion($sql);
				$smsCambio='El nombre ya se ha cambiado.';
				
				$sql="SELECT nombre, password FROM tb_user WHERE idUser=".$idUser;

				$datosUser=$conn->DevolverConjuntoRegistros($sql);

				echo"<h4>Ajustes</h4><hr><br>";

				echo"<div class='todoComments'>";
					echo "<span class='red'>".$smsCambio."</span>";
					echo "<form method='post'>";
					//Dibujamos la tabla
					echo"<table>";
						echo "<tr>
								<th>Nombre</th>
								<th>Password</th>
								<th></th>
							  <tr>";
						$x=1;

					foreach ($datosUser as $clave => $valor) {
						echo "<tr>";
							
						foreach ($valor as $key => $value) {

							echo"<td>
								<input type='text' name='cambiar".$x."' value='".$value."'>
								<input type='submit' name='borrar".$x."'  onclick='Update(".$idUser.");' value='cambiar'>
								</td>";

								$x++;
						}
							
						echo "<td>
								<input type='submit' class='inputBtnTxt' name='nocambiar' value='Cerrar'>
							</td>";

					}

					echo"</table>";
					echo "</form>";
				echo"</div>";
			}

			if(isset($_POST['borrar2'])){
				$pass=$_POST['cambiar2'];
				$sql="UPDATE tb_user SET password='$pass' WHERE idUser=".$idUser;
				$conn->EjecutarConsultaNoDevolucion($sql);
				$smsCambio='El password ya se ha cambiado.';

				$sql="SELECT nombre, password FROM tb_user WHERE idUser=".$idUser;

				$datosUser=$conn->DevolverConjuntoRegistros($sql);

				echo"<h4>Ajustes</h4><hr><br>";

				echo"<div class='todoComments'>";
					echo "<span class='red'>".$smsCambio."</span>";
					echo "<form method='post'>";
					//Dibujamos la tabla
					echo"<table>";
						echo "<tr>
								<th>Nombre</th>
								<th>Password</th>
								<th></th>
							  <tr>";
						$x=1;

					foreach ($datosUser as $clave => $valor) {
						echo "<tr>";
							
						foreach ($valor as $key => $value) {

							echo"<td>
								<input type='text' name='cambiar".$x."' value='".$value."'>
								<input type='submit' name='borrar".$x."'  onclick='Update(".$idUser.");' value='cambiar'>
								</td>";

								$x++;
						}
							
						echo "<td>
								<input type='submit' class='inputBtnTxt' name='nocambiar' value='Cerrar'>
							</td>";

					}

					echo"</table>";
					echo "</form>";
				echo"</div>";
			}
		?>

		<!-- PHP(Fechas desde hasta) -->
		<?php 
			if(isset($_POST['verFecha'])){
				
				if(empty($_POST['desde']) || empty($_POST['hasta'])){

		 			echo '<p class="red errores">Debe rellenar las dos fechas.</p>';

		 		}else{

		 			$desde=test_input($_POST['desde']);
		 			$hasta=test_input($_POST['hasta']);
		 			
		 			$fechD=date('Y-m-d', strtotime($desde));
		 			$fechH=date('Y-m-d', strtotime($hasta));

		 			//Recogemos todos los comentarios entre las dos fechas introducidas 
					$sql="SELECT U.nombre, C.comment, DATE_FORMAT(C.fecha, '%d.%m.%Y - %h:%m:%s') as fecha FROM tb_comment AS C INNER JOIN tb_user AS U ON C.idUser=U.idUser WHERE C.fecha>='$fechD 00:00:00' AND C.fecha<='$fechH 23:59:59' ORDER BY C.fecha ASC";

					$commentFech=$conn->DevolverConjuntoRegistros($sql);

					//Total de comentarios 
					echo"<h4>".count($commentFech)." comentarios</h4><hr><br>";

					echo"<div class='todoComments todoCommentsAr'>";
					
					foreach ($commentFech as $reg) {
						echo "<div class='usuarios'>";
						echo "<h4 class='nomuser'>".$reg['nombre']."</h4>";
						echo "<span class='fecha'>".$reg['fecha']."</span>";
						echo "<p>".$reg['comment']."</p>";
						echo "</div>";
					}

					echo"</div>";
				}
			}
		?>

		<!-- Total de comentarios-->
		<h4> <?php echo $total; ?> comentarios</h4><hr><br>
		<div class="todoComments">
			<!-- PHP(Todos los comentarios) -->
			<?php 
				//Recogemos todos los nombres y los comentarios 
				$sql="SELECT C.comment, U.nombre, DATE_FORMAT( C.fecha, '%h:%m:%s - %d.%m.%Y') as fecha FROM tb_comment AS C INNER JOIN tb_user AS U ON C.idUser=U.idUser ORDER BY C.fecha;";

				$userComment=$conn->DevolverConjuntoRegistros($sql);
			
				//Creacion dinamica de comentarios
				foreach ($userComment as $reg) {
					echo "<div class='usuarios'>";
					echo "<h4 class='nomuser'>".$reg['nombre']."</h4>";
					echo "<span class='fecha'>".$reg['fecha']."</span>";
					
					echo "<p>".$reg['comment']."</p>";
					echo "</div>";
				}
			?>
		</div>
	</div>
	<?php 
		//Se cierra la conexion
		$conn->closeConn();
	 ?>
</body>
</html>