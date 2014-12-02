<div class="Address_Controller container" ng-controller="UpdateAddressController2">
	ewfwfwfwef
	<h3 ng-cloak>{{batchtitletext}}</h3>
	<div class="container">
		<div class="row">	
		</div>
		<div class="alert alert-success" style="display:none;" ></div>
		<div class="alert alert-danger" ng-bind="status" style="display:none;"></div>
	</div>
	<div class="row">
		<div class="col-xs-7">
			<!--<div class="form-group">
				<label for="batchid">Search By Batch ID/Address</label>
				<input type="text" name="batchid" id="batchid" class="form-control"  ng-init="" ng-model="search.$" />
			</div>-->
		</div>
		<div class="col-xs-5">
			<div class="form-group" ng-disabled='viewonly==true'>
				<label for="acceptcorrectedall">&nbsp;</label><br/>
				<button class='btn btn-warning' ng-click="acceptcorrectedall()" ng-disabled="passedvalidation==true">
					<span class="glyphicon glyphicon-wrench"></span>&nbsp;AutoCorrect All
				</button>
				<button class='btn btn-success' ng-click="markallasvalidated()" ng-disabled='passedvalidation==true'>
					<span class='glyphicon glyphicon-ok'></span>&nbsp;Mark All as validated
				</button>
				<button class='btn btn-primary' ng-disabled="passedvalidation==false" loc-Chg>
					<span class='glyphicon glyphicon-cloud-upload'></span>&nbsp;Process
				</button>
			</div>
		</div>
	</div>
	<div class="row">	
		<!--<ul class="nav nav-tabs">
			<li class="active"><a href="#all" data-toggle="tab" address-Tabs>All Addresses <span class="badge ">{{info.total}}</span></a></li>
		  	<li><a href="#passed" data-toggle="tab" address-Tabs>Verified <span class="badge alert-success">{{info.validation_passed}}</span></a></li>
		  	<li><a href="#not_passed" data-toggle="tab" address-Tabs>Unverified <span class="badge alert-warning">{{info.validation_notpassed}}</span></a></li>
		  	<li><a href="#error" data-toggle="tab" address-Tabs>Failed <span class="badge alert-danger">{{info.validation_failed}}</span></a></li>
		</ul>-->
	</div>
	<div class="row">
		<smart-table class="table table-striped" table-title="Smart Table example" columns="columnCollection" rows="rowCollection" config="globalConfig"></smart-table>
		<table class="table" st-table>
			<thead>
				<tr>
					
					<th st-sort="shipping_method">Ship Method</th>
					<th st-sort="store_name">Store Name</th>
					<th st-sort="store_number">Store ID</th>
					<!--<th>Attn/Address</th>-->
					<th st-sort="phone_number">Phone Number</th>
					<th st-sort="kit_number">Kit Number</th>
					<!--<th>Wieght / Dimensions</th>
					<th>Misc Reference</th>-->
					<th st-sort="insurance">Insurance</th>
					<!--<th>Third Party Address</th>-->
					<th st-sort="thirdparty_account">Third Party Account</th>
					<!--<th>Intl/Domestic</th>-->
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="address in addresslist">
					
					<td>{{address.shipping_method}}</td>
					<td>{{address.store_name}}</td>
					<td>{{address.store_number}}</td>
					
					<!--<td><b>{{address.attn}}</b><br/>
						{{address.address1}}{{address.address2}}{{address.address3}}<br/>
						{{address.city}}, {{address.state_province}}<br/>
						{{address.postal_code}} {{address.country}}
					</td>-->
					<td>{{address.phone_number}}</td>
					<td>{{address.kit_number}}</td>
					<!--<td>{{address.weight}}{{ (address.weight.length > 0) ? " / " : "" }}{{address.dimensions}}</td>-->
					<!--<td>
						{{address.misc_reference1}}
						{{address.misc_reference2}}
						{{address.misc_reference3}}
						{{address.misc_reference4}}
						{{address.misc_reference5}}
					</td>-->
					<td>{{address.insurance}}</td>
					<!--<td><b>{{address.thirdparty_company}}</b><br/>
						{{address.thirdparty_street}}<br/>
						{{address.thirdparty_city}}{{(address.thirdparty_city.length > 0) ? ", " : "" }}{{address.thirdparty_statep}}<br/>
						{{address.thirdparty_zip}} {{address.thirdparty_country}}
					</td>-->
					<td>{{address.thirdparty_account}}</td>
					<!--<td ng-style="(address.country == 'US') ? {color:'blue'} : {color:'red'}">{{ (address.country == "US") ? "Domestic" : "International"}}</td>-->
				</tr>	
			</tbody>
			
		</table>
	</div>
</div>
<!-- generic modal -->
<div class="Address_Controller_Select" ng-controller="UpdateAddressControllerSelect">
	<div ng-include="'./templates/ValidateAddress.html'"></div>
	<div class="modal fade bs-example-modal-sm" id="continueModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content">
	    	<div class="modal-header"></div>
	    	<div class="modal-body">All Records in this batch were validated.</div>
	    	<div class="modal-footer">
	    		<button type='submit' class='btn btn-default' data-dismiss='modal'>Close</button>
	    		<button type='submit' class='btn btn-primary' data-dismiss='modal' loc-Chg>
	    			<span class='glyphicon glyphicon-cloud-upload'></span>&nbsp;Process
	    		</button>
	    	</div>
	    </div>
	  </div>
	</div>
</div>
	<!-- END -->