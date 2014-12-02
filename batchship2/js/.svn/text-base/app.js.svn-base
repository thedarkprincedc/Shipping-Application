'use strict';

/* App Module */
var BatchShip2App = angular.module('BatchShip2App', [
  'ngRoute',
  'BatchShip2Controllers',
  'ngSanitize'
]).config(['$logProvider', function($logProvider){
    	$logProvider.debugEnabled(true);
	}
]).run(['batchShipService','$rootScope', '$log',
	function(batchShipService, $rootScope, $log) { 
	
	$log.debug("Bootstrapping.....................");
	$rootScope.appname = "BatchShip II";
	batchShipService.initialize().then(function(){
		$log.warn("loaded data");
	});
}]);

BatchShip2App.filter('fulladd', function() {
	  return function(addressJSON) {
	  	
	  	var tr = "";
	   	$.each(addressJSON, function(index, value){
	   		if(typeof(value) !== "undefined")
	   		tr += value + "<br/>";
	   		//tr += (value.length > 0) ? "<br/>" :  "";
	   	});
	   	return tr;
	  };
});
BatchShip2App.filter('timestamp', function() {
	  return function(timestamp) {
	    return moment(timestamp, "YYYY-MM-DD HH:mm:ss").format("MM-DD-YYYY hh:mma");
	  };
});
BatchShip2App.filter('arrayStreetString', function() {
	  return function(address) {
	  	if(angular.isArray(address)){
	  		var string = "";
	  		$.each(address, function(index, value){
	  			string += (index == 0) ? value : ", " + value;
	  		});
	  		return string;
	  	}
	  	else{
	  		return address;
	  	}
	  };
});
/*
 * Show Tab - Change tabs
 */
BatchShip2App.directive('showTab',
function () {
    return {
        link: function (scope, element, attrs) {
            element.click(function(e) {
                e.preventDefault();
                $(element).tab('show');
            	//$(".alert").html("").hide();
            });
        }
    };
});
BatchShip2App.config(['$routeProvider',
	function($routeProvider) 
	{
		$routeProvider.when('/AddressVerification', {
	        templateUrl: 'partials/User.php',
	        public : false
		}).when('/RegisterEmailAddress/:userid', {
	      	templateUrl : 'partials/RegisterEmailAddress.php',
	      	public : false
		}).when('/UpdateBatchAddresses/:batchid', {
	        templateUrl: 'partials/UpdateBatchAddresses.php',
	        public : false
	        
	    }).when('/UpdateBatchAddresses2/:batchid', {
	        templateUrl: 'partials/UpdateBatchAddresses2.php',
	        public : false
	    }).when('/BatchShipRequests', {
	        templateUrl: 'partials/BatchshipRequest.php',
	        public : false
	    }).when('/BatchShipRequests/:batchid', {
	        templateUrl: 'partials/BatchshipRequest.php',
	        public : false
	    }).when('/ManageShipping', {
	        templateUrl: 'partials/ManageThirdPartyShipping.php',
	        public : false
	    }).when('/AdminMain', {
	        templateUrl: 'partials/AdminMain.php',
	        public : false,
	        admin : true
	    }).when('/User', {
	        templateUrl: 'partials/User.php',
	        public : false
	    }).when('/Login', {
	        templateUrl: 'partials/Mainlogin.php',
	        public : true
	    }).when('/Help', {
	        templateUrl: 'partials/Help.php',
	        public : false
	    }).otherwise({
	    	redirectTo: '/Login'
	    });
}]);

BatchShip2App.directive('checkUser', ['$rootScope', '$location', 'authService', '$route', '$log', 
function ($root, $location, userSrv, $route, $log) {
	
	/*userSrv.isloggedin().then(
		function(message){
		if(message.data.validated == true){
			loadGoogleMaps( 3, "AIzaSyAzejE7EzH8BSE1arIe1P70t0ruZphqe9A", "EN" )
				.done(function() {
					console.log("google maps loaded");					
				}
			);	
		}
	});*/
	
	return {
		link: function (scope, elem, attrs, ctrl) {
			// Logout Button
			$(document).on("click", ".btn_logout", function(event){
				$('.bs-logout-modal-sm').modal('show');
			});
			// Login Button
			$(document).on("click", ".btn_login", function(event){
				$('.bs-login-modal-sm').modal('show');
			});
			// User Logout Event
			$root.$on('user:logout', function (event, data) {
				$.getJSON("./scripts/loginad.php", {action: "logout"}, function( data ){
					location.href = "#";
					location.reload();
				});
				//$("body").append("<iframe src='http://172.27.72.27/web/logout' id='foo'></iframe>");
			});
			// Barracuda login Event
			$root.$on('user:initialized', function(event, data) {
				console.log("aaaaaaaaaaa");
				$root.$apply(function(){
					$location.url("/User");
				});
				
			});
			// User Login Event
			$root.$on('user:loggedin', function (event, data) {
				$('.bs-login-modal-sm').modal('hide');
				$location.url("/User");
				//$root.$broadcast('user:initialized', "ddd");
				/*
				$("body").append("<iframe src='http://172.27.72.27/web/login?_bcsp=1&login_form_action=Login&login=" + data.credentials.username + "&password="+ data.credentials.password+"&_bceq=U2FsdGVkX18lwbhlREXXy2ViK_GLotuGE3joygXus8TVHPzeGX80FLkakSl0ndgRL7P1iGULmeq4if1XFVQKeS-sM2PqIlyzj1TPTQd8Ouc.' id='loginbarracuda' width='0px' height='0px'></iframe>");
	    		$("#loginbarracuda").load(function(response, status, xhr){
	    			$log.warn("barracuda login");
	    			userSrv.setsessiondata({"barracuda_login" : true}).then(function(message){
	    				//console.log(message);
						//******************
						loadGoogleMaps( 3, "AIzaSyAzejE7EzH8BSE1arIe1P70t0ruZphqe9A", "EN" )
				    		.done(function() {
				    			console.log("google maps loaded");
				    			$root.$broadcast('user:initialized', "ddd");
				    	});	
					});
	    		});*/
			});
			$root.$on('$routeChangeStart', function(event, currRoute, prevRoute){
				event.preventDefault();
				userSrv.isloggedin().then(
					function(message){
						if(message.data.validated == false){
							$location.url("Login");
							$('.bs-login-modal-sm').modal('show');
						}
					}
				);
			});
		}
	};
}]);
