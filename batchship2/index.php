<?php
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
	session_start();
	$ini_array = parse_ini_file("configuration.ini", true);
?>
<!DOCTYPE html>
<html lang="en" ng-app="BatchShip2App">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{appname}}</title>
    <!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
	<!-- Optional theme -->
	<link href="css/bootstrap-theme.min.css" rel="stylesheet" />
	<link href="css/app.css" rel="stylesheet" />
	<link href="css/nprogress.css" rel="stylesheet" />
	<link href="css/datepicker.css" rel="stylesheet" />
	<link href="css/loading-box.css" rel="stylesheet" />
	<style type="text/css" > 
		.ng-enter 			{ animation: scaleUp 0.5s both ease-in; z-index: 8888; }
		.ng-leave 			{ animation: slideOutLeft 0.5s both ease-in; z-index: 9999; }
		.st-sort-ascent:before{
			content:'\2191';
		}

		.st-sort-descent:before{
			content:'\2193';
		}

		.st-selected{
			background: blue ;
			color:white;
		}
	</style>
</head>
<body ng-controller="MainController" ng-cloak check-User>
	<div ng-include="'./templates/Login.html'"></div>
	<div ng-include="'./templates/Loading.html'"></div>
	<div class="navbar navbar-default" role="navigation">
	    <div class="container-fluid">
	      	<div class="navbar-header">
	        	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
	            </button>           
	        	<a class="navbar-brand" href="#AddressVerification">BatchShip II</a>	
	        </div>
	      	<div class="navbar-collapse collapse">
	        	<ul class="nav navbar-nav">
	        		<li ng-class="{ active: isActive('{{menu_item.url}}') }" ng-repeat="menu_item in menuitems">
	        			<a href="#{{menu_item.url}}">{{menu_item.name}}</a>
	        		</li>
	        	</ul>
	        	<ul class="nav navbar-nav navbar-right" login-Area>
	        		<li class="login_element"><a class="btn_login">Login</a></li>
	        		<li class="logout_element"><a class="btn_logout">Logout ({{authdata.firstname}})</a></li> 		
	        	</ul>
	      	</div><!--/.nav-collapse -->
	    </div><!--/.container-fluid -->
	</div>
	<div ng-view></div>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
   	<script src="js/jquery/jquery-2.1.1.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap/bootstrap.min.js"></script>
	<script src="js/angular/angular.js"></script>
	<script src="js/angular/angular-route.min.js"></script> 
	<script src="js/angular/angular-sanitize.js"></script>
	
	<script src="js/moment.min.js"></script>
	<script src="js/app.js"></script>
	<script src="js/directives.js"></script>
	<script src="js/controllers.js"></script>
	<script src="js/services.js"></script>
	<script src="js/bootstrap/bootstrap.file-input.js"></script>
	<script src="js/nprogress.js"></script>
	<script src='js/ng-infinite-scroll.js'></script>
	<script src='js/bootstrap/bootstrap-datepicker.js'></script>
	<!--<script src='js/load-google-maps.js'></script>-->
	
	<?php
		//if(isset($_SESSION["username"])){
		//	print("<script src='http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false'></script>");
		//}
	?>
	<script src='js/ngAutocomplete.js'></script>
</body>
</html>