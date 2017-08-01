<?php
	include('include/class_conn_bd.inc');

	//Creamos una nueva conexion
	$conn = new capaBBDD();

	//Sacamos total de registros de comentarios)
	 
	$sql = "SELECT count(*) AS total FROM tb_comment;";

	$allCommentBD=$conn->DevolverConjuntoRegistros($sql);
	$total=$allCommentBD[0]['total'];
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset='UTF-8'>
	<title>Comentarios Usuarios Logueados</title>
	<link rel="stylesheet" href="css/estilosSinLogin.css">
</head>
<body>
	<div class="container">
		<!-- Total de comentarios -->
		<h4 class='totalComments'><?php echo $total; ?> comentarios</h4>	
		<hr><br>

		<div class="todoComments">
			<!-- PHP(Muestra todos los usuarios con sus comentarios) -->
			<?php 
				//Recogemos todos los nombres y los comentarios 
				$sql="SELECT C.comment, U.nombre FROM tb_comment AS C INNER JOIN tb_user AS U ON C.idUser=U.idUser ORDER BY C.fecha;";

				$userComment=$conn->DevolverConjuntoRegistros($sql);

				//Creacion dinamica de comentarios
				foreach ($userComment as $reg) {
					echo "<div class='usuarios'>";
					echo "<h4 class='nomuser'>".$reg['nombre']."</h4>";
					echo "<p>".$reg['comment']."</p>";
					echo "</div>";
				}
			?>
		</div>
	</div>
</body>
</html>