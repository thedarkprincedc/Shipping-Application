BatchShip2Controllers.factory('batchShipService', function($http, $q, $rootScope) {
    var service ={};
    service.upload_and_validate = function(data){
    	$http({
            method: 'POST',
            url: './scripts/processBatchShipItemsForm.php?action=uploadandprocess',
            headers: {	'Content-Type': 'multipart/form-data'	},
            data: {
                email: "brettmosley@hotmail.com",
                token: "11111111111111111111111",
                upload: $scope.file
            },
            transformRequest: function (data, headersGetter) {
                var formData = new FormData();
                angular.forEach(data, function (value, key) {
                    formData.append(key, value);
                });
                var headers = headersGetter();
                delete headers['Content-Type'];
                return formData;
            }
       });
    /*	$http({
            method: 'POST',
            url: './scripts/processBatchShipItemsForm.php?action=uploadandprocess',
            headers: {	'Content-Type': 'multipart/form-data'	},
            data: {
                email: Utils.getUserInfo().email,
                token: Utils.getUserInfo().token,
                upload: $scope.file
            },
            transformRequest: function (data, headersGetter) {
                var formData = new FormData();
                angular.forEach(data, function (value, key) {
                    formData.append(key, value);
                });

                var headers = headersGetter();
                delete headers['Content-Type'];

                return formData;
            }
        })
        .success(function (data) {

        })
        .error(function (data, status) {

        });
    	
    	
    	
    	var upload_batch = function(data){
    		
    		return "A";
    	}, validate_batch = function(message){
    		return "B";
    	};
    	return upload_batch()
    		.then(validate_batch(message));*/
    };
    service.initialize = function(){
    	var initialize_shippingTypes = function(){
    		return service.getShippingTypesInfo().then(function(message){
		  		var shippingtypelist = [];
		  		//initialize shipping types
		  		//$log.debug("Bootstrapping..UPS");
			  	$.each(message.shipping_method[0].options, function(index, value){ 	
			  		value.company = "UPS";
			  		value.label = value.name + " - " + value.value;
			  		shippingtypelist.push(value);	
			  	});
			  //	$log.debug("Bootstrapping..FEDEX");
			  	$.each(message.shipping_method[1].options, function(index, value){ 
			  		value.company = "FEDEX";
			  		value.label = value.name + " - " + value.value;
			  		shippingtypelist.push(value);
			  	});
			  	$rootScope.shippingtypelist = shippingtypelist;
			  	//initialize state info
			  //$log.debug("Bootstrapping..State Abbv1");
			  	service.getstateinfo().then(function(message){
					$rootScope.stateabbv = message;
				});
				//$log.debug("Bootstrapping..State Abbv2");
				service.getstateinfo2().then(function(message){
					$rootScope.statelisting = message;
				});
				//initialize country info
				//$log.debug("Bootstrapping..Country Abbv1");
				service.getcountryinfo().then(function(message){
					$rootScope.countrylisting = message;
				});  	
			});
    	}, initialize_menuItems = function(){
    		return service.getmenuitems().then(function(data){
				$rootScope.menuitems = data.menu;
			});
    	}, initialize_thirdpartyshipping = function(){
    		return service.getThirdPartyShipInfo().then(function(data){
    			$rootScope.thirdpartyshippingitems = data.data;
    		});
    	};
    	return initialize_shippingTypes()
	    		.then(initialize_menuItems()
	    		.then(initialize_thirdpartyshipping())
    		);
    };
   
    
    
  	service.markallasvalidated = function(batchid){
  		return $http.get('./scripts/processBatchShipItemsForm.php?action=markallasvalidated&batchid=' + batchid)
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				console.log(response.data);
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
  	};
    service.getShippingTypesInfo = function(){
    	return $http.get('./js/shippingmethods.json')
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				console.log(response.data);
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
    
       service.modifyUserInfo = function(data){
    	return $http.post('./scripts/processBatchShipItemsForm.php?action=modifyuserinfo',data)
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				console.log(response.data);
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
      service.addThirdPartyShipInfo = function(data){
    	return $http.post('./scripts/processBatchShipItemsForm.php?action=addthirdpartyshipinfo',data)
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
     service.modifyThirdPartyShipInfo = function(data){
    	return $http.post('./scripts/processBatchShipItemsForm.php?action=modifythirdpartyshipinfo',data)
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				console.log(response.data);
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
    service.deleteThirdPartyShipInfo = function(data){
    	return $http.post('./scripts/processBatchShipItemsForm.php?action=deletethirdpartyshipinfo',data)
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				console.log(response.data);
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
     service.getThirdPartyShipInfo = function(){
    	return $http.get('./scripts/processBatchShipItemsForm.php?action=getThirdPartyShipInfo')
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
    service.dateIsHoliday = function($date){
    	return $http.get('./scripts/processBatchShipItemsForm.php?action=isholiday&datestring=' + $date)
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
     service.getPONumbersByBatchID = function($batchid){
    	return $http.get('./scripts/processBatchShipItemsForm.php?action=getponumbersbybatch&batchid=' + $batchid)
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
    // Customer Information
    service.lookupCustomerInfoByJob = function($jobnumber){
    	return $http.get('./scripts/lookupCustomerByJob.php?jobnumber=' + $jobnumber)
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
    
    service.getmenuitems = function(){
    	return $http.get('js/navmenu.json')
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
    
    
     service.getstateinfo2 = function(){
    	return $http.get('content/states_titlecase.json')
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
       service.getcountryinfo = function(){
    	return $http.get('content/JSONCountries.json')
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
      service.getstateinfo = function(){
    	return $http.get('content/states_hash.json')
    	.then(function(response){
    			if(typeof response.data === 'object'){
    				return response.data;
    			}else{
    			 // invalid response
               return $q.reject(response.data);
              }
    		}, function(response){
    		// something went wrong
            return $q.reject(response.data);	
    	});
    };
    // Batch Operations
    service.processbatch = function(batchid){
    	return $http.get('./scripts/processBatchShipItemsForm.php?action=processbatchitem&batchid=' + $batchid)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
    };
    //Consolidated Shipping Services
     service.validateshippingByBatch = function(batchid){
    	return $http.get('./scripts/processBatchShipItemsForm.php?action=validateAddress&batchid=' + batchid)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
    };
    service.validateshippingBySingleAddress = function(addressid){
    	return $http.get('./scripts/processBatchShipItemsForm.php?action=validateAddress&addressid=' + addressid)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
    };
	service.deletebatch = function($batchid){
		 return $http.get('./scripts/processBatchShipItemsForm.php?action=deletebatchitem&batchid=' + $batchid)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	service.getBatchesByUserIDAndPrivs = function(userid, privs){
		return $http.get('./scripts/processBatchShipItemsForm.php?action=getBatchItemListByUserID&userid=' + userid + "&privs=" + privs)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	service.updatebatches = function($batchid){
		return $http.get('./scripts/processBatchShipItemsForm.php?action=update&batchid=' + $batchid)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	service.getAddressesByWhere = function(addresswherejson){
		return $http.post('./scripts/processBatchShipItemsForm.php?action=showaddressesbywhere', addresswherejson)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};

	service.getAddressesByBatchAndStatus = function(batchid, status){
		return $http.get('./scripts/processBatchShipItemsForm.php?action=showaddressesbybatchandstate&batchid=' + batchid + "&status=" + status)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	service.getaddressbatchinfo = function(batchid){
		return $http.get('./scripts/processBatchShipItemsForm.php?action=getaddressbatchinfo&batchid=' + batchid)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	
	// Address Operations
	service.updateaddresses = function(batchid){
		return $http.get('./scripts/processBatchShipItemsForm.php?action=showaddresses&batchid=' + batchid)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	
	service.delete_address = function(addressid){
		return $http.get('./scripts/processBatchShipItemsForm.php?action=deleteaddressitem&addressid=' + addressid)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	service.save_address = function(addressJSONData){
		return $http.post('./scripts/processBatchShipItemsForm.php?action=update_singleaddress', addressJSONData)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	service.takecorrectedaddress = function(addressid){
		return $http.get('./scripts/processBatchShipItemsForm.php?action=update&batchid=all')
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	service.takecorrectedall = function(batchid){
		return $http.get('./scripts/processBatchShipItemsForm.php?action=batchtakepropadd&batchid=' + batchid)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	service.eventlogupdate = function(){
		return $http.get('./scripts/eventlogging.php')
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data.items;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	service.getthirdpartyvars = function(){
		return $http.get('./scripts/thirdpartybilling.php')
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	// Process BatchshipRequest
	service.processbatchshiprequest = function(formdata){
		return $http.post('./scripts/processBatchShipItemsForm.php?action=processbatchshiprequest', formdata)
            .then(function(response) {
                if (typeof response.data === 'object') {
                	console.log(response.data);
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
	};
	
	
	return service;
});

BatchShip2Controllers.factory('authService', function($http, $q) {
    var service ={};
    var startpages = {
    		"admin" : "/Admin",
    		"user" : "/User"
    	};
    service.authinfo = null;
    service.authenticate = function(creds){
    	 return $http.post('./scripts/loginad.php?action=login', creds)
            .then(function(response) {
                if (typeof response.data === 'object') {
                	service.authinfo = response.data.data;
                    return response;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
    };
    service.authenticate_cookie = function(){
    	return $http.post('./scripts/loginad.php?action=getsessiondata')
            .then(function(response) {
                if (typeof response.data === 'object') {
                	//service.authinfo = response.data.data;
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
    };
    service.isloggedin = function(){
    	return $http.get('./scripts/loginad.php?action=isloggedin')
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        });
    	//return (service.authinfo == null) ? false : service.authinfo.validated;
    };
    service.registeremail = function(){
    	return $http.get('./scripts/loginad.php?action=getuserinfo&userid=all')
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        });
    };
    service.updateuserlist = function(){
    	return $http.get('./scripts/loginad.php?action=getuserinfo&userid=all')
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        });
    };
    service.getuserbyid = function(id){
    	return $http.get('./scripts/loginad.php?action=getuserinfo&userid=' + id)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        });
    };
    service.deleteuser = function(userid){
    	return $http.post('./scripts/loginad.php?action=removeuser', userid)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
    };
     service.setsessiondata = function(userdefinedjson){
    	return $http.post('./scripts/loginad.php?action=setsessiondata', userdefinedjson)
            .then(function(response) {
                if (typeof response.data === 'object') {
                    return response.data;
                } else {
                    // invalid response
                    return $q.reject(response.data);
                }
            }, function(response) {
                // something went wrong
                return $q.reject(response.data);
        	});
    };
    return service;
});

/*
 * FileUpload Factory
 * 
 * 
 */
BatchShip2Controllers.factory('fileUpload', ["$http", '$rootScope', 
function($http, $rootScope) {
		var service = {};
		service.uploadforminfo = function(vals, file){
			var fd = new FormData();
			fd.append('file', file);
			$.each(vals, function(index, value){
				fd.append(index, value);
			});
			ELoadStart();
			$http.post("./scripts/processBatchShipItemsForm.php?action=uploadandprocess", fd, {
            	transformRequest: angular.identity,
            	headers: {'Content-Type': undefined}
        	})
        	.success(function(message){
        		ELoadFinish();
        		$rootScope.$broadcast('file:uploaded', "emit");
        		console.log("success " + message);
        	})
        	.error(function(message){
        		ELoadFinish();
        		console.log("failure");
        	});
		};
		return service;
}]);