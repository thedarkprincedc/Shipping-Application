<div class="container">
	<div id="myTab">
	<!-- Nav tabs -->
		<ul class="nav nav-tabs">
			<li><a show-tab href="#ManageBatchesAdmin" data-toggle="tab">Manage Batches</a></li>
		  	<li><a show-tab href="#ManageAccounts" data-toggle="tab">Manage Accounts</a></li>
		  	<li><a show-tab href="#ManageLog" data-toggle="tab">Manage Log</a></li>
		 	<li><a show-tab href="#ManageThirdPartyAccounts" data-toggle="tab">Manage Third Party Accounts</a></li>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content">	
			<div class="tab-pane fade in active" id="ManageBatchesAdmin">
			<!-- Start Manage Batches-->
			<div class="container" ng-controller="ManageBatchController">
				<form method="post" name="ManageBatches" id="ManageBatches" role="form">
					<h3>Manage Batches</h3>
					<div class="alert alert-success" style="display:none;"></div>
					<div class="alert alert-danger" style="display:none;"></div>
					<table class="table">
						<thead>
							<tr>
								<th>Modify</th>
								<th>BatchID</th>
								<th>FCP Job#</th>
								<th>Company Name</th>
								<th>Uploaded By</th>
								<th>Uploaded Timestamp</th>
								<th>Files</th>
								<th>File Path</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="batch_item in batchlist">
								<td>
									<input type="checkbox" name="cb_checkbox[]" ng-click="onSelectMod(this)" ng-model="formData.batchid" ng-true-value="{{batch_item.id}}" />
								</td>
								<td><a href="#/UpdateBatchAddresses/{{batch_item.id}}">{{batch_item.id}}</a></td>
								<td><a href="#/BatchShipRequests/{{batch_item.id}}">{{batch_item.fcp_jobnumber}}</a></td>
								<td ng-bind="batch_item.company_name"></td>
								<td ng-bind="batch_item.username"></td>
								<td ng-bind="batch_item.uploaded_timestamp"></td>
								<td><a href="{{batch_item.filepath}}">{{batch_item.filename}}</a></td>
								<td ng-bind="batch_item.filepath"></td>
								<td is-Proc></td>			
							</tr>
						</tbody>	
						<tfoot></tfoot>
					</table>
					<div class="ManageBatchControls">
						<input type="hidden" name="hf_btn_selected" value="" />
						<button name="btn_delete" ng-click="onClickDelete()" class="btn btn-danger">
							 <span class="glyphicon glyphicon-floppy-remove"></span>
							Delete
						</button>
					</div>
				</form>
			</div>
			<!-- End Manage Batches -->
			</div>
			
		 	<div class="tab-pane fade" id="ManageAccounts">
		 	
		 	<!-- Start Manage Users-->
			<div class="container" ng-controller="ManageUsersController">
				<form method="post" role="form" ng-submit="onSubmit()">
				<h3>Manage Users</h3>
				<div class="alert alert-success" style="display:none;"></div>
				<div class="alert alert-danger" style="display:none;" ></div>
				<div class="form-group">
					<label for="searchusers">Search Users</label>
					<input type="text" name="searchusers" class="form-control" ng-model="search.$" />
				</div>
				<table class="table">
					<thead>
						<tr>
							<th>Modify</th>
							<th>ID</th>
							<th>Username</th>
							<th>Firstname</th>
							<th>Lastname</th>
							<th>EmailAddress</th>
							<th>Privaleges</th>
						</tr>
					</thead>
					<tbody>
						<tr ng-repeat="user in userlist | filter:search:strict">
							<td><input type="checkbox" ng-model="formData.selectedid" ng-true-value="{{user.id}}" /></td>
							<td ng-bind="user.id"></td>
							<td> <a modify-Accountmodal>{{user.username}}</a></td>
							<td ng-bind="user.first_name"></td>
							<td ng-bind="user.last_name"></td>
							<td ng-bind="user.email_address"></td>
							<td ng-bind="user.privaleges"></td>
						</tr>
					</tbody>
					<tfoot></tfoot>
				</table>
				<div class="ManageUserCountrols">
					<button name="btn_delete" ng-click="onDeleteUser()" class="btn btn-danger">
						<span class="glyphicon glyphicon-floppy-remove"></span>
						Delete
					</button>
				</div>
			</form>
	
			<!-- generic modal -->
			<form name="UserUpdateForm" id="UserUpdateForm" role="form">
			<div class="modal fade UserUpdateForm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
			  <div class="modal-dialog modal-sm">
			    <div class="modal-content">
			    	<!--<div class="modal-header"></div>-->
			    	<div class="modal-body">
			    		<!--<div class="alert alert-success" role="alert"></div>
						<div class="alert alert-info" role="alert"></div>
						<div class="alert alert-warning" role="alert"></div>
						<div class="alert alert-danger" role="alert"></div>-->
						<h3>Manage Users - Update</h3>
						<div class="row">
							<div class="col-xs-12">
								<div class="form-group">
									<label for="street" class="sr-only">Username</label>
									<input type="text" class="form-control" name="username" id="username" ng-model="userform.username" placeholder="UserName" required/>
								</div>
								<div class="form-group">
									<label for="street" class="sr-only">Email Address</label>
									<input type="text" class="form-control" name="emailadd" id="emailadd" ng-model="userform.emailaddress" placeholder="Eamil Address" required/>
								</div>
					    		<div class="form-group">
									<label for="permissions" class="sr-only">Permissions</label>
									<select class="form-control" id="privaleges" ng-model="userform.privaleges">
										<option>admin</option>
										<option>user</option>
									</select>
								</div>
							</div>
						</div>
								
			    	</div>
			    	<div class="modal-footer">
			    		<button name="btn_cancel" class="btn btn-default" data-dismiss="modal">
							Cancel
						</button>
			    		<button name="btn_update" class="btn btn-danger" data-dismiss="modal" ng-click="onModifyUserInfo()">
						 	<span class="glyphicon glyphicon-cloud-upload"></span>
							Update
						</button>
			    	</div>
			    </div>
			  </div>
			</div>
			</form>
			<!-- END generic modal-->
		</div>
		<!-- End Manage Users -->
		</div>
		
		<div class="tab-pane fade" id="ManageLog">
		<!-- Manage Event Log -->
		  		
	<div class="container" ng-controller="ManageEventLogController">
	<h3>View Log</h3>
	<div class="alert alert-success" style="display:none;"></div>
	<div class="alert alert-danger" style="display:none;"></div>
	<div class="form-group">
		<label for="batchid">Search</label>
		<input type="text" name="batchid" class="form-control" ng-model="search.$" />
	</div>
	<table class="table">
	<thead>
		<tr>
		<th>ID</th>
		<th>Subject</th>
		<th>Description</th>
		<th>BatchID</th>
		<th>Time</th>
		<th>User</th>
		</tr>
	</thead>
	<tbody>
		<tr ng-repeat="logitem in events | filter:search | limitTo: 10 ">
			<td>{{logitem.id}}</td>
			<td ng-bind="logitem.subject"></td>
			<td ng-bind="logitem.description"></td>
			<td ng-bind="logitem.batch_id"></td>
			<td ng-bind="logitem.timestamp"></td>
			<td ng-bind="logitem.user_id"></td>
		</tr>
	</tbody>
	<tfoot></tfoot>
	</table>
</div>	  		
<!-- End Manage Event Log-->	
		  	</div>
		  	<div class="tab-pane fade" id="ManageThirdPartyAccounts">
		  	<div class="container" ng-controller="ManageThirdPartyAddress">
		  	<h3>Manage Thirdparty Addresses</h3>
		  	<div class="form-group">
				<label for="batchid">Search</label>
				<input type="text" name="batchid" class="form-control" ng-model="search.$" />
			</div>
		  	<table class="table">
		  		<thead>
		  			
		  			<tr>
		  				<th>Modify</th>
		  				<th>Company Name</th>
		  				<th>Address</th>
		  				<th>Accounts</th>
		  			</tr>
		  		</thead>
		  		<tbody>
		  			<tr ng-repeat="thpitem in thirdpartyinfo | filter:search ">
		  				<td><input type="checkbox" ng-model="formData.selectedid" ng-true-value="{{thpitem.id}}" /></td>
		  				<td><a modify-Thp-Modal data-toggle='tooltip' data-placement='top' title='Click here to edit company information'>{{thpitem.company_name}}</a></td>
		  				<td>
		  					{{thpitem.street}}<br/>
		  					{{thpitem.city}}, {{thpitem.state}} {{thpitem.zipcode}}
		  				</td>
		  				<td format-Service></td>
		  			</tr>
		  		</tbody>
		  		<tfoot></tfoot>
		  	</table>
		  	<button name="btn_cancel" class="btn btn-default" data-dismiss="modal">
				Delete
			</button>
    		<button name="btn_update" class="btn btn-primary" data-dismiss="modal">
			 	<span class="glyphicon glyphicon-cloud-upload"></span>
				Add Account
			</button>
			
			<!-- generic modal -->
	<form name="ThirdPartyAddressForm" id="ThirdPartyAddressForm" role="form">
	<div class="modal fade ThirdPartyAddressForm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-lg">
	    <div class="modal-content">
	    	<!--<div class="modal-header"></div>-->
	    	<div class="modal-body">
	    		<!--<div class="alert alert-success" role="alert"></div>
				<div class="alert alert-info" role="alert"></div>
				<div class="alert alert-warning" role="alert"></div>
				<div class="alert alert-danger" role="alert"></div>-->
				<h3>Manage Third Party Addresses - Update</h3><br/>
				<div class="row">
					<div class="col-xs-6">
						<div class="form-group">
							<label for="street" class="sr-only">Company Name</label>
							<input type="text" class="form-control" name="companyname" id="companyname" ng-model="thirdpartyadd.CompanyName" placeholder="Company Name" required/>
						</div>
			    		<div class="form-group">
							<label for="street" class="sr-only">Street</label>
							<input type="text" class="form-control" name="street" id="street" ng-model="thirdpartyadd.Street" placeholder="Street" required/>
						</div>
						<div class="form-group">
							<label for="city" class="sr-only">City</label>
							<input type="text" class="form-control" name="city" id="city" ng-model="thirdpartyadd.City" placeholder="City" required/>
						</div>
						<div class="row">
							<div class="col-xs-3">
								<div class="form-group">
									<label for="state" class="sr-only">State</label>
									<input type="text" class="form-control" name="state" id="state" ng-model="thirdpartyadd.State" placeholder="State" required/>
								</div>
							</div>
							<div class="col-xs-6">
								<div class="form-group">
									<label for="zip" class="sr-only">Zip</label>
									<input type="text" class="form-control" name="zip" id="zip" ng-model="thirdpartyadd.ZipCode" placeholder="Zip" required/>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-6">
								<div class="form-group">
									<label for="country" class="sr-only">Country</label>
									<input type="text" class="form-control" name="country" id="country" ng-model="thirdpartyadd.Country" placeholder="Country" required/>
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-xs-6">
						<!-- Row of carriers-->
						<div ng-repeat="thaddservice in thirdpartyadd.service_accounts">
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group">
										<label for="Carrier">Carrier</label>
										<input type="text" class="form-control" name="Carrier" id="Carrier" ng-model="thaddservice.service_name" data-id="{{thaddservice.id}}" placeholder="Carrier" required/>
									</div>
								</div>
								<!-- modify -->
								<!--
								<div class="col-xs-1">
									<button name="btn_modify" class="btn btn-danger modifycarrier_btn" data-id="{{thaddservice.id}}" ng-click="onClickModify(thaddservice.id)">
									 	<span class="glyphicon glyphicon glyphicon-pencil"></span>
									</button>
								</div>
								-->
								<!-- delete -->
								<!--
								<div class="col-xs-1">
									<button name="btn_remove" class="btn btn-danger removecarrier_btn" data-id="{{thaddservice.id}}" ng-click="onClickRemove(thaddservice.id)">
									 	<span class="glyphicon glyphicon glyphicon-remove"></span>
									</button>
								</div>-->
							</div>
						
							<div class="row">
								<div class="col-xs-6">
									<div class="form-group">
										<label for="AccountNumber">AccountNumber</label>
										<input type="text" class="form-control" name="AccountNumber" id="AccountNumber" ng-model="thaddservice.account_name" data-id="{{thaddservice.id}}" placeholder="AccountNumber" required/>
									</div>
								</div>
							</div>
							
							
						</div>
				<!-- -->
					<!-- Carrier Controls -->
					<!--
				<button name="btn_addcarrier" class="btn btn-danger addcarrier_btn" ng-click="onClickAddCarrier()">
				 	<span class="glyphicon glyphicon glyphicon-plus"></span>
				</button>
				-->
				<!-- -->
						
						
						
					</div>
				</div>		
	    	</div>
	    	<div class="modal-footer">
	    		<button name="btn_cancel" class="btn btn-default" data-dismiss="modal">
					Cancel
				</button>
	    		<button name="btn_update" class="btn btn-danger" data-dismiss="modal" ng-click="onClickUpdate()">
				 	<span class="glyphicon glyphicon-cloud-upload"></span>
					Update
				</button>
	    	</div>
	    </div>
	  </div>
	</div>
	</form>
	<!-- END generic modal-->
	
			</div>
		  	</div>	  	
		</div>
	</div>
	
	
	
	
</div>

