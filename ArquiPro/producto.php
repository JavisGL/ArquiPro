<?php

session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: /php-login');
  }
require 'database.php';


//Listar registros y consultar registro
	if($_SERVER['REQUEST_METHOD'] == 'GET'){
		if(isset($_GET['idproducto']))
		{
			$sql = $conn->prepare("SELECT * FROM producto WHERE idproducto=:idproducto");
			$sql->bindValue(':idproducto', $_GET['idproducto']);
			$sql->execute();
			$sql->setFetchMode(PDO::FETCH_ASSOC);
			header("HTTP/1.1 200 hay datos");
			echo json_encode($sql->fetchAll());
			exit;				
	}
}
	
	//Insertar registro
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	{
	$sql = "INSERT INTO producto (idproducto, nombreproducto, stock, idcategoria) VALUES(:idproducto, :nombreproducto, :stock, :idcategoria)";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':idproducto', $_POST['idproducto']);
		$stmt->bindValue(':nombreproducto', $_POST['nombreproducto']);
		$stmt->bindValue(':stock', $_POST['stock']);
		$stmt->bindValue(':idcategoria', $_POST['idcategoria']);
		$stmt->execute();
		$idPost = $conn->lastInsertId(); 
		if($idPost)
		{
			header("HTTP/1.1 200 Ok");
			echo json_encode($idPost);
			exit;
		}
	}

	//Actualizar registro
	if($_SERVER['REQUEST_METHOD'] == 'PUT')
	{
	$sql = "UPDATE producto SET nombreproducto=:nombreproducto, stock=:stock, idcategoria=:idcategoria WHERE idproducto=:idproducto";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':idproducto', $_GET['idproducto']);
		$stmt->bindValue(':nombreproducto', $_GET['nombreproducto']);
		$stmt->bindValue(':stock', $_GET['stock']);
		$stmt->bindValue(':idcategoria', $_GET['idcategoria']);
		$stmt->execute();
			header("HTTP/1.1 200 Ok");
			exit;

	}
	
	//Eliminar registro
	if($_SERVER['REQUEST_METHOD'] == 'DELETE')
	{
		$sql = "DELETE FROM producto WHERE idproducto=:idproducto";
		$stmt = $conn->prepare($sql);
		$stmt->bindValue(':idproducto', $_GET['idproducto']);
		$stmt->execute();
		header("HTTP/1.1 200 Ok");
		exit;
	}
	else{
		$message = 'Lo sentimos, no se ha podido eliminar correctamente';
	}
	
	//Si no corresponde a ninguna opción anterior
	header("HTTP/1.1 400 Bad Request");
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Productos</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet">
  </head>
  <body>
    <?php require 'partials/header.php' ?>

    <?php if(!empty($message)): ?>
      <p> <?= $message ?></p>
    <?php endif; ?>

    <h1>Dar de alta un producto</h1>
	<form action="producto.php" method="POST">
	<!--<span>o <a href="signup.php">Regístrate</a></span>//-->
      <input name="idproducto" type="number" placeholder="Id del producto">
      <input name="nombreproducto" type="text" placeholder="Nombre del producto">
      <input name="stock" type="number" placeholder="Stock">
	  <input name="idcategoria" type="number" placeholder="Categoria">
	  <input type="submit" value="Ingresar">
    </form>

	<h1>Consulta producto</h1>
	<form action="producto.php" method="GET">
	<!--<span>o <a href="signup.php">Regístrate</a></span>//-->
      <input name="idproducto" type="number" placeholder="Id del producto">
	  <input type="submit" value="Obtener">
    </form>

	<h1>Actualizar un producto</h1>
	<form action="producto.php" method="POST">
		@method('PUT')
		@csrf
	<!--<span>o <a href="signup.php">Regístrate</a></span>//-->
      <input name="idproducto" type="number" placeholder="Id del producto">
      <input name="nombreproducto" type="text" placeholder="Nombre del producto">
      <input name="stock" type="number" placeholder="Stock">
	  <input name="idcategoria" type="number" placeholder="Categoria">
	  <input type="submit" value="Actualizar">
    </form>

	<h1>Eliminar un producto</h1>
	<form action="producto.php" method="DELETE">
	<!--<span>o <a href="signup.php">Regístrate</a></span>//-->
      <input name="idproducto" type="number" placeholder="Id del producto">
	  <input type="submit" value="Eliminar">
    </form>
  </body>
</html>