<?php  
	session_start();

	include('include/class_conn_bd.inc'); 
	include('include/class_usuario.inc'); 
	include('include/class_comment.inc');
	include('include/delete.inc');

	//Creamos una nueva conexion
	$conn = new capaBBDD();
?>

<!DOCTYPE html>
<html>
<head>
	<meta chrset="UTF-8">
	<title>Adminisrador</title>
	<link rel="stylesheet" type="text/css" href="css/estilos.css">
</head> 

<body>

	<div class="containerAdmin">
		<h2 class="h2User">Administrador</h2>
		<a class="salida" href="salir.php">Cerrar sesion</a>
		<br>
		<hr>
		 
		<div class='divTitles'>Usuarios</div>

		<div class='allUsers'>
			<form class='formUsu' method="post">
				<input class="btnBuscar" type="submit" name="allUsers" value="Todos los usurios">
				<input class="btnBuscar" type="submit" name="usersBlocked" value="Usurios bloqueados">
			</form>

			<div class="mostrarUsers"> 
				<?php
					//Muestra todos los usuarios
					if(isset($_POST['allUsers'])){
						/*Seleccionamos todos los usuarios */
						$sql="SELECT idUser, nombre, email, password FROM  tb_user";

						$users=$conn->DevolverConjuntoRegistros($sql);
						
						//Dibujamos la tabla
						echo"<table>";
						echo "<tr>
								<th>idUser</th>
								<th>Nombre</th>
								<th>Email</th>
								<th>Password</th>
								<th></th>
							<tr>";
						
						foreach ($users as $clave => $valor) {
							echo "<tr>";
							
							foreach ($valor as $key => $value) {
								echo"<td>".$value."</td>";
							}
							
							echo "<td>
									<input type='submit' class='inputBtnTxt dos' name='borrar'  onclick='BorrarUs(".$valor['idUser'].");' value='Borrar' >
								</td>";

						}

						echo"</table>";
					}

					if(isset($_GET['id'])){
						$id=$_GET['id'];
						
						$us=new usuario($id,'','','');
						
						$us->deleteUser($conn);
						echo $us->get_sms();
						
					}else{
						$id='';
					}
 
					//Muestra los usuarios bloqueados
					if(isset($_POST['usersBlocked'])){
						/*Seleccionamos los usuarios bloqueado*/
						$sql="SELECT idUser, nombre FROM  tb_user WHERE intentos=3";

						$usersBlocked=$conn->DevolverConjuntoRegistros($sql);

						//Dibujamos la tabla
						echo"<table>";
						echo "<tr>
								<th>idUser</th>
								<th>Nombre</th>
								<th></th>
							<tr>";

						foreach ($usersBlocked as $clave => $valor) {
							echo "<tr>";
							
							foreach ($valor as $key => $value) {
								echo"<td>".$value."</td>";
							}
							
							echo "<td>
									<input type='submit' class='inputBtnTxt dos' name='borrar'  onclick='DesbloquearUs(".$valor['idUser'].");' value='Desbloquear' >
								</td>";

						}

						echo"</table>";
					}

					if(isset($_GET['block'])){
						$idUser=$_GET['block'];
						
						$us=new usuario($idUser,'','','');
						
						$us->unlockedUser($conn);
						echo $us->get_sms();
						header('Refresh:1; admin.php');
						
					}else{
						$idUser='';
					}
				?>
			</div>
		</div>

		<div class='secUsuDelete'>

			<form class='formUsu' method="post" name='usuarios'>
				<input class="txtBuscar" type='text' placeholder="Buscar usuario..." name='username'>
				<input class="btnBuscar" type="submit" name="buscarUsu" value="Buscar"> 
			</form>
			
			<div class="mostrarUsers">
				<?php
					if(isset($_POST['buscarUsu'])){
						if($_POST['username']!=""){
							//Guardamos la palabra introducida
							$usuario=$_POST['username'];

							/*Seleccionamos todos los usuarios */
							$sql="SELECT idUser, nombre, email, password FROM  tb_user WHERE nombre='$usuario'";
							$users=$conn->DevolverConjuntoRegistros($sql);
							
							echo"<table>";
							echo "<tr>
									<th>idUser</th>
									<th>Nombre</th>
									<th>Email</th>
									<th>Password</th>
									<th></th>
								  <tr>";
						
							foreach ($users as $clave => $valor) {
								echo "<tr>";
								//var_dump($valor);
								foreach ($valor as $key => $value) {
									echo"<td>".$value."</td>";
								}
								
								echo "<td>
										<input type='submit' class='inputBtnTxt dos' name='borrar'  onclick='BorrarUs(".$valor['idUser'].");' value='Borrar'>
									</td>";
							
							}

							echo"</table>";

						}else{
							echo "<span class='red'>El campo 'buscar' esta vacio.</span>";
						}
					}

					if(isset($_GET['id'])){
						$id=$_GET['id'];
						
						$us=new usuario($id,'','','');
						
						$us->deleteUser($conn);
						echo $us->get_sms();
					}else{
						$id='';
					}
				?>
			</div>
		</div>

		<hr>
		<div class='divTitles'>Comentarios</div>

		<div class='allUsers'>
			<form class='formUsu' method="post">
				<input class="btnBuscar" type="submit" name="allComments" value="Todos los comentrios">
			</form>

			<div class="mostrarUsers">
				<?php
					if(isset($_POST['allComments'])){
						/*Seleccionamos todos los usuarios */
						$sql="SELECT idComment, comment FROM  tb_comment;";

						$users=$conn->DevolverConjuntoRegistros($sql);
							
						echo"<table>";
						echo "<tr>
								<th>idComment</th>
								<th>Comment</th>
								<th></th>
							<tr>";
						
						foreach ($users as $clave => $valor) {
							echo "<tr>";
							//var_dump($valor);
							foreach ($valor as $key => $value) {
								echo"<td>".$value."</td>";
							}
								
							echo "<td>
									<input type='submit' class='inputBtnTxt dos' name='borrar'  onclick='BorrarCom(".$valor['idComment'].");' value='Borrar' >
								</td>";
							}

						echo"</table>";
					}

					if(isset($_GET['idCom'])){
						$idComment=$_GET['idCom'];
						
						$comment=new comentario($idComment, -1, '');
						
						$comment->deleteComment($conn);
						echo $comment->get_sms();
					}else{
						$idComment='';
					}
				?>
			</div>
		</div>

		<div class='secUsuDelete'>

			<form class='formUsu' method="post" name='usuarios'>
				<input class="txtBuscar" type='text' placeholder="Buscar comentario..." name='palabra'>
				<input class="btnBuscar" type="submit" name="buscarComment" value="Buscar"> 
			</form>
			
			<div class="mostrarUsers">
				<?php
					if(isset($_POST['buscarComment'])){
						if($_POST['palabra']!=""){
							//Guardamos la palabra introducida
							$palabra=$_POST['palabra'];

							/*Seleccionamos todos los usuarios */
							$sql="SELECT C.idComment, U.nombre, C.comment FROM tb_comment AS C INNER JOIN tb_user AS U ON C.idUser=U.idUser WHERE C.comment LIKE '%$palabra%'";
							$users=$conn->DevolverConjuntoRegistros($sql);
							
							echo"<table>";
							echo "<tr>
									<th>idComent</th>
									<th>Nombre</th>
									<th>Comentario</th>
									<th></th>
								  <tr>";
						
							foreach ($users as $clave => $valor) {
								echo "<tr>";
								//var_dump($valor);
								foreach ($valor as $key => $value) {
									echo"<td>".$value."</td>";
								}
								
								echo "<td>
										<input type='submit' class='inputBtnTxt dos' name='borrar'  onclick='BorrarCom(".$valor['idComment'].");' value='Borrar' >
									</td>";
							
							}

							echo"</table>";

						}else{
							echo "<span class='red'>El campo 'buscar' esta vacio.</span>";
						}
					}

					if(isset($_GET['idCom'])){
						$idComment=$_GET['idCom'];
						
						$comment=new comentario($idComment, -1, '');
						
						$comment->deleteComment($conn);
						echo $comment->get_sms();
					}else{
						$idComment='';
					}
				?>
			</div>
		</div>
	</div>

	<?php 
		//Se cierra la conexion
		$conn->closeConn(); 
	?>

	<script type="text/javascript">
		function BorrarUs(id) {
		  var result=confirm("Desea borrar usuario con ID="+id);

		  if(result==true){
		  	location.href = "admin.php?id="+id;
		  }
		}

		function BorrarCom(idCom) {
		  var result=confirm("Desea borrar comentario con ID="+idCom);

		  if(result==true){
		  	location.href = "admin.php?idCom="+idCom;
		  }
		}

		function DesbloquearUs(idUs) {
		  var result=confirm("Desea desbloquear al usuario con ID="+idUs);

		  if(result==true){
		  	location.href = "admin.php?block="+idUs;
		  }
		}
	</script>
</body>
</html>