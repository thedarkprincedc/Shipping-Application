// General Directives
BatchShip2App.directive('zipcodeCorrection', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		require: "ngModel",
		scope: {
			'zipcode' : "=ngModel"
		},
		link:function(scope, element, attr, ngModel){
            element.bind("blur", function(){       		
            	scope.$apply(function(){
            		if(typeof(scope.$parent.sharedState) != "undefined"){
            			if(scope.$parent.sharedState == "PR"){
            				// Drop the last 4 digits
            				//scope.zipcode = scope.phonenumber.replace(/ |-|/g,"");
            				
            			}
            		}
            		// remove - and / and .
            		//scope.phonenumber = scope.phonenumber.replace(/ |-|\.|\(|\)/g,"");
            		
            		// if phone number is < 10 digits
            		//if(scope.phonenumber.length == 7){
            		//	scope.phonenumber = "585" + scope.phonenumber;
            		//}
            	});          
            });		
		}
	};		
}]);

BatchShip2App.directive('phonenumberCorrection', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		require: "ngModel",
		scope: {
			'phonenumber' : "=ngModel"
		},
		link:function(scope, element, attr, ngModel){
            element.bind("blur", function(){       		
            	scope.$apply(function(){
            		// remove - and / and .
            		scope.phonenumber = scope.phonenumber.replace(/ |-|\.|\(|\)/g,"");
            		
            		// if phone number is < 10 digits
            		if(scope.phonenumber.length == 7){
            			scope.phonenumber = "585" + scope.phonenumber;
            		}
            	});          
            });		
		}
	};		
}]);
BatchShip2App.directive('weightCorrection', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		require: "ngModel",
		scope: {
			'weight' : "=ngModel"
		},
		link:function(scope, element, attr, ngModel){
            element.bind("blur", function(){       		
            	scope.$apply(function(){
            		scope.weight = Math.round(scope.weight * 10) / 10; 
            	});          
            });		
		}
	};		
}]);
BatchShip2App.directive('dimensionCorrection', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		require: "ngModel",
		scope: {
			'dimension' : "=ngModel"
		},
		link:function(scope, element, attr, ngModel){
            element.bind("blur", function(){       		
            	scope.$apply(function(){
            		var dimen = scope.dimension;
            		//dimen = dimen.replace(" ","");
            		scope.dimension = scope.dimension.replace(/ |i|n/g,"");
            		//scope.weight = Math.round(scope.weight * 10) / 10; 
            	});          
            });		
		}
	};		
}]);
BatchShip2App.directive('countryCorrection', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		require: "ngModel",
		scope: {
			'countryName' : "=ngModel"	
		},
		link:function(scope, element, attr, ngModel){
			// = 
			scope.$watch(function(){
				scope.$parent.sharedCountry = scope.countryName;
			});
			
            element.bind("blur", function(){       		
            	scope.$apply(function(){
            		$.each(scope.$root.countrylisting, function(index, value){
	          			if(angular.equals(value.name.toLowerCase(), scope.countryName.toLowerCase()) == true ){
	          				scope.countryName = value.abbreviation;
	          				return;
	          			}
	          		});
            		if(ngModel.$modelValue.toLowerCase() == "usa"){
						scope.countryName = "US";
					}
					if(scope.countryName.length > 2){
						console.log("not a valid country");
					}
					
            	});          
            });		
		}
	};		
}]);
BatchShip2App.directive('stateCorrection', ['$document', '$rootScope',
function($document, $rootScope)
{
	return {
		require: "ngModel",
		scope: {
			'stateName' : "=ngModel" 
		},
		link:function(scope, element, attr, ngModel){			
			scope.$watch(function(){
				scope.$parent.sharedState = scope.stateName;		
			});
            element.bind("blur", function(){    
            	if(typeof(scope.$parent.sharedCountry) != "undefined"){
            	scope.$apply(function(){
            		if(scope.$parent.sharedCountry.toLowerCase() == "us"){
						$.each(scope.$root.statelisting, function(index, value){
							if(value.name.toLowerCase() == scope.stateName.toLowerCase() ){
								scope.stateName = value.abbreviation;
								return;
							}
						});
					}
					if(scope.stateName.length > 2){						
						console.log("not a valid state");
					}
            	}); 
            	}     
            });		
		}
	};		
}]);
// Page Specific Directives
BatchShip2App.directive('addressTabs', ['$filter', '$rootScope',
function ($filter, $rootScope) {
    return {
        link: function (scope, element, attrs) {
            element.click(function(e) {
                e.preventDefault();
                scope.$apply(function(){
	                scope.tab_search = attrs.href.slice(1, attrs.href.length);
	                scope.resetPaging();
	                scope.myPagingFunction(scope.tab_search);
                });
            });
        }
    };
}]);
// Applies the date picker input object for the batchship request form
BatchShip2App.directive('datepick', ['$document', '$rootScope', 'batchShipService',
function($document, $rootScope, batchShipService)
{
	return {
		require : "ngModel",
		scope : {
			"date" : "=ngModel"
		},
		link:function(scope, element, attr){
			var nowTemp = new Date();
			scope.now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
			element.datepicker(
				{
					onRender : function(date) {
						return date.valueOf() < scope.now.valueOf() ? 'disabled' : '';
					}
				}
			).on("changeDate", function(event){
				element.parent(".form-group").removeClass("has-error has-success has-warning");
				$(".status").html("");
				try{
						var inputdate = moment(event.currentTarget.value);
						if(inputdate.day() == 0) // is not sunday
							throw "This date is on a sunday";
						batchShipService.dateIsHoliday(event.currentTarget.value).then(
							function(message){
								
								if(message.data.isholiday){
									element.parent(".form-group").addClass("has-warning");
									$(".status").html(" *Warning: This date is on a holiday*");
									scope.date = event.currentTarget.value;
									element.datepicker('hide');
								}
								else{
									element.parent(".form-group").addClass("has-success");
									scope.date = event.currentTarget.value;
									element.datepicker('hide');
							}
						});
				}
				catch(e){
					element.parent(".form-group").addClass("has-error");
					$(".status").html(" *Error: " + e + "*");
				
				}	
			});
		}
	};		
}]);