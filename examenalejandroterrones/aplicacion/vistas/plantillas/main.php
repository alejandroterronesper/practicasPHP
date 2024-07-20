<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo $titulo; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width; initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="/estilos/principal.css" />

	<link rel="icon" type="image/png" href="/imagenes/favicon.png" />
	<?php
	if (isset($this->textoHead))
		echo $this->textoHead;
	?>
</head>

<body>
	<div id="todo">
		<header>
			<div class="logo">
				<a href="/index.php"><img src="/imagenes/logo.png" width="50px" height="50px" /></a>
			</div>
			<div class="titulo">
				<a href="/index.php">
					<h1>Framework 1</h1>
				</a>
			</div>

		</header><!-- #header -->
		<div id="barraLogin">
			<?php

			if ($_POST) {

				// if (isset($_POST["CerrarSesion"])){

				// 	Sistema::app()->Acceso()->quitarRegistroUsuario();
				// 	Sistema::app()->irAPagina(array("inicial"));
				// 	exit;
				// }
			}


			if (Sistema::app()->Acceso()->hayUsuario() === true) {
				// echo CHTML::dibujaEtiqueta("p", [], "Bienvenido: ". Sistema::app()->Acceso()->getNick()).PHP_EOL;
				// echo CHTML::iniciarForm("", "post", []).PHP_EOL;
				// echo CHTML::campoBotonSubmit("Cerrar sesión", ["name" => "CerrarSesion"]).PHP_EOL;

				// echo CHTML::finalizarForm().PHP_EOL;
			} else {
				//echo CHTML::dibujaEtiqueta("span", [], "usuario no conectado").PHP_EOL;
				//echo CHTML::botonHtml(CHTML::link("Login", Sistema::app()->generaURL(["registro", "login"])), ["class"=>"boton"]).PHP_EOL;
				//echo CHTML::botonHtml(CHTML::link("Registrarse", ["registro", "pedirDatosRegistro"]), ["class"=>"boton"]).PHP_EOL;
			}


			?>
		</div>
		<nav>
			<?php
			echo CHTML::link("PARITDAS", ["partida"]) . PHP_EOL;


			?>
		</nav>
		<div id="barraLocalizacion">
			<?php
			if (isset($this->barraUbi)) {
				foreach ($this->barraUbi as $ele) {
					echo CHTML::link($ele["texto"], $ele["url"]) . PHP_EOL; //<a href=''> ''</a>
				}
			}
			?>
		</div>
		<div class="contenido">
			<aside>
				<ul>
					<?php

					if (isset($this->menuizq)) {
						foreach ($this->menuizq as $opcion) {
							echo CHTML::dibujaEtiqueta(
								"li",
								array(),
								"",
								false
							);
							echo CHTML::link(
								$opcion["texto"],
								$opcion["enlace"]
							);
							echo CHTML::dibujaEtiquetaCierre("li");
							echo CHTML::dibujaEtiqueta("br") . "\r\n";
						}
					}

					?>
				</ul>
			</aside>

			<article>
				<?php echo $contenido; ?>
			</article><!-- #content -->

		</div>
		<div id="barraExamen">

			<?php

			echo CHTML::dibujaEtiqueta("span", [], "Partidas: " . Sistema::app()->N_Partidas, true) . PHP_EOL;
			echo "<br>" . PHP_EOL;
			echo CHTML::dibujaEtiqueta("span", [], "Partidas hoy: " . $this->N_PartidasHoy, true) . PHP_EOL;


			if (!isset($_SESSION["login"])) {
				echo "<br>" . PHP_EOL;

				echo CHTML::dibujaEtiqueta("span", [], "Sin usuario conectado", true) . PHP_EOL;

				echo CHTML::botonHtml(CHTML::link("Login", Sistema::app()->generaURL(["partida", "Login"])), ["class" => "boton"]) . PHP_EOL;
			} else {
				if ($_SESSION["login"]["validado"] === true) {
					echo CHTML::dibujaEtiqueta("span", [], "Usuario: " . $_SESSION["login"]["nombre"], true) . PHP_EOL;
					echo CHTML::botonHtml(CHTML::link("Apagar sesion", Sistema::app()->generaURL(["partida", "QuitarLogin"])), ["class" => "boton"]) . PHP_EOL;
				} else {
					echo CHTML::dibujaEtiqueta("span", [], "Sin usuario conectado", true) . PHP_EOL;

					echo CHTML::botonHtml(CHTML::link("Login", Sistema::app()->generaURL(["partida", "Login"])), ["class" => "boton"]) . PHP_EOL;
				}
			}
			?>

		</div>
		<footer>
			<h2><span>Copyright:</span> <?php echo Sistema::app()->autor ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>Dirección:</span><?php echo Sistema::app()->direccion ?></h2>
		</footer><!-- #footer -->

	</div><!-- #wrapper -->
</body>

</html>