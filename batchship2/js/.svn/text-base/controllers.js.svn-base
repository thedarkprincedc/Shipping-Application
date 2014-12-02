'use strict';

/* Controllers */

var BatchShip2Controllers = angular.module('BatchShip2Controllers', ['infinite-scroll', 'ngAutocomplete'])

 // usage: data-google-map
    .directive('googleMap', ['$window', function ($window) {
        return {
            restrict: 'A',
            link: function (scope, element, attrs) {
                // If Google maps is already present then just initialise my map
                if ($window.google && $window.google.maps) {
                    initGoogleMaps();
                } else {
                    loadGoogleMapsAsync();
                }

                function loadGoogleMapsAsync() {
                    // loadGoogleMaps() == jQuery function from https://gist.github.com/gbakernet/828536
                    $.when(loadGoogleMaps())
                        // When Google maps is loaded, add InfoBox - this is optional
                        .then(function () {
                        	console.log("fsdfsf");
                            //$.ajax({ url: "/resources/js/infobox.min.js", dataType: "script", async: false });
                        })
                        .done(function () {
                            initGoogleMaps();
                        });
                };

                function initGoogleMaps() {
                    // Load your Google map stuff here
                    // Remember to wrap scope variables inside `scope.$apply(function(){...});`
                }
            }
        };
    }]);
    
    
  

/*
// Intercepting HTTP calls with AngularJS.

.config(function ($provide, $httpProvider) {
  
  // Intercept http calls.
  $provide.factory('MyHttpInterceptor', function ($q) {
    return {
      // On request success
      request: function (config) {
        console.log(config); // Contains the data about the request before it is sent.
 
        // Return the config or wrap it in a promise if blank.
        return config || $q.when(config);
      },
 
      // On request failure
      requestError: function (rejection) {
        console.log(rejection); // Contains the data about the error on the request.
        
        // Return the promise rejection.
        return $q.reject(rejection);
      },
 
      // On response success
      response: function (response) {
        console.log(response); // Contains the data from the response.
        
        // Return the response or promise.
        return response || $q.when(response);
      },
 
      // On response failture
      responseError: function (rejection) {
        console.log(rejection); // Contains the data about the error.
        
        // Return the promise rejection.
        return $q.reject(rejection);
      }
    };
  });
 
  // Add the interceptor to the $httpProvider.
  $httpProvider.interceptors.push('MyHttpInterceptor');
 
});

*/
/* 
 * Secondary Loading Screens
 */
function ELoadInit(){
	// main overlay container
	$('<div id="msg_overlay">').css({
	      "width" : "100%"
	    , "height" : "100%"
	    , "background" : "#000"
	    , "position" : "fixed"
	    , "top" : "0"
	    , "left" : "0"
	    , "zIndex" : "50"
	    , "MsFilter" : "progid:DXImageTransform.Microsoft.Alpha(Opacity=60)"
	    , "filter" : "alpha(opacity=60)"
	    , "MozOpacity" : 0.6
	    , "KhtmlOpacity" : 0.6
	    , "opacity" : 0.6
	
	}).appendTo(document.body);
	$("<div class='box loading'></div>").css({"zIndex" : "100"}).appendTo(document.body);
	$("#msg_overlay").hide();
	$(".loading").hide();
}
function ELoadStart(){		
	$("#msg_overlay").show();
	$(".loading").show();
}
function ELoadFinish(){
	$("#msg_overlay").hide();
	$(".loading").hide();
}
ELoadInit();

/*
 * BatchShipAuthentication Service
 * This service handles authentication
 */

/*
 * Event Log List Controller
 * 
 */
function onRestError(data, status, headers, config){
	// Status Box Message
	var resterror = "<b>Error:</b> " + status + " " + config.url + " not found ";
	$(".alert-danger").html(resterror).show();
	console.log(resterror);
}

BatchShip2Controllers.controller('ManageEventLogController', ['$scope', '$http', '$rootScope', 'batchShipService', '$interval',
	function($scope, $http, $rootScope, batchShipService, $interval) {
		$scope.update = function(){
			batchShipService.eventlogupdate().then(function(message){
				$scope.events = message;
			});
		};
		$scope.update();
		var intervalPromise = $interval($scope.update, 15000);      
    	$scope.$on('$destroy', function () { 
    		$interval.cancel(intervalPromise); }
    	);
}]);
/*
 *	ManageThirdPartyAddress 
 *	Provides functions for the thirdparty management to work correctly  
 */
BatchShip2Controllers.controller('ManageThirdPartyAddress', ['$scope', '$routeParams', '$http', 'batchShipService',
function($scope, $routeParams, $http, batchShipService) {
	
	$scope.thirdpartyAddresslist = [];
	$scope.thirdpartyAddress = {};
	$scope.thirdpartyAddressSelection = { };
	$scope.listofpropaddress = [];
	$scope.formData = {
		"thirdpartyid" : ""
	};
	$scope.onClickDeleteThirdParty = function(){
		batchShipService.deleteThirdPartyShipInfo($scope.formData).then(function(data){
			console.log(data);
			$scope.update();
			
		});
		
	};
	$scope.onClickMarkAsValidated = function(){
		
	};
	$scope.onClickAddThpAdd = function(data){
		$(".addthirdpartyshipper").modal("show");
		console.log("efwefwefe");
	};
	$scope.onClickUpdate = function(data){
		batchShipService.modifyThirdPartyShipInfo($scope.thirdpartyAddressSelection).then(function(data){
			$scope.update();	
		});
	};
	$scope.onClickDelete = function(data){
		batchShipService.deleteThirdPartyShipInfo($scope.formData).then(function(data){
			$scope.update();
		});
	};
	$scope.onCreateThirdPartyAccount = function(){
		batchShipService.addThirdPartyShipInfo($scope.thirdpartyAddress).then(function(data){
			$scope.update();
			//console.log(data);
		});
		//console.log($scope.thirdpartyAddress);
	};
	$scope.onClickSelection = function(data){
		$scope.listofpropaddress = [];
		$scope.thirdpartyAddressSelection = {
			"company_name" : $scope.thirdpartyAddresslist[data].company_name,
			"street" :  $scope.thirdpartyAddresslist[data].street,
			"city" :  $scope.thirdpartyAddresslist[data].city,
			"state" :  $scope.thirdpartyAddresslist[data].state,
			"country" :  $scope.thirdpartyAddresslist[data].country,
			"zipcode" :  $scope.thirdpartyAddresslist[data].zipcode,
			"carrier" : $scope.thirdpartyAddresslist[data].carrier,
			"accountnumber" : $scope.thirdpartyAddresslist[data].accountnumber,
			"verify_data" : angular.fromJson($scope.thirdpartyAddresslist[data].verify_data),
			"id" : $scope.thirdpartyAddresslist[data].id
		};
		if($scope.thirdpartyAddressSelection.verify_data !== null){

			$scope.validation_data = $scope.thirdpartyAddressSelection.verify_data;
			if(typeof($scope.validation_data.ProposedAddressDetails.Original_Address) !== "undefined"){
				$scope.validation_data.ProposedAddressDetails.Original_Address.type = "Original Address";
				$scope.listofpropaddress.push($scope.validation_data.ProposedAddressDetails.Original_Address);
			}
			if(typeof($scope.validation_data.ProposedAddressDetails.Address) !== "undefined"){
				for (var i = 0; i < $scope.validation_data.ProposedAddressDetails.Address.length; i++){
					$scope.validation_data.ProposedAddressDetails.Address[i].type = "Proposed Address";
					$scope.listofpropaddress.push($scope.validation_data.ProposedAddressDetails.Address[i]);
				}
			}
		}
		console.log($scope.thirdpartyAddressSelection);
		//console.log("ddffsadfsddfsf");
		$("#manageaddress").modal("show");
	};
	$scope.update = function(){
		$scope.formData.thirdpartyid = "";
		batchShipService.getThirdPartyShipInfo().then(function(data){
			$scope.thirdpartyAddresslist = [];
			for(var i = 0; i < data.data.length; i++){
				$scope.thirdpartyAddresslist[i] = data.data[i];
			}
		
		});
	};
	$scope.update();
}]);
	
BatchShip2Controllers.controller('RegisterEmailAddressController', ['$scope', '$routeParams', '$http', 'batchShipService',
	function($scope, $routeParams, $http, batchShipService) {
	$scope.userinfo;
	$scope.formData ={
		action : "",
		username : "",
		emailadd: ""
	};
	$scope.initialize = function(){
		$(".alert").hide();
		batchShipService.getuserbyid($routeParams.userid).then(function(data){
			if(data.status == 200)
			{
				$scope.userinfo = data.data[0];
				$scope.formData.username = $scope.userinfo.username;
				$scope.formData.emailadd = $scope.userinfo.email_address;	
			}
		});
	};
	$scope.onClickSubmit = function(){
			$http.get("./scripts/loginad.php?action=updateemail&userid=" + $routeParams.userid + "&email_address=" + $scope.formData.emailadd)
			.success(function(data) {
				if(data.status == 200){
					$(".alert-success").html(data.message).show();
					location.href = "#/User";
					location.reload();
				}
				else{
					$(".alert-danger").html(data.message).show();
				}
			});
	};
	$scope.initialize();
}]);

BatchShip2Controllers.controller('MainLoginController', ['$scope', '$routeParams', '$http', '$rootScope', 'authService', '$location',
	function($scope, $routeParams, $http, $rootScope, authService, $location) {
	}
]);
/*
 * Address List Controller
 */
BatchShip2App.directive('trsRx', ['$document', '$rootScope', '$location', '$routeParams', '$timeout',
function($document, $rootScope, $location, $routeParams, $timeout)
{
	return {
		link:function(scope, element, attr){
			//scope.validation_data.proposed_details
			//scope.$(w)
			element.blur(function(e){
				//console.log(scope);
					
				$timeout(function(){
					
					if(scope.details1 != ""){
						var currentadd = {
							"address1" : "",
							"address2" : "",
							"address3" : "",
							"city" : "" ,
							"state" : "",
							"postalcode" : "",
							"country" : ""
						};
						$.each(scope.details1.address_components, function(index, value){
							switch(value.types[0]){
								case "street_number": 
									scope.tempstreetnumber = value.long_name;							
								break;
								case "route": 		 				 	scope.temproute = value.long_name;					break;
								case "locality":	 					currentadd.city = value.long_name;					break;
								case "administrative_area_level_1":		currentadd.state = value.short_name;				break;
								case "country":							currentadd.country = value.short_name;				break;
								case "postal_code":						currentadd.postalcode = value.short_name;			break;
							}
							//$scope.details1 = "";
							//$scope.$apply();
							//$scope.$broadcast('address:google', "wwwwwww");
						});	
						//if(scope.temproute !== "" || scope.tempstreetnumber !== ""){
					 	scope.tempstreetnumber = (scope.tempstreetnumber !== "") ? scope.tempstreetnumber : "";
					 	scope.temproute = (scope.temproute !== "") ? scope.temproute : "";
					 	
					 	var streetroute = parseInt(scope.$parent.currentAddress.address2.split(" ")[0]);
					 	scope.tempstreetnumber = (typeof(streetroute) !== 'undefined') ? streetroute : "";
						currentadd.address2 = scope.tempstreetnumber + " " + scope.temproute;
						
						scope.$parent.$apply(function(){
							scope.$parent.currentAddress = currentadd;
							console.log(scope.$parent.currentAddress);
						});
					 	/*var streetroute = scope.currentaddress2.split(" ");
					 	var streetroute = parseInt(streetroute[0]);
					 	if( (typeof(streetroute) == 'number') && scope.tempstreetnumber == ""){
					 		scope.tempstreetnumber = streetroute;
					 	}
						scope.$parent.currentaddress2 = scope.tempstreetnumber + " " + scope.temproute;
						
						scope.$apply(function(){
							
						});*/
							//if($scope.$$phase) {
							  //$digest or $apply
							//  $timeout(function(){}, 2000);
							//}		
						//}
					}
				}, 3000);
			});
			
		}
	};
}]);
/*scope.$watch("vvti", function(newVal, oldVal){
				if(typeof(newVal) !== "undefined"){
				if(newVal.score == 100){
					console.log("fdsfdffdsdfs");
				}
				if(newVal.score < 100){
					console.log("fdsfdffdsdfs");
				}*/
				

BatchShip2Controllers.controller('UpdateAddressControllerSelect', ['$scope', '$routeParams', '$http', '$rootScope', 'batchShipService','$timeout',
	function($scope, $routeParams, $http, $rootScope, batchShipService, $timeout) {
		$scope.viewonly = false;
		$scope.result1 = '';
	    $scope.options1 = null;
	    $scope.details1 = '';
	    $scope.tempstreetnumber = "";
		$scope.oldstreetnumber = "";
		$scope.temproute = "";
		$scope.listofpropaddress = [];
		$scope.validated = false;
		$scope.currShowAdd = {
			"address1" : true,
			"address2" : true,
			"address3" : true,
			"showalladdresslines" : true
		};
		$scope.vvti = {
			"title" : "",
			"score" : "",
			"changes" : "",
			"status" : "",
			"severity" : ""
		};
		$scope.$watch("vvti", function(newVal, oldVal){
			if(typeof(newVal) !== "undefined"){
				console.log(newVal);
				$(".unialert").removeClass("alert-info alert-success alert-danger alert-warning");
				if(newVal.severity == "FAILURE"){
					$(".unialert").addClass("alert-danger");
				}
				else{
					if(newVal.score == 100){
					$(".unialert").addClass("alert-success");
					}
					if(newVal.score < 100){
						$(".unialert").addClass("alert-warning");
					}
				}
				
			}
		});
		
		$scope.currentAddress = {
			"address1" : "",
			"address2" : "",
			"address3" : "",
			"city" : "" ,
			"state" : "",
			"postalcode" : "",
			"country" : ""
		};
		//$scope.shippingtypes = [];
		$scope.shippingselection = "";
		$scope.thirdpartyshippinglist = [];
		$rootScope.$on('maincontroller:thirdpartyshippinglist', function (event, data) {
			$scope.thirdpartyshippinglist = data;
			console.log("llllllllllllllll");
			console.log( $scope.thirdpartyshippinglist);
		});
		$scope.itemData = {
			"store_name":"",
			"store_number":"",
			"attn":"",
			"phone_number":"",
			"kit_number":"",
			"weight":"",
			"dimensions":"",
			"insurance":"",
			"shippingmethod": null,
			"thirdpartyshipping":null
		};
		$scope.updatecurrentaddress = function(selectedAddress){
			$scope.currentAddress.address2 = selectedAddress.StreetLines;
			$scope.currentAddress.city = selectedAddress.City;
			$scope.currentAddress.state = selectedAddress.StateOrProvinceCode;
			$scope.currentAddress.postalcode = selectedAddress.PostalCode;
			$scope.currentAddress.country= selectedAddress.CountryCode;				
		};
		/*
		$rootScope.$on('addressvalidation:passed', function (event, data) {
			//$("#addform").modal("hide");
			$("#continueModal").modal("show");
		});*/
		$rootScope.$on('addressvalidation:updated', function (event, data) {
			if(data.fedex_validation_score == 100){
				//$("#addform").modal("hide");
				$scope.validated = false;
			}
			if(data.ups_validation_score == 100){
				//$("#addform").modal("hide");
				$scope.validated = true;
			}
			if(data.fedex_validation_score < 100){
				/*$("#addform .alert-warning").html( 
					"<b>" + $scope.validation_data.ServiceType.toUpperCase() + " Validation</b><br/>Validation Score: " + data.fedex_validation_score + 
					"%<br/> Proposed Changes: " + $scope.validation_data.ProposedAddressDetails.Changes
				).show(); */
				$scope.validated = false;
			}
			if(data.fedex_validation_score == 100){
				/*$("#addform .alert-success").html( 
					"<b>" + $scope.validation_data.ServiceType.toUpperCase() + " Validation</b><br/>Validation Score: " + data.fedex_validation_score + 
					"%<br/> Proposed Changes: " + $scope.validation_data.ProposedAddressDetails.Changes
				).show(); */
				$scope.validated = true;
			}
			
			//if($scope.validation_data.Severity == "FAILURE"){
			//	$("#addform .alert-danger").html( "Validation Failure").show();				
			//}
		});
		$scope.alertstatus = "";
		$scope.alldata = {};
		$rootScope.$on('addressvalidation:updateselect', function (event, data) {
			$("#OrigStreetLines2, #City, #StateOrProvinceCode, #country").parent().removeClass("has-error");
			$scope.validated = false;
			$scope.viewonly = data.viewonly;
			console.log(data);
			$('#addform').on('show.bs.modal', function (e) {
				$scope.currentmarkasvalidated = false;
				$(".ship_logo").hide();
				$scope.listofpropaddress = [];
				$scope.alldata = data;
				$scope.itemData = {
					"store_name": data.store_name,
					"store_number":data.store_number,
					"attn":data.attn,
					"phone_number":data.phone_number,
					"kit_number":data.kit_number,
					"weight":data.weight,
					"dimensions":data.dimensions,
					"insurance":data.insurance,
					"shipping_method": null,
					"thirdpartyshipping":null
				};
				$.each($rootScope.shippingtypelist, function(index, value){
					if(value.value == data.shipping_method){
						$scope.itemData.shipping_method = value;
					}
				});
				
				
				// Set variables
				$scope.currentAddress = {
					"address1" : data.address1,
					"address2" : data.address2,
					"address3" : data.address3,
					"city" : data.city ,
					"state" : data.state_province,
					"postalcode" : data.postal_code,
					"country" : data.country
				};
				
				$scope.validation_data = angular.fromJson(data.verify_data);
				$scope.alertstatus = $scope.validation_data;
				switch($scope.validation_data.ServiceType.toUpperCase())
				{
					case "UPS":		$(".ups_logo").show();			break;
					case "FEDEX":	$(".fedex_logo").show();		break;		
				}	
				if(typeof($scope.validation_data.ProposedAddressDetails.Original_Address) !== "undefined"){
					$scope.validation_data.ProposedAddressDetails.Original_Address.type = "Original Address";
					$scope.listofpropaddress.push($scope.validation_data.ProposedAddressDetails.Original_Address);
				}
				if(typeof($scope.validation_data.ProposedAddressDetails.Address) !== "undefined"){
					for (var i = 0; i < $scope.validation_data.ProposedAddressDetails.Address.length; i++){
						$scope.validation_data.ProposedAddressDetails.Address[i].type = "Proposed Address";
						$scope.listofpropaddress.push($scope.validation_data.ProposedAddressDetails.Address[i]);
					}
				}
				var status = (typeof($scope.validation_data.ProposedAddressDetails.Score) !== "undefined") ? 								
								"SUCCESS" : "FAILURE";	
				var score = (typeof($scope.validation_data.ProposedAddressDetails.Score) !== "undefined") ? 								
								$scope.validation_data.ProposedAddressDetails.Score : 0;
				var change = (typeof($scope.validation_data.ProposedAddressDetails.Changes) !== "undefined") ?
								$scope.validation_data.ProposedAddressDetails.Changes : $scope.validation_data.Message;
						
			
				$scope.vvti = {
					
					"title" : $scope.validation_data.ServiceType.toUpperCase(),
					"score" : score, 
					"changes" : change,
					"status" : "",
					"severity" : status 
				};
				switch($scope.validation_data.Severity){
					case "SUCCESS":
				
						
					if($scope.validation_data.ProposedAddressDetails.Score < 100){
						$scope.validated = false;
					}
					if($scope.validation_data.ProposedAddressDetails.Score == 100){
						$scope.validated = true;
					}
					break;
					case "FAILURE":
						$scope.validated = false;
					break; 
				} 
			});			
			$(".pac-container").css("z-index", 2000);
			$("#addform").modal('show');
		});
		$scope.onClickShowAllAddLines = function(){
			$scope.currShowAdd.showalladdresslines = ($scope.currShowAdd.showalladdresslines == true) ? false : true;
			switch($scope.currShowAdd.showalladdresslines){
				case true:
				$scope.currShowAdd.address1 = ($scope.currentAddress.address1 !== "") ? true : false;
				//$scope.currShowAdd.address2 = true;
				$scope.currShowAdd.address3 = true;
				break;
				case false:
				//$scope.currShowAdd.address1 = ($scope.currentAddress.address1 == "") ? false : true;
				//$scope.currShowAdd.address1 = false;
				//$scope.currShowAdd.address2 = false;
				$scope.currShowAdd.address3 = false;
				break;
			}
		};
		$scope.onClickDelete = function(){
			NProgress.start();
			batchShipService.delete_address($scope.validation_data.id).then(function(message){
				//$("#addform .alert-success").html(message.message).show();
				// Close Window and Update listing
				$rootScope.$broadcast('addressvalidation:updated', "fsafasfsa");
				//$("#addform").modal('hide');
				//$scope.$apply();
				NProgress.done();
			}, function(message){ // IF Event Fails
				$("#addform .alert-failure").html(message.message).show();
				NProgress.done();
			});
		};
		
		$scope.onClickMarkAsValidated = function(){
			NProgress.start();
			var saveaddress = function(address)
			{
				console.log(address);
				return batchShipService
					.save_address(address)
					.then(function(message)
					{
						console.log(message);
						return message;
					});
			};
			saveaddress(angular.toJson({
					"action" : "update_singleaddress",
					"addressid" : $scope.validation_data.id,
					"batchid" : $routeParams.batchid,
					"markasvalidated" : true,
					"address" : $scope.currentAddress,
					"shippingmethod" : "FEDGRD"
					})
			).then(function(message){
				console.log(message);
				//$("#addform .alert-success").html(message.message).show();
				//$("#addform").modal("hide");
				$scope.vvti = {
					
					"title" : $scope.validation_data.ServiceType.toUpperCase(),
					"score" : 100, 
					"changes" : "NONE",
					"status" : "Marked As Validated",
					"severity" : "SUCCESS" 
				};
				
				// Update listing
				$rootScope.$broadcast('addressvalidation:updated', message);
				NProgress.done();
			});
		};
		$scope.onClickSave = function(){
			var saveaddress = function(address)
				{
					console.log(address);
					return batchShipService
						.save_address(address)
						.then(function(message)
						{
							console.log(">>>>>>>>>>>>>>>-----");
				console.log($scope.itemData);
							console.log(message);
							return message;
						});
				},
			validateAddress = function(address)
				{
					//console.log(batchid);
					return batchShipService
						.validateshippingBySingleAddress(address.data.addressid)
						.then(function(message){
							console.log("--------------------------");
							console.log(message);
							console.log("--------------------------");
							// Update Validation Changes
							//$("#addform .alert-success").html(message.message).show();
							//$("#addform .alert").hide();
							if(message.fedex_val.data.length > 0){
								$scope.vvti = {
									"title" : message.fedex_val.data[0].ServiceType.toUpperCase(),
									"score" : message.fedex_val.data[0].ProposedAddressDetails.Score,
									"changes" : message.fedex_val.data[0].ProposedAddressDetails.Changes,
									"status" : "SAVED",
									"severity" : ""
								};
								
								$scope.listofpropaddress = [];
								//***********************************************
								if(typeof(message.fedex_val.data[0].ProposedAddressDetails.Original_Address) !== "undefined"){
									message.fedex_val.data[0].ProposedAddressDetails.Original_Address.type = "Original Address";
									$scope.listofpropaddress.push(message.fedex_val.data[0].ProposedAddressDetails.Original_Address);
								}
								if(typeof(message.fedex_val.data[0].ProposedAddressDetails.Address) !== "undefined"){
									for (var i = 0; i < message.fedex_val.data[0].ProposedAddressDetails.Address.length; i++){
										message.fedex_val.data[0].ProposedAddressDetails.Address[i].type = "Proposed Address";
										$scope.listofpropaddress.push(message.fedex_val.data[0].ProposedAddressDetails.Address[i]);
									}
								}
							}
							if(message.ups_val.data.length > 0){
								$scope.vvti = {
									"title" : message.ups_val.data[0].ServiceType.toUpperCase(),
									"score" : message.ups_val.data[0].ProposedAddressDetails.Score,
									"changes" : message.ups_val.data[0].ProposedAddressDetails.Changes,
									"status" : "SAVED",
								};
								
						
								$scope.listofpropaddress = [];
								//***********************************************
								if(typeof(message.ups_val.data[0].ProposedAddressDetails.Original_Address) !== "undefined"){
									message.ups_val.data[0].ProposedAddressDetails.Original_Address.type = "Original Address";
									$scope.listofpropaddress.push(message.ups_val.data[0].ProposedAddressDetails.Original_Address);
								}
								if(typeof(message.ups_val.data[0].ProposedAddressDetails.Address) !== "undefined"){
									$.each(message.ups_val.data[0].ProposedAddressDetails.Address, function(index, value){
										value.type = "Proposed Address";
										$scope.listofpropaddress.push(value);
									});
								}
							}
							//**************************							
							// Update listing
							$rootScope.$broadcast('addressvalidation:updated', message);
							NProgress.done();
							return message;
					});
			};
			try{
				$("#OrigStreetLines2, #City, #StateOrProvinceCode, #country").parent().removeClass("has-error");
				console.log($scope.validation_data.id);
				if($scope.currentAddress.address2 == ""){
					$("#OrigStreetLines2").parent().addClass("has-error");
					throw "Address 2 field not populated";	
				}	
								
				if($scope.currentAddress.city == ""){
					$("#City").parent().addClass("has-error");
					throw "City field not populated";
				}	
					
				if($scope.currentAddress.state == ""){
					$("#StateOrProvinceCode").parent().addClass("has-error");
					throw "State field not populated";
				}		
				else{
					if($scope.currentAddress.country.toLowerCase() == "us"){
						var isValid = false;
						$.each($rootScope.statelisting, function(index, value){
							if(value.abbreviation.toLowerCase() == $scope.currentAddress.state.toLowerCase() ){
								isValid = true;
								return;
							}
						});
						if(isValid== false){
							$("#StateOrProvinceCode").parent().addClass("has-error");
							throw "Invalid State Selected";
						}
						
					}
				}	
				if($scope.currentAddress.country == "")	{
					$("#country").parent().addClass("has-error");
					throw "Country field not populated";
				}	
				// Now Save and process addresses
				NProgress.start();
				saveaddress(angular.toJson({
						"action" : "update_singleaddress",
						"addressid" : $scope.validation_data.id,
						"batchid" : $routeParams.batchid,
						"markasvalidated" : $scope.currentmarkasvalidated,
						"address" : $scope.currentAddress,
						"shippingmethod" : "FEDGRD",
						"itemdata" : $scope.itemData
						})
				).then(validateAddress);
			}
			catch(err){
				console.log("Error: " + err);
				$scope.vvti.status = "NOT SAVED - " + err;
			}
		};
	}
]);
// UpdateAddressController -
BatchShip2Controllers.controller('UpdateAddressController', ['$scope', '$routeParams', '$http', '$rootScope', 'batchShipService', '$filter', '$location', '$timeout', 'authService',
	function($scope, $routeParams, $http, $rootScope, batchShipService, $filter, $location, $timeout, authService) {
		$scope.orderby = "";
		$scope.formData = {
			action : "",
			service : "",
			batchid: $routeParams.batchid,
			selected_item : ""	
		};
		var update_ui_tabs = function(){
			return batchShipService.getaddressbatchinfo($routeParams.batchid).then(function(message){
				$scope.info = message.data[0];
			});
		}, update_paging_funct = function(event){
			$scope.myPagingFunction(event);
			//console.log(event);
		};
		$scope.viewonly = false;
		$scope.batchtitletext = "";
		batchShipService.updatebatches($routeParams.batchid).then(function(message){
			$scope.viewonly = (message[0].processed == 't') ? true : false;
			$scope.batchtitletext = message[0].company_name + " - " + message[0].fcp_jobnumber;
		});
		$scope.info = {
			
		};
		$scope.search = {
			"verified" : ""
		};
		 
		$scope.validation_data = "";
		//Current Addresses
		$scope.addresslist = Array();
		
		$scope.listcount = 0;
		$scope.tab_search = "all";
	 	$scope.mechsearch = "";
	 	$scope.searching = false;
	 	$scope.searchresult = [];
	 	$scope.search.$ = "";
	 	$scope.$watch('search.$', function(event, eventold){
	 		if($scope.search.$.length > 0){
				batchShipService.getAddressesByWhere({	"batchid": $routeParams.batchid, 
														"status" : $scope.tab_search, 
														"search" : $scope.search.$
														}).then(function(message){
					$scope.addresslist = [];
					$.each(message.data, function(index, value){
						$scope.addresslist.push(value);
					});
					console.log(message);
				});
				//$scope.searching = true;
	 		}
	 		if(eventold.length > 0 && event.length == 0){
	 			//$scope.addresslist = [];
	 			//scope.resetPaging();
	           // scope.myPagingFunction($scope.tab_search);
	 			//console.log("nothing"  + $scope.tab_search);
	 		}
	 	});
	 	$scope.currentpage = 1;
	 	$scope.totalpages = 0;
	 	$scope.listoffset = 0;
	 	$rootScope.$on('addressvalidation:changed_type', function (event, data) {
	 		$scope.resetPaging();
			$scope.myPagingFunction(data);
			console.log(data);
		});	
		$scope.resetPaging = function(){
			$scope.currentpage = 1;
	 		$scope.totalpages = 0;
	 		$scope.listoffset = 0;
	 		$scope.addresslist = [];
	 		$scope.passedvalidation = false;
		};
		update_ui_tabs();
		
		$scope.myPagingFunction = function(event){	
			console.log("paging function: " + event);
			update_ui_tabs().then(function(){
				$scope.info;	
					$scope.passedvalidation = ($scope.info.total == $scope.info.validation_passed) ? true : false;
					console.log("**all vl=" + $scope.passedvalidation);			
				switch($scope.tab_search){
					case "all":
					$scope.totalpages  = Math.ceil($scope.info.total/10);
					break;
					case "passed":
					$scope.totalpages  = Math.ceil($scope.info.validation_passed/10);
					break;
					case "not_passed":
					$scope.totalpages  = Math.ceil($scope.info.validation_notpassed/10);
					break;
					case "error":
					$scope.totalpages  = Math.ceil($scope.info.validation_failed/10);
					break;
				}
				console.log($scope.info);
				if($scope.currentpage <= $scope.totalpages){			
					var addparams = {	"batchid": $routeParams.batchid, 
										"status" : $scope.tab_search, 
										"limit" : 10, 
										"offset" : $scope.listoffset
									};
					console.log(addparams);
					batchShipService.getAddressesByWhere(addparams).then(function(message)
					{
						console.log(message);
						$.each(message.data, function(index, value){
							value.fulladdress = {
																"attn" : value.attn,
																"address1" : value.address1,
																"address2" : value.address2,
																"address3" : value.address3,
																"city" : value.city,
																"state" : value.stateprovince,
																"zipcode" : value.postal_code,
																"country" : value.country,
													};
							$scope.addresslist.push(value);
							
						});
					}, function(message){
						console.log("failed");
					});
					$scope.currentpage++;
					$scope.listoffset += 10;
				}
				});
		};
		
		$scope.myPagingFunction("all");
		//$scope.fulladdress = [];
		
		// Fix this hide old form
		$scope.vWorkModalVisible = false;
		
		$rootScope.$on('addressvalidation:updated', function (event, data) {
			$scope.update();
		});
		
		
		$scope.update = function(event){
			console.log("------------------");
			$scope.resetPaging();
			$scope.myPagingFunction();
			
			
			/*NProgress.start();
			batchShipService.getAddressesByBatchAndStatus($routeParams.batchid, $scope.tab_search).then(function(message){
				//$scope.addresslist = message.data;
				$scope.fulladdress = message.data;
				$scope.addresslist = [];
				$scope.myPagingFunction();
				$scope.info = message.info[0];
				$scope.passedvalidation = ($scope.info.total == $scope.info.validation_passed) ? true : false; 
				if($scope.passedvalidation){
					$rootScope.$broadcast('addressvalidation:passed', "fsafasfsa");
				}
				NProgress.done();
			});*/
		};
		
		//$scope.update("all");
		$scope.markallasvalidated = function(){
			//************************
			batchShipService.markallasvalidated($routeParams.batchid).then(function(data){
				$scope.update();	
			});
			//************************
			//console.log("xxxxxxxxx");
			
		};
		// Validate Address and Batch
		$scope.validateAdressesByBatch = function(){
			NProgress.start();	
			batchShipService.validateshippingByBatch($scope.formData.batchid).then(function(messages) {
					$(".alert-success").html(messages.message).show();
			   		NProgress.done();
			   		$scope.update();
			});
		};
		// Accept Correct All
		$scope.acceptcorrectedall = function(){
			NProgress.start();				
			batchShipService.takecorrectedall($scope.formData.batchid).then(function(messages) {
				//$(".alert-success").html(messages.message).show();
		   		NProgress.done();
		   		$scope.update();
		 	}); 
		};
		
		$rootScope.onUpdateAddressCtrl = $scope.update;
		$scope.CurrentAddress = {
			StreetLines : "",
			City: "",
			StateOrProvinceCode : "",
			PostalCode : "",
			CountryCode : ""
		};
		$scope.onClickRow = function(address){
			address.viewonly = $scope.viewonly;
			$rootScope.$broadcast('addressvalidation:updateselect', address);
		};
		$scope.onClickFormButton = function(operation){
			$scope.formData.service = operation;
			$scope.formData.batchid = $routeParams.batchid;
		};
}]);
//****************************************************************
// UpdateAddressController -
BatchShip2Controllers.controller('UpdateAddressController2', ['$scope', '$routeParams', '$http', '$rootScope', 'batchShipService', '$filter', '$location', '$timeout', 'authService',
	function($scope, $routeParams, $http, $rootScope, batchShipService, $filter, $location, $timeout, authService) {
		$scope.formData = {
			action : "",
			service : "",
			batchid: $routeParams.batchid,
			selected_item : ""	
		};
		$scope.addresslist = [];
		var addparams = {	"batchid": $routeParams.batchid, 
										/*"status" : $scope.tab_search, */
										"limit" : 10, 
										/*"offset" : $scope.listoffset*/
									};
		batchShipService.getAddressesByWhere(addparams).then(function(message)
		{
			console.log(message);
			$.each(message.data, function(index, value){
				$scope.addresslist.push(value);
			
			});
		}, function(message){
			console.log("failed");
		});
		 $scope.rowCollection = [];
          $scope.columnCollection = [
                { label: 'Name', map: 'FirstName', validationAttrs: 'required', validationMsgs: '<span class="error" ng-show="smartTableValidForm.FirstName.$error.required">Required!</span>',cellTemplate:'<div class="name"><span>{{dataRow.FirstName +" "+dataRow.LastName}}</span><div>'}, //have whatever template you want and your custom css
                //{ label: 'Last Name', map: 'LastName' },
                { label: 'User Name', map: 'UserName', validationAttrs: 'required' },
                { label: 'Password', map: 'Password', noList: true, editType: 'password' },
                { label: 'Customer', map: 'CustId', optionsUrl: '/GetCusts', editType: 'radio' },
                { label: 'Role', map: 'RoleId', optionsUrl: '/GetRoles', editType: 'select', defaultTemplate: '<option value="" ng-hide="dataRow[column.map]">---</option>', validationAttrs: 'required', validationMsgs: '<span class="error" ng-show="smartTableValidForm.RoleId.$error.required">Required!</span>' }, // NOTE: small hack which enables defaultTemplate option :)
                { label: 'E-mail', editType: 'email', map: 'Email' },
                { label: 'Cell Phone', map: 'Mobilephone', noEdit: true, validationAttrs: 'required' },
                { label: 'Locked', map: 'IsLocked', cellTemplate: '<input disabled type="checkbox" name="{{column.map}}" ng-model="dataRow[column.map]">', editType: 'checkbox', noAdd: true }
            ];

            $scope.globalConfig = {
                isPaginationEnabled: true,
                isGlobalSearchActivated: true,
                itemsByPage: 10,
                
                actions: {
                    list: { url: '/GetUsers' },
                    edit: { url: '/EditUser', title: 'Edit User', desc: 'Edit', iconClass: '' }, 
                    add: { url: '/AddUser', title: 'Add User', buttonClass: 'pull-right', iconClass: 'icon-plus', desc: ' Add User' }, // TODO: zkontrolovat default description
                    remove: { url: '/DelUser', title: 'Confirmation Dialog', msg: 'Do you really want to delete the user?' }
                }
            };
		/*
		var update_ui_tabs = function(){
			return batchShipService.getaddressbatchinfo($routeParams.batchid).then(function(message){
				$scope.info = message.data[0];
			});
		}, update_paging_funct = function(event){
			$scope.myPagingFunction(event);
			//console.log(event);
		};
		$scope.viewonly = false;
		$scope.batchtitletext = "";
		batchShipService.updatebatches($routeParams.batchid).then(function(message){
			$scope.viewonly = (message[0].processed == 't') ? true : false;
			$scope.batchtitletext = message[0].company_name + " - " + message[0].fcp_jobnumber;
		});
		$scope.info = {
			
		};
		$scope.search = {
			"verified" : ""
		};
		 
		$scope.validation_data = "";
		//Current Addresses
		$scope.addresslist = Array();
		
		$scope.listcount = 0;
		$scope.tab_search = "all";
	 	$scope.mechsearch = "";
	 	$scope.searching = false;
	 	$scope.searchresult = [];
	 	$scope.search.$ = "";
	 	$scope.$watch('search.$', function(event, eventold){
	 		if($scope.search.$.length > 0){
				batchShipService.getAddressesByWhere({	"batchid": $routeParams.batchid, 
														"status" : $scope.tab_search, 
														"search" : $scope.search.$
														}).then(function(message){
					$scope.addresslist = [];
					$.each(message.data, function(index, value){
						$scope.addresslist.push(value);
					});
					console.log(message);
				});
				//$scope.searching = true;
	 		}
	 		if(eventold.length > 0 && event.length == 0){
	 			//$scope.addresslist = [];
	 			//scope.resetPaging();
	           // scope.myPagingFunction($scope.tab_search);
	 			//console.log("nothing"  + $scope.tab_search);
	 		}
	 	});
	 	$scope.currentpage = 1;
	 	$scope.totalpages = 0;
	 	$scope.listoffset = 0;
	 	$rootScope.$on('addressvalidation:changed_type', function (event, data) {
	 		$scope.resetPaging();
			$scope.myPagingFunction(data);
			console.log(data);
		});	
		$scope.resetPaging = function(){
			$scope.currentpage = 1;
	 		$scope.totalpages = 0;
	 		$scope.listoffset = 0;
	 		$scope.addresslist = [];
	 		$scope.passedvalidation = false;
		};
		update_ui_tabs();
		
		$scope.myPagingFunction = function(event){	
			console.log("paging function: " + event);
			update_ui_tabs().then(function(){
				$scope.info;	
					$scope.passedvalidation = ($scope.info.total == $scope.info.validation_passed) ? true : false;
					console.log("**all vl=" + $scope.passedvalidation);			
				switch($scope.tab_search){
					case "all":
					$scope.totalpages  = Math.ceil($scope.info.total/10);
					break;
					case "passed":
					$scope.totalpages  = Math.ceil($scope.info.validation_passed/10);
					break;
					case "not_passed":
					$scope.totalpages  = Math.ceil($scope.info.validation_notpassed/10);
					break;
					case "error":
					$scope.totalpages  = Math.ceil($scope.info.validation_failed/10);
					break;
				}
				console.log($scope.info);
				if($scope.currentpage <= $scope.totalpages){			
					var addparams = {	"batchid": $routeParams.batchid, 
										"status" : $scope.tab_search, 
										"limit" : 10, 
										"offset" : $scope.listoffset
									};
					console.log(addparams);
					batchShipService.getAddressesByWhere(addparams).then(function(message)
					{
						console.log(message);
						$.each(message.data, function(index, value){
							$scope.addresslist.push(value);
						
						});
					}, function(message){
						console.log("failed");
					});
					$scope.currentpage++;
					$scope.listoffset += 10;
				}
				});
		};
		
		$scope.myPagingFunction("all");
		//$scope.fulladdress = [];
		
		// Fix this hide old form
		$scope.vWorkModalVisible = false;
		
		$rootScope.$on('addressvalidation:updated', function (event, data) {
			$scope.update();
		});
			
		$scope.update = function(event){
			console.log("------------------");
			$scope.resetPaging();
			$scope.myPagingFunction();

		};
		
		//$scope.update("all");
		$scope.markallasvalidated = function(){
			//************************
			batchShipService.markallasvalidated($routeParams.batchid).then(function(data){
				$scope.update();	
			});
			//************************	
		};
		// Validate Address and Batch
		$scope.validateAdressesByBatch = function(){
			NProgress.start();	
			batchShipService.validateshippingByBatch($scope.formData.batchid).then(function(messages) {
					$(".alert-success").html(messages.message).show();
			   		NProgress.done();
			   		$scope.update();
			});
		};
		// Accept Correct All
		$scope.acceptcorrectedall = function(){
			NProgress.start();				
			batchShipService.takecorrectedall($scope.formData.batchid).then(function(messages) {
				//$(".alert-success").html(messages.message).show();
		   		NProgress.done();
		   		$scope.update();
		 	}); 
		};
		
		$rootScope.onUpdateAddressCtrl = $scope.update;
		$scope.CurrentAddress = {
			StreetLines : "",
			City: "",
			StateOrProvinceCode : "",
			PostalCode : "",
			CountryCode : ""
		};
		$scope.onClickRow = function(address){
			address.viewonly = $scope.viewonly;
			$rootScope.$broadcast('addressvalidation:updateselect', address);
		};
		$scope.onClickFormButton = function(operation){
			$scope.formData.service = operation;
			$scope.formData.batchid = $routeParams.batchid;
		};*/
}]);
 
//****************************************************************

/*
 * Address Verification Form
 */
BatchShip2Controllers.controller('Address_Verification_Ctrl', ['$scope', '$http', '$rootScope', 'batchShipService', 'authService', 'fileUpload',
	function($scope, $http, $rootScope, batchShipService, authService, fileUpload) {
		$scope.formData = {
			userid : "",
			job_number : "",
			customer_name : "",
			csr_email : ""
		};
		$scope.filewrongtype = false;
		$scope.filesupplied = false;
		authService.authenticate_cookie().then(function(message){
			
			$scope.formData.userid = message.data.userid;
		});
		$scope.foundjobnumber = false;
		$scope.change = function(){
			if($scope.formData.job_number !== undefined){
				$scope.formData.customer_name = "";
				$("#jobnumber, #customername").parent(".form-group").removeClass("has-error has-success");
				if($scope.formData.job_number.length >= 4){
					
					batchShipService.lookupCustomerInfoByJob($scope.formData.job_number).then(function(message){
						if(message.data.length == 1){
							$scope.formData.customer_name = message.data[0].customer_name;
							$scope.formData.csr_email = message.data[0].csr_email;
							$("#jobnumber, #customername").parent(".form-group").addClass("has-success");
							$scope.foundjobnumber = true;
						}
						else{
							$("#jobnumber, #customername").parent(".form-group").addClass("has-error");
							$scope.foundjobnumber = false;
						}
					});
				}
				else{
					$scope.formData.customer_name = "";
				}
			}
		};
		// This fixes the file upload button so it doesnt look generic
		$('.file-inputs').bootstrapFileInput();
		
	    $(':file').change(function(){
		    var file = this.files[0];
		    var name = file.name;
		    var size = file.size;
		    var type = file.type;
		    
		    //Your validation 
		    var filetypes = ["xls", "xlsx"];
		    var ext = file.name.split(".");
		    if(filetypes.indexOf( ext[1] ) == -1){
		    	$scope.filewrongtype = false;
		    	$(".alert-danger").html("<b>Error: </b><b><u>"+ file.name + "</u></b> is the wrong file type. Please choose different file").show();
		    }
		    else{
		    	$scope.filesupplied = true;
		    	$scope.filewrongtype = true;
		    	$(".alert-danger").html("").hide();
		    }
		});
		$(document).on('change', '.btn-file :file', function(event) {
				var input = $(this);
				var numFiles = input.get(0).files ? input.get(0).files.length : 1;				
				var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
				input.trigger('fileselect', [numFiles, label]);
		});
		$('.btn-file :file').on('fileselect', function(event, numFiles, label) {
			
			var icon = ($scope.filewrongtype) ? "<img src='./content/microsoft-excel-icon.png' width='20px'/>" : "<span class='glyphicon glyphicon-remove'></span>";
	      	$(".file_upload_status").html(icon + " " + label);
	    });
		$scope.progressHandlingFunction = function(e){
		    if(e.lengthComputable){
		    	var r = Math.round((e.loaded / e.total) * 100);		    	
		    	$(".num_progress").html(r + "/100");
		    	$('.progress-bar').css('width', r +'%').attr('aria-valuenow', r);
		    	if(r == 100)
		    	{
		    		$(".title").html("<center>Processing</center>");
		    	}
		    }
		};
		$scope.onClickClearBtn = function(){
			var defaultForm = {
            	job_number : "",
				customer_name : ""
         	};
         	$scope.Address_Verification.$setPristine();
			$scope.formData = defaultForm;	
			$(".file_upload_status").html("");
			$(".form-group").removeClass("has-error has-success");
		};
	    $scope.onClickSubmit = function(){
	    	try{
	    		// Test job number
	    		if($scope.formData.job_number.length == 0)
	    			throw "No job number was supplied";
	    		else{
	    			// Make sure job number follows the format
	    			var re = new RegExp("^[0-9]{1,}[A-Za-z]{1}");
					if( (re.test($scope.formData.job_number)) != true )
					{
						console.log($scope.formData.job_number);
						$("#jobnumber").parent().addClass("has-error");
						throw "Jobnumber not correct format";
					}
					else{
						$("#jobnumber").parent().addClass("has-success");
					}
	    		}	
	    		// Makes sure a customer name is entered
	    		if($scope.formData.customer_name.length == 0)
		    		throw "No customer name was supplied";
		    	// Make sure a file is supplied
		    	if($scope.filesupplied == false){
		    		throw "No file was was supplied";
		    	}
		    		
		    	//var item = {
		    	//			"batchid" : scope.txt
		    	//		};
				//fileUpload.uploadforminfo($scope.formData, $scope.file);
					
		    	var formData = new FormData($("#Address_Verification")[0]);
		    	console.log("form-data");
		    	console.log(formData);
		    	console.log("---------");
		  		$.ajax({
					type: 'POST',
					url: './scripts/processBatchShipItemsForm.php?action=uploadandprocess',  //Server script to process data
					   //Options to tell jQuery not to process data or worry about content-type.
					cache: false,
					contentType: false,
					processData: false,
					 // Form data
					data:  formData,
					dataType: 'json',
					xhr: function() {  // Custom XMLHttpRequest
						var myXhr = $.ajaxSettings.xhr();
						if(myXhr.upload){ // Check if upload property exists
							myXhr.upload.addEventListener('progress',$scope.progressHandlingFunction, false); // For handling the progress of the upload
							$(".alert").html("").hide();
							$('.bs-loading-modal-sm').modal('show');
							$(".title").html("<center>Uploading Files...</center>");
				    	}
					    return myXhr;
					},
					success: function(jqXHR, textStatus, errorThrown){
						// Message Box Status
						if(jqXHR.status == 200){
					 		// Show Success Message
							$(".alert-success").html(jqXHR.message ).show();
							$(".title").html("<center>Upload Complete</center>");
							$($("#myTab .manage_batches")).tab('show');
							$('.bs-loading-modal-sm').modal('hide');	
							$rootScope.$broadcast('file:uploaded', "emit");
							$scope.onClickClearBtn();
						}
						else{
							$(".alert-danger").html(jqXHR.message ).show();
							$('.bs-loading-modal-sm').modal('hide');
						}
					},
					error: function(jqXHR, textStatus, errorThrown){
						// Message Box Status
						$('.bs-loading-modal-sm').modal('hide');
						throw textStatus + " " + errorThrown;
					}
				}); 	
	    	}
	    	catch(err){
	    		$(".alert-danger").html("Error: " + err).show();
	    	}
	    };
}]);
/*
 * LogonFormController
 * Contains all client side code for logins
 */
BatchShip2Controllers.controller('LoginFormController', ['$scope', '$http', '$rootScope', 'authService', '$location', '$timeout', '$log',
	function($scope, $http, $rootScope, authService, $location, $timeout, $log) {
		$scope.loginstring ={ "username" : "",
							  "password" : "",
							  "action" : "login"
						};
		$(".alert").html("").hide();
		$scope.onSubmitLogin = function(){
			try
			{
				$(".alert").hide();
				if($scope.loginstring.username == "")
					throw "No username";
				if($scope.loginstring.password == "")
					throw "No password";
					
				authService.authenticate($scope.loginstring).then(function(data) {
	                if (data.data.status ===200) {
	                	if(data.data.data.validated == true){
	                		data.data.data.credentials = {
	                			"username" : $scope.loginstring.username,
	                			"password" : $scope.loginstring.password
	                		};
	                		
		                   //	$(".alert-success").html(data.data.message).show();	
		                  	$rootScope.$broadcast('user:loggedin', data.data.data);
		                } else {
		                	$(".alert-danger").html(data.data.message).show();
		                	//throw data.data.message;	                   
		                }
	                }
	            }, function(error) {
	                // promise rejected, could log the error with: console.log('error', error);
	              
	            });
			}
			catch(err){
				$(".alert-danger").html("Error: " + err + " was entered").show();
			}
			return true;
		}; 
}]);
/**
 *LogoutFormContolller
 * Contains all client side code for logging out 
 */
BatchShip2Controllers.controller('LogoutFormController', ['$scope', '$http', '$rootScope',
	function($scope, $http, $rootScope) {
		$scope.onSubmitForm = function(){
			$rootScope.$broadcast('user:logout', "logout");
		};
		
}]);

/*
 * BatchshipRequestController
 * This is used to process a batchship request
 */
BatchShip2Controllers.controller('BatchshipRequestController', ['$scope', '$routeParams', '$rootScope', '$http', 'batchShipService', 
function($scope, $routeParams, $rootScope, $http, batchShipService) 
{
	$scope.validation_percent =0;
	$scope.isFormSubmitButtonClicked = false;
	$scope.formData = {
		"action" : "processbatchship_request", 
		"batchid" : $routeParams.batchid,
		"comments" :"",
		"companyname" : "",
		"jobnumber" : "",
		"ponumber" : "",
		"ponumberrequired" : false,
		"blindshipcompany" : "",
		"useformponumbers" : true,
		"numOfAddresses" : "",
		"shipDate": "",
		"shipFrom" : "",
		"packageType" : "",
		"billto" : "",
		"multipleshipquantities" : false,
		"thirdpartyaccount" : "",
		"description" : "",
		"totaladdresses" : 0,
		"validation_passed" : 0,
		"validation_failed" : 0,
		"validation_percent" : 0
	};
	$scope.formlevelponumbersdisplay = "";
	$scope.initialelements = "";
	$scope.shmeth = [];
	$scope.thirdpartybilling_opt = [];

	$('.bs-example-modal-sm').modal('hide');

	/*$scope.$watch("formData.useformponumbers", function(newVal, oldVal){
		console.log(newVal);
		if(newVal == true){
			$scope.formData.ponumber = $scope.formlevelponumbersdisplay;
		}
		else{
			$scope.formData.ponumber = "";
		}
	});*/
	$scope.onClickUseFormPONumbers = function(){
		//$scope.formData.ponumber
		//$scope.formData.ponumber = (formData.useformponumbers) ? "" 
	};
	$scope.update = function(){
		
		batchShipService.getThirdPartyShipInfo().then(function(message){
			$scope.thirdpartybilling_opt = message.data;
		});
		
		batchShipService.getPONumbersByBatchID($routeParams.batchid).then(function(messages) {
				if(messages.data.po_numbers_counted !== messages.data.total_rows_returned){
					// if the po number field is not completely filled out
					// then we need to force the user to enter a po number
					$scope.formData.ponumberrequired = true;
				}
		});
		
		batchShipService.updatebatches($routeParams.batchid).then(function(messages) {
			
			if (typeof(messages[0]) != 'undefined'){
				$scope.formData.companyname = messages[0].company_name;
				if($scope.formData.companyname.length > 0)
				{
					$("#companyname").parent().addClass("has-success");
				}
				$scope.formData.validation_passed = messages[0].validation_passed;
				$scope.formData.totaladdresses = messages[0].total_addresses;
	   			$scope.formData.jobnumber = messages[0].fcp_jobnumber;
				$scope.formData.numOfAddresses = messages[0].validation_passed + "/" + messages[0].total_addresses;					
				$scope.formData.validation_percent = messages[0].validation_percent;
				if($scope.formData.validation_passed != $scope.formData.totaladdresses)
				{
					$("#numOfAddresses").parent().addClass("has-error");
				}
				else{
					$("#numOfAddresses").parent().addClass("has-success");
				}
			}
 		});
	};
	$scope.thirdpartyAddress = {};
	$rootScope.$on('thirdparty:added', function (event, data) {
		batchShipService.getThirdPartyShipInfo().then(function(message){
			$scope.thirdpartybilling_opt = message.data;
		});
		
	});
	$scope.onCreateThirdPartyAccount = function(){
		// Create Third Party Account
		try{
			if($scope.thirdpartyAddress.company_name.length == 0)
				throw "Company Name was not supplied";
			if($scope.thirdpartyAddress.street.length == 0)
				throw "Street was not supplied";
			if($scope.thirdpartyAddress.city.length == 0)
				throw "City was not supplied";
			if($scope.thirdpartyAddress.state.length == 0)
				throw "State was not supplied";
			if($scope.thirdpartyAddress.zipcode.length == 0)
				throw "Zip code was not supplied";
			if($scope.thirdpartyAddress.accountnumber.length == 0)
				throw "Account Number was not supplied";
			batchShipService.addThirdPartyShipInfo($scope.thirdpartyAddress).then(function(message){
				$rootScope.$broadcast('thirdparty:added', message);
			});
		}
		catch(err){
			console.log("Error: " + err);
		}
	};
	$scope.onCancelBatchShipRequest = function(){
		
	};
	$scope.onProcessBatchshipRequest = function(){
		try{
			$("#jobnumber, #billto, #ponumber, #packagetype, #shipfrom").parent().removeClass("has-error");
			$(".alert-danger").html("").hide();
			if($scope.formData.jobnumber.length == 0){
				$("#jobnumber").parent().addClass("has-error");
				throw "<u>job number</u> not entered";
			}
			else{
				$("#jobnumber").parent().addClass("has-success");
			}
			if($scope.formData.ponumberrequired == true &&
				$scope.formData.ponumber.length == 0){
				$("#ponumber").parent().addClass("has-error");
				throw "<u>po number</u> is required since we did not fill out the po number fields completely in the spreadsheet";	
			} 
			
			if($scope.formData.shipDate.length == 0){
				$("#shipdate").parent().addClass("has-error");
				throw "<u>ship date</u> not entered";
			}
			else{
				var re = new RegExp("^[0-9]{2}/[0-9]{2}/[0-9]{4}");
				if( (re.test($scope.formData.shipDate)) != true )
				{
					console.log($scope.formData.shipDate);
					$("#shipdate").parent().addClass("has-error");
						throw "<u>ship date</u> not correct format";
				}
				else{
					$("#shipdate").parent().addClass("has-success");
				}
			}
			if($scope.formData.shipFrom.length == 0){
				$("#shipfrom").parent().addClass("has-error");
				throw "<u>ship from </u> location not selected";
			}
			
			if($scope.formData.packageType.length == 0){
				$("#packageType").parent().addClass("has-error");
				throw "<u>package type </u> not entered";
			}
				
			if($scope.formData.validation_passed != $scope.formData.totaladdresses){
				$("#numOfAddresses").parent().addClass("has-error");
				throw "<br/>Click here to <a href='#/UpdateBatchAddresses/"+ $routeParams.batchid +"'>update</a> Addresses";
			
			}
			if($scope.formData.billto.length == 0){
				$("#billto").parent().addClass("has-error");
				throw "<u>billto</u> not entered";
			}
			else{
				switch($scope.formData.billto){
					case "Third Party":
						if(	$scope.formData.thirdpartyaccount.length == 0){
							$("#thirdpartyaccount").parent().addClass("has-error");
							throw "<u>third party account</u> not entered";
						}
					break;
					case "Sender":
						if($scope.formData.sender.length == 0){
							$("#thirdpartyaccount").parent().addClass("has-error");
							throw "<u>sender</u> not entered";
						}			
					break;
				}
			}
			
			// Process Batchship request
			console.log($scope.formData);
			$scope.isFormSubmitButtonClicked = true;
			NProgress.start();	
			batchShipService.processbatchshiprequest($scope.formData).then(function(message){
				if(message.status == 200){
					$(".alert-success").html("<b>Success:</b> " + message.message + "<br/><a href='#/User'>Click here to continue</a>").show();
					$("#batchshiprequest").hide();	
					$scope.isFormSubmitButtonClicked = false;	
					NProgress.done();	
				}
				
			});
		}
		catch(err){
			$(".alert-danger").html("<b>Error:</b> " + err).show();	
		}
	};
	$scope.update();
}]);

BatchShip2App.directive('thirdPartyAddAddress', ['$document', 
function($document)
{
	return function(scope, element, attr)
	{
		console.log("helpme");
		//element.click(function(event){
			//scope.$parent.txt = scope.batch_item.id;
			//scope.$apply();
			//$(".updateaddresses").modal("show");
		//});
	};		
}]);
/*
 * Userlist Controller form
 */
BatchShip2Controllers.controller('ManageUsersController', ['$scope', '$http', 'authService', 'batchShipService',
	function($scope, $http, authService, batchShipService) {
		
		$scope.formData = {
			action : "",
			selectedid : 0
		};
		$scope.userform = {
			id : "",
			username : "",
			emailaddress : "",
			permissions : ""
		};
		$scope.onUpdateList = function(){
			authService.updateuserlist().then(function(data){
				$scope.userlist = data;
			});
		};
		$scope.onUpdateList();
		$scope.onModifyUserInfo = function(){
			batchShipService.modifyUserInfo($scope.userform).then(function(data){
				console.log($scope.userform);
				//$scope.onUpdateList();
			});
		};
		$scope.onDeleteUser = function(){
			
		};
}]);

/*********************************
 *	MainController
 * Manages the passing of authentication variables
 ********************************/	
BatchShip2Controllers.controller('MainController', 
['$scope', '$http', '$rootScope', 'batchShipService', 'authService',
function($scope, $http, $rootScope, batchShipService, authService) {
		$scope.loggedin = false;
		$scope.authdata = {};
		//$scope.shippingtypelist = [];
		authService.isloggedin().then(function(message){
			$scope.loggedin = (message.data.validated) ? true : false;
		});
		
		$rootScope.$on('user:loggedin', function (event, data) {
			//authService.authenticate_cookie().then(function(message){
			$scope.authdata = data;
			//	});
		});
		
		authService.authenticate_cookie().then(function(message){
			$scope.authdata = message.data;		
		});
}]);
/*********************************
 *	ManageBatchController
 * Manages the Batch Items provides functionality such as 
 * delete and validate fedex
 ********************************/	
BatchShip2Controllers.controller('ManageBatchController', 
	['$scope', '$http', '$rootScope', 'batchShipService', '$interval', '$location', 'authService',	
	function($scope, $http, $rootScope, batchShipService, $interval, $location, authService) {
		$scope.formData = {
			username : "",
			batchid : "",
			action : ""
		};
		$scope.bShowDelete = true;
		$scope.batchlist = [];
		// If a file is uploaded update the list
		$rootScope.$on('file:uploaded', function (event, data){
			$scope.update();
		});
		$scope.$watch("formData.batchid", function(evt){
			console.log($scope.batchlist);
			$.each($scope.batchlist, function(index, value){
				if( (value.id == $scope.formData.batchid) && (value.processed == 't'))
				{
					$scope.bShowDelete = false;
				}
				if( (value.id == $scope.formData.batchid) && (value.processed == 'f'))
				{
					$scope.bShowDelete = true;
				}
			});
		});
		$scope.update = function(){
			authService.authenticate_cookie().then(function(message){
				batchShipService.getBatchesByUserIDAndPrivs(message.data.userid, "user").then(function(messages) {
		   				$scope.batchlist = messages;
	 			});
				console.log(message);
			});
		};
		$scope.onClickProcessBatch = function(){
    		if($scope.formData.batchid != ""){
    			$location.path("/BatchShipRequests/"+$scope.formData.batchid);
    		}
    	};
    	$scope.onClickUpdateAddresses = function(){
    		if($scope.formData.batchid > 0){
    			$location.path("UpdateBatchAddresses/"+$scope.formData.batchid);
    		}
    	};
    	$scope.onClickReUploadBatch = function(){
    		if($scope.formData.batchid > 0){
    			$scope.txt = $scope.formData.batchid;
    			$(".updateaddresses").modal("show");
    		}
    	};
    	$scope.onClickValidate = function(){
    		NProgress.start();	
			batchShipService.validateshippingByBatch($scope.formData.batchid).then(function(messages) {
					$(".alert-success").html(messages.message).show();
			   		NProgress.done();
			   		$scope.update();
			});	
    	};
    	$scope.onClickDelete = function(){
    		NProgress.start();	
			batchShipService.deletebatch($scope.formData.batchid).then(function(messages) {
   				$(".alert-success").html(messages.message).show();
   				$scope.update();
   				NProgress.done();
 			});	
    	};
    	$scope.update();
}]);
/*
 * Directives
 * FileUpload - is used to actually upload files and text
 */

/*
 * fileModel - Selects the file throught the selector
 * 				
 */
BatchShip2App.directive('fileModel', ['$document', '$parse',
function($document, $parse)
{
	return{
		restrict: 'A', 
		link : function(scope, element, attr){
            var model = $parse(attr.fileModel);
            var modelSetter = model.assign;
            element.bind('change', function(){
            	scope.$apply(function(){
                	modelSetter(scope, element[0].files[0]);
                });
            });
		}
	};
}]);
/*
 * Clicks and parses uploaded fields
 */
BatchShip2App.directive('onClickUpload', 
	['$document', 'fileUpload', function($document, fileupload) {
	return function(scope, element, attr)
	{
		element.click(function(event){
			try{
				var file = scope.file;
		        //console.log('file is ' + JSON.stringify(file));
		        //var uploadUrl = "/fileUpload";
		        if(scope.txt.length == 0){
		        	throw "BatchID - was not supplied";
		        }
		        var item = {"batchid" : scope.txt};
				fileupload.uploadforminfo(item, file);	
			}
			catch(err){
				$(".alert-danger").html("Error: " + err).show();
			}
		});
	};		
}]);

BatchShip2App.directive('onClickUpdateAddress', ['$document', 
function($document)
{
	return function(scope, element, attr)
	{
		element.click(function(event){
			scope.$parent.txt = scope.batch_item.id;
			scope.$apply();
			$(".updateaddresses").modal("show");
		});
	};		
}]);

BatchShip2App.directive('onValidateaddr', ['$document', 
function($document)
{
	return function(scope, element, attr)
	{
		if(scope.formData.batchid == undefined){
			
		}
		else{
			element.attr("readonly","readonly");
		}
	};		
}]);
BatchShip2App.directive('deleteFrombatchlist', function(){
	return {
		scope :{
			batchid : "=batchid"
		},
		link:function(){
			console.log("edededed");
		},
		template: "<button type='submit' name='btn_delete' class='btn btn-danger'> <span class='glyphicon glyphicon-floppy-remove'></span> Delete</button>"
	};
});
 /*  
  * UpdateBatchAddresses
  * Highlights - Rows in UpdateBatchAddresses
 */
BatchShip2App.directive('isValidated', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		scope :{
			batchid : "=batchid"
		},
		link:function(scope, element, attr){
			scope.hoverstate = false;
			switch(scope.$parent.address.verify_state){
				case "passed":		
					element.addClass('success');	
				break;
				case "not_passed":	
					element.addClass('warning');	
				break;
				case "error":		
					element.addClass('danger');		
				break;
			}
			/*
			element.hover(function(e){
            	//console.log("mousein");
            	element.addClass('info');
            }, function(e){
            	element.addClass('success');
            });*/
		}
	};		
}]);


BatchShip2App.directive('isProcessedbatch', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		scope :{
			batchid : "=batchid"
		},
		link:function(scope, element, attr){
			var it = scope.$parent.batch_item;
			switch(it.processed){
				case "t":
					element.addClass('success');
					$(element[0].cells[9]).html("<span class='glyphicon glyphicon-ok'></span>");
				break;
				case "f":	
					var text = (it.validation_passed != it.total_addresses) ? (it.validation_passed + "/" + it.total_addresses) : "<span class='alert-success'>validated</span>";
					$(element[0].cells[9]).html(text);	
				break;
			}
		}
	};		
}]);

BatchShip2App.directive('adrMod', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		template : "<address>" +
						"<strong>{{listaddress.type}}</strong><br/>" +
						"<div ng-show='(angular.isArray(listaddress.StreetLines)) == true'>{{listaddress.StreetLines[1]}}</div></address>",
		restrict: 'E',
        replace: true,
		link : function(scope, element, attr){
		
		}
	};
}]);
BatchShip2App.directive('propAddress', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		scope :{
			batchid : "=batchid"
		},
		//replace: true,
		//template: "<address><strong>{{listaddress.type}}</strong></address>",
		link:function(scope, element, attr){
			//scope.fulladdressprint = "<address><strong></strong></address>";
			//scope.$parent.listaddress.rest = "<b>" + scope.$parent.listaddress.type + "</b>";									
			//if(angular.isArray(scope.$parent.listaddress.streetlines)){
				
			//}
			//else{
				
			//}
			if(angular.isArray(scope.$parent.listaddress.StreetLines)){
				scope.$parent.listaddress.add1 = scope.$parent.listaddress.StreetLines[0];
				scope.$parent.listaddress.add2 = scope.$parent.listaddress.StreetLines[1];
				scope.$parent.listaddress.add3 = scope.$parent.listaddress.StreetLines[2];
				if(scope.$parent.listaddress.StreetLines.length == 2){
					scope.$parent.listaddress.add1 = "";
					scope.$parent.listaddress.add2 = scope.$parent.listaddress.StreetLines[0];
					scope.$parent.listaddress.add3 = scope.$parent.listaddress.StreetLines[1];
				}
			}
			else{
				scope.$parent.listaddress.add1 = "";
				scope.$parent.listaddress.add2 = scope.$parent.listaddress.StreetLines;
				scope.$parent.listaddress.add3 = "";
			}
			element.click(function(e){
				e.preventDefault();	
				//scope.$parent.updatecurrentaddress(scope.$parent.listofpropaddress[attr.id]);
				if(angular.isArray(scope.$parent.listaddress.StreetLines)){
					
					scope.$parent.currentAddress.address1 = scope.$parent.listaddress.StreetLines[0];
					scope.$parent.currentAddress.address2 = scope.$parent.listaddress.StreetLines[1];
					scope.$parent.currentAddress.address3 = scope.$parent.listaddress.StreetLines[2];
					if(scope.$parent.listaddress.StreetLines.length == 2){
						scope.$parent.currentAddress.address1 = "";
						scope.$parent.currentAddress.address2 = scope.$parent.listaddress.StreetLines[0];
						scope.$parent.currentAddress.address3 = scope.$parent.listaddress.StreetLines[1];
					}
					//console.log("fuck");
				}
				else{
					scope.$parent.currentAddress.address1 = "";
					scope.$parent.currentAddress.address2 = scope.$parent.listaddress.StreetLines;
					scope.$parent.currentAddress.address3 = "";
				}
				//scope.$parent.currentAddress.address2 = scope.$parent.listofpropaddress.StreetLines;
				scope.$parent.currentAddress.city = scope.$parent.listaddress.City;
				scope.$parent.currentAddress.state = scope.$parent.listaddress.StateOrProvinceCode;
				scope.$parent.currentAddress.postalcode = scope.$parent.listaddress.PostalCode;
				scope.$parent.currentAddress.country= scope.$parent.listaddress.CountryCode;	
					
				
				scope.$apply();
			});
		}
	};		
}]);
BatchShip2App.directive('continueProcess',
function () {
    return {
        link: function (scope, element, attrs) {
            element.click(function(e) {
                e.preventDefault();
               	console.log("ssddssaaa");
            });
        }
    };
});
BatchShip2App.directive('addthirdpartyaccountmodal',
function () {
    return {
        link: function (scope, element, attrs) {
            element.click(function(e) {
                e.preventDefault();
               	//console.log("ssddssaaa");
               	$(".addthirdpartyshipper").modal("show");
            });
        }
    };
});

BatchShip2App.directive('modifyAccountmodal',
function () {
    return {
        link: function (scope, element, attrs) {
            element.click(function(e) {
                e.preventDefault();
               	//console.log("ssddssaaa");
               	$(".UserUpdateForm").modal("show");
               	scope.userform.id = scope.$parent.userlist[scope.$index].id;
               	scope.userform.username = scope.$parent.userlist[scope.$index].username;
               	scope.userform.privaleges = scope.$parent.userlist[scope.$index].privaleges;
               	scope.userform.emailaddress = scope.$parent.userlist[scope.$index].email_address;
               	scope.$apply();
            });
        }
    };
});
//**************************************


BatchShip2App.directive('modifyThpModal', ['$document', '$rootScope', 
function($document, $rootScope) {
    return {
        link: function (scope, element, attrs) {
        	scope.addaccountmanual = false;
        	scope.addnewaccount = false;
            element.click(function(e) {
                e.preventDefault();
                
               	$(".ThirdPartyAddressForm").modal("show");
               	scope.thirdpartyadd.CompanyName = scope.thpitem.company_name;
               	scope.thirdpartyadd.Street = scope.thpitem.street;
                scope.thirdpartyadd.City = scope.thpitem.city;
                scope.thirdpartyadd.State = scope.thpitem.state;
                scope.thirdpartyadd.Country = scope.thpitem.country;
                scope.thirdpartyadd.ZipCode = scope.thpitem.zipcode;
                scope.thirdpartyadd.ID = scope.thpitem.id;
                console.log(scope.thirdpartyadd);
                scope.thirdpartyadd.service_accounts = scope.thpitem.service_accounts;
               	scope.$apply();
            });
        }
    };
}]);


BatchShip2App.directive('filejsonparse',
function () {
    return {
        link: function (scope, element, attrs) {
        	var filepatharray = angular.fromJson(scope.batch_item.filepathjson);
        	var text = "";
        	var temptext = "";
        	$.each(filepatharray, function(index, value){
        		var info = "";
        		switch(value.title){
        			case "input": info = "data-toggle='tooltip' data-placement='top' title='The file that was used to create the batch. " + value.filename+ "'"; 			break;
        			case "output_txt": info = "data-toggle='tooltip' data-placement='top' title='The file that was in processing the batch.'";		break;
        			case "output_xlsx": info = "data-toggle='tooltip' data-placement='top' title='The file after validation after processing.'";	break;
        		}
        		temptext = "<a href='batchship2/" + value.filepath + "'"+ info + ">" + value.title + "</a>";
        		text += (index > 0) ? ", " + temptext : temptext;
        	});
        	$(element.context).html(text + ", <a href='scripts/generateExcelfile.php?batchid=" + scope.batch_item.id +"&userid=" + "bmo" + "' data-toggle='tooltip' data-placement='top' title='This outputs what is currently in the database'>current</a>");
        	$('table tr td a').tooltip();
        	
        }
    };
});

BatchShip2App.directive('isProc', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		link:function(scope, element, attr){
			var it = scope.batch_item;
			var addressToBeValidated = it.total_addresses - it.validation_passed;
			switch(it.processed){
				case "t":
					$(element.context).html("<span class='glyphicon glyphicon-ok'></span>");
					$(element.context.parentNode).addClass('success');
				break;
				case "f":	
				
					var text = (it.validation_passed != it.total_addresses) ? "<span class='alert-warning'>" +(it.validation_passed + " of " + it.total_addresses + " addresses validated, <b>Action needs to be taken validate (" + addressToBeValidated + " addresses)</b></span>") : "<span class='alert-success'>Validated, <b>Action needs to be taken to Process this batch</b></span>";
					$(element.context).html(text);	
				break;
			}
		}
	};		
}]);
BatchShip2App.directive('isValim', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		link:function(scope, element, attr){
			switch(scope.thirdpartyitem.verify_state){
				case "passed":		
					element.addClass('success');	
				break;
				case "not_passed":	
					element.addClass('warning');	
				break;
				case "error":		
					element.addClass('danger');		
				break;
				default:
					element.addClass('warning');
			}
		}
	};		
}]);
BatchShip2App.directive('vfInd', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		link:function(scope, element, attr){
		//	if(element.context !== undefined)
			element.context.style = "color:green";
		}
	};		
}]);
BatchShip2App.directive('locChg', ['$document', '$rootScope', '$location', '$routeParams', '$timeout',
function($document, $rootScope, $location, $routeParams, $timeout)
{
	return {
		link:function(scope, element, attr){
			element.click(function(e) {
                e.preventDefault();
               // $('#myModal').on('hidden.bs.modal', function (e) {
                	
                //});
                $timeout(function(){
                	$location.path("/BatchShipRequests/"+$routeParams.batchid);
                scope.$apply();
                }, 2000);
                
            });
		}
	};		
}]);
BatchShip2App.directive('loginArea', ['$document', '$rootScope', '$location', '$routeParams', '$timeout', 'authService',
function($document, $rootScope, $location, $routeParams, $timeout, authService)
{
	return {
		link:function(scope, element, attr){
			scope.$watch("authdata", function(data){
				if(typeof(data.validated) === "undefined"){
					$(".login_element").show();
					$(".logout_element").hide();
				}
				else{
					switch(data.validated){
						case true:
							$(".login_element").hide();
							$(".logout_element").show();
						break;
						case false:
							$(".login_element").show();
							$(".logout_element").hide();
						break;
					}
				}
			});
		}
	};		
}]);
//5184740774 MF 8-12 MV8