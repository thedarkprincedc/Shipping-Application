<!--<script>
	/*loadGoogleMaps( 3, "AIzaSyAzejE7EzH8BSE1arIe1P70t0ruZphqe9A", "EN" )
		.done(function() {
			console.log("google maps loaded");
  			//!!google.maps // true
		}
	);*/
</script>
-->
<div class="Address_Controller container" ng-controller="UpdateAddressController" >
	<h3 ng-cloak>{{batchtitletext}}</h3>
	<div class="container">
		<div class="row">	
		</div>
		<div class="alert alert-success" style="display:none;" ></div>
		<div class="alert alert-danger" ng-bind="status" style="display:none;"></div>
	</div>
	<div class="row">
		<div class="col-xs-5">
			<div class="form-group">
				<label for="batchid">Search By Batch ID/Address</label>
				<input type="text" name="batchid" id="batchid" class="form-control"  ng-init="" ng-model="search.$" />	
			</div>
		</div>
		<div class="col-xs-2">
			<div class="form-group">
				<label for="batchid">Order By</label>
				<select class="form-control" ng-model="predicate" >
					<option></option>
					<option value="attn">Attn</option>
					<option value="shipping_method">Ship Method</option>
					<option value="state_province">State</option>
					<option value="country">Country</option>
				</select>	
			</div>
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
		<ul class="nav nav-tabs">
			<li class="active"><a href="#all" data-toggle="tab" address-Tabs>All Addresses <span class="badge ">{{info.total}}</span></a></li>
		  	<li><a href="#passed" data-toggle="tab" address-Tabs>Verified <span class="badge alert-success">{{info.validation_passed}}</span></a></li>
		  	<li><a href="#not_passed" data-toggle="tab" address-Tabs>Unverified <span class="badge alert-warning">{{info.validation_notpassed}}</span></a></li>
		  	<li><a href="#error" data-toggle="tab" address-Tabs>Failed <span class="badge alert-danger">{{info.validation_failed}}</span></a></li>
		</ul>
	</div>
	<div class="row">
		<div infinite-scroll="myPagingFunction(tab_search)" infinite-scroll-distance="2" infinite-scroll-immediate-check="false"></div>
		<table class="table">
			<thead>
			<tr>
				<th>Count</th>
				<th><a href="" ng-click="predicate = 'state_province'; reverse=!reverse">Ship Method</a></th>
				<th>Store Name</th>
				<th>Store ID</th>
				<th><a href="" ng-click="predicate = 'state_province'; reverse=!reverse">Attn/Address</a></th>
				<th>Phone Number</th>
				<th>Kit Number</th>
				<th>Wieght / Dimensions</th>
				<th>Misc Reference</th>
				<th>Insurance</th>
				<th>Third Party Address</th>
				<th>Third Party Account</th>
				<th><a href="" ng-click="predicate = 'shiptype'; reverse=!reverse">Intl/Domestic</a></th>
			</tr>
			</thead>
			<tbody>
				<tr ng-repeat="address in addresslist | orderBy:predicate:reverse" ng-click='onClickRow(address)' data-arrayid="{{$index}}" is-Validated>
					<td>{{$index+1}}</td>
					<td>{{address.shipping_method}}</td>
					<td>{{address.store_name}}</td>
					<td>{{address.store_number}}</td>
					<td>
						<address>
						  <strong>{{address.attn}}</strong><br>
						  <span ng-show="address.address1">{{address.address1}}<br/></span>
						  <span ng-show="address.address2">{{address.address2}}<br/></span>
						  <span ng-show="address.address3">{{address.address3}}<br/></span>
						  <span ng-show="address.city">{{address.city}},</span> <span ng-show="address.state_province">{{address.state_province}}<br/></span>
						  <span ng-show="address.postal_code">{{address.postal_code}}</span> <span ng-show="address.country">{{address.country}}</span>				
						</address>
					</td>
					<td>{{address.phone_number}}</td>
					<td>{{address.kit_number}}</td>
					<td>{{address.weight}}{{ (address.weight.length > 0) ? " / " : "" }}{{address.dimensions}}</td>
					<td>
						<span ng-show="address.misc_reference1">REF1# {{address.misc_reference1}}</span>
						<span ng-show="address.misc_reference2">REF2# {{address.misc_reference2}}</span>
						<span ng-show="address.misc_reference3">REF3# {{address.misc_reference3}}</span>
						<span ng-show="address.misc_reference4">REF4# {{address.misc_reference4}}</span>
						<span ng-show="address.misc_reference5">REF5# {{address.misc_reference5}}</span>
					</td>
					<td>{{address.insurance}}</td>
					<td>
						<address>
						  <strong>{{address.thirdparty_company}}</strong><br>
						  <span ng-show="address.thirdparty_street">{{address.thirdparty_street}}<br/></span>
						  <span ng-show="address.thirdparty_city">{{address.thirdparty_city}},</span> <span ng-show="address.thirdparty_statep">{{address.thirdparty_statep}}<br/></span>
						  <span ng-show="address.thirdparty_zip">{{address.thirdparty_zip}}</span> <span ng-show="address.thirdparty_country">{{address.thirdparty_country}}</span>				
						</address>
					</td>
					<td>{{address.thirdparty_account}}</td>
					<td ng-style="(address.country == 'US') ? {color:'blue'} : {color:'red'}">{{ address.shiptype = (address.country == "US") ? "Domestic" : "International"}}</td>
				</tr>	
			</tbody>
			<tfoot>
			</tfoot>
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