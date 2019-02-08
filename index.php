<?
include 'app/route_controller.php';
?>
<html>
<head>
	<title><?=$page['title']?></title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/css/main.css">
</head>
<body>
	<div class="container main_menu_wrapper">
		<div class="col-md-12">
			<div class="row">
				<nav class="navbar navbar-default" role="navigation">
					<ul class="nav navbar-nav">
						<li class="nav-item <?=($view=='user_list'?'active':'')?>">
							<a class="nav-link" href="/">Home</a>
						</li>
						<li class="nav-item <?=($view=='report'?'active':'')?>">
							<a class="nav-link" href="/?p=reports">Reports</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>

	<?=$page['html']?>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript" src="/js/main.js"></script>
</body>
</html>