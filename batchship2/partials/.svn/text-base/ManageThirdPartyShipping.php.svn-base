<div class="container" id="ManageThirdPartyAccounts" ng-controller="ManageThirdPartyAddress">
	<div ng-include="'./templates/ThirdPartyAddAddress.html'"></div>
	<div ng-include="'./templates/ThirdPartyModifyAddress.html'"></div>
	<div ng-include="'./templates/ValidateAddress.html'"></div>
	<h3>Manage Thirdparty Addresses</h3>
  	<div class="form-group">
		<label for="batchid">Search</label>
		<input type="text" name="batchid" class="form-control" ng-model="search.$" />
	</div>
	<button name="btn_delete" class="btn btn-danger" data-dismiss="modal" ng-click="onClickDeleteThirdParty()" ng-disabled="formData.thirdpartyid == ''">
		<span class="glyphicon glyphicon-floppy-remove" data-dismiss="modal"></span>
		Delete
	</button>
	<button name="btn_update" class="btn btn-primary" ng-click="onClickAddThpAdd()">
	 	<span class="glyphicon glyphicon-cloud-upload"></span>
		Add Account
	</button>
  	<table class="table">
  		<thead>
  			<tr>
  				<th>Modify</th>
  				<th>Company Name</th>
  				<th>Address</th>
  				<th>Carrier</th>
  				<th>Account #</th>
  			</tr>
  		</thead>
  		<tbody>
  			<tr ng-repeat="thirdpartyitem in thirdpartyAddresslist | filter:search" is-Valim>
  				<td>
  					<input type="checkbox" name="checkbox[]" ng-model="formData.thirdpartyid" ng-true-value="{{thirdpartyitem.id}}" ng-false-value=""/>
  				</td>
  				<td>
  					<a ng-click="onClickSelection($index)">{{thirdpartyitem.company_name}}</a>
  				</td>
  				<td>{{thirdpartyitem.street}}<br/>
  					{{thirdpartyitem.city}}{{(address.thirdparty_city.length > 0) ? ",": "" }} {{thirdpartyitem.state}} 
  					{{thirdpartyitem.zipcode}} {{thirdpartyitem.country}}
  				</td>	
  				<td>{{thirdpartyitem.carrier}}</td>
  				<td>{{thirdpartyitem.accountnumber}}</td>
  			</tr>
  		</tbody>
  		<tfoot></tfoot>
  	</table>
</div>

