<?php
	require('php/conn.php');
	require("secure.php");
	
	$username = $_SESSION['username'];

	$checkUserLic = mysql_query("Select firstName, lastName, productId, gender From users Where email = '$username'");
	$res = mysql_fetch_object($checkUserLic);

	$sqlPrivileges = mysql_query("Select usertype From `users` Where email = '$username'");
	$resPrivileges = mysql_fetch_object($sqlPrivileges);

	$userId = $_SESSION['userId'];
?>

<!doctype html>
<html lang = "pt">
	<head>
		<title> Daily Helper </title>

		<!-- Meta -->
		<meta charset = "utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- CSS -->
		<link type="text/css" rel="stylesheet" href="resources/css/style.css">
		<link rel="stylesheet" href="lib/jquery-ui-1.10.3/themes/base/jquery-ui.css">
		<link rel="stylesheet" href="lib/bootstrap-3.0/css/bootstrap.min.css">
		<link rel="stylesheet" href="resources/css/normalize.min.css">

		<!-- Scripts -->
		<script src = "lib/jquery-1.10.2/jquery-1.10.2.min.js" type = "text/javascript"></script>
		<script src = "lib/maskMoney/maskMoney.min.js" type = "text/javascript"></script>
		<script src="resources/js/home.min.js"></script>
		<script src="lib/jquery-ui-1.10.3/ui/minified/jquery-ui.min.js"></script>
		<script src="lib/bootstrap-3.0/js/bootstrap.min.js"></script>
	</head>
	<body>
		<!-- Header -->
		<section>
			<header>
				<div class="row">
					<div class="col-xs-4 col-sm-4 col-md-12"> </div>
					<div class="col-xs-4 col-sm-4 col-md-12"> 
						<div class = "logo"> <img src = "resources/images/logo.gif" width = "80" height = "80"> </div>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-12"> </div>

					<div class="col-xs-4 col-sm-4 col-md-12"> </div>
					<div class="col-md-offset-1 col-xs-8 col-sm-8 col-md-8">
						<div class = "welcome"> 
							<?php if($res->gender == 1) echo "<span class = 'welcomeText'> Seja bem vindo " . $res->firstName . " " . $res->lastName; ?> </span> <a href = "php/logout.php"> Logout </a>
						</div>
					</div>
					<div class="col-xs-2 col-sm-2 col-md-2"> 
						<div id = "dataHoraShow"> </div>
					</div>
					<div class="col-xs-4 col-sm-4 col-md-12"> </div>
				</div>
			</header>
		</section>
		<!-- ./Header -->

		<!-- Menu carregado para produtos free -->
		<?php if($res->productId == 1){ ?>
				<nav class="navbar navbar-default" role="navigation">
					<!-- Brand and toggle get grouped for better mobile display -->
					<div class="navbar-header">
						<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						</button>
						<a class="navbar-brand" href="#"> Home </a>
					</div>

					<!-- Collect the nav links, forms, and other content for toggling -->
					<div class="collapse navbar-collapse mainMenu" id="bs-example-navbar-collapse-1">
						<ul class="nav navbar-nav">
							<li> <a href = "#" name = "modulesTasks"> Minhas Tarefas </a> </li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"> Fluxo de caixa <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<li><a href="#" name = "modulesCashFlowMonth"> Visão mensal </a></li>
									<li><a href="#" name = "modulesCashFlowExpenses"> Gerenciar despesas </a></li>
									<li><a href="#" name = "modulesCashFlowIncomes"> Gerenciar receitas </a></li>
									<li><a href="#" name = "modulesCashFlowCategories"> Gerenciar categorias </a></li>
									<!--<li class="divider"></li>
									<li><a href="#">Separated link</a></li>-->
								</ul>
							</li>
							<li class="dropdown">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown"> Gerencial <b class="caret"></b></a>
								<ul class="dropdown-menu">
									<!-- Apenas o usuário master tem acesso -->
									<?php if ($resPrivileges->usertype == '1'){ ?>
										<li><a href="#" name = "gerencialUsuarios"> Usuários </a></li>
									<?php } ?>
								</ul>
							</li>
						</ul>
					</div><!-- /.navbar-collapse -->
				</nav>
		<?php } ?>

		<input type = "hidden" value = "<?php echo $userId; ?>" name = "userId">

		<div id = "contentMain"> </div>
	</body>
</html>