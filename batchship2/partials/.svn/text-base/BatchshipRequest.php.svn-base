<div class="container" ng-controller="BatchshipRequestController">
	<div ng-include="'./templates/ThirdPartyAddAddress.html'"></div>
	<h3>Process Batchship Request</h3>
	<!--<pre>{{formData}}</pre>-->
	<div class="alert alert-success" ng-bind="status" style="display:none;"></div>
	<div class="alert alert-danger" ng-bind="status" style="display:none;" ></div>
	<div class="alert alert-info" ng-bind="status" style="display:none;" ></div>
	<div class="alert alert-warning" ng-bind="status" style="display:none;"></div>
	<form method="post" id="batchshiprequest" role="form">
		<div class="row">
			<div class="col-xs-10">
				<div class="row">
					<div class="col-xs-5">
						<div class="form-group">
				  		<label class="control-label" for="companyname">Company Name</label>
				  		<input type="text" class="form-control" id="companyname" ng-model="formData.companyname" readonly="readonly" />
						</div>
					</div>
					<div class="col-xs-2">
						<div class="form-group has-success">
						  <label class="control-label" for="jobnumber">FCP Job#</label>
						  <input type="text" class="form-control"  id="jobnumber" ng-model="formData.jobnumber" readonly="readonly"/>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
					  		<label class="control-label" for="ponumber">PO Number</label>
					  		<input type="text" class="form-control"  id="ponumber" ng-model="formData.ponumber" />
					  		<!--<label><input type="checkbox" name="formData.useformponumbers" ng-model="formData.useformponumbers">use form PO numbers</label>-->
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-5">
						<div class="form-group">
				  			<label class="control-label" for="numOfAddresses"># of Addresses Validated</label>
				  			<input type="text" class="form-control" id="numOfAddresses" ng-model="formData.numOfAddresses" readonly="readonly" has-Validatedtotal />
							<label><input type="checkbox" name="currentshipasis" ng-model="currentshipasis"> Ignore Validation</label>
						</div>
					</div>

				</div>

				<div class="row">
					<div class="col-xs-4">
						<div class="form-group">
						  	<label class="control-label" for="shipdate">Ship Date<span class="status"></span></label>
							<input type="text" class="form-control" id="shipdate" ng-model="formData.shipDate" val-Complete datepick/>
							<!-- data-date-format="yyyy-dd-mm"-->
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
						  	<label class="control-label" for="shipfrom">Ship From</label>
							<select class="form-control" id="shipfrom" ng-model="formData.shipFrom">
								<option></option>
								<option>Mt. Read</option>
								<option>Lee</option>
								<option>Rate Only</option>
							</select>
						</div>
					</div>
					<div class="col-xs-3">
						<div class="form-group">
						  	<label class="control-label" for="packagetype">Package Type</label>
							<select class="form-control" id="packagetype" ng-model="formData.packageType">
								<option></option>
								<option>Package</option>
								<option>Letter</option>
								<option>Tube</option>
							</select>
							<label><input type="checkbox" name="multiship" ng-model="formData.multipleshipquantities"> Ship Multiple Quantities</label>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xs-10">
						<div class="form-group">
						  	<label class="control-label" for="blindship">Blind Company</label>
							<input type="text" class="form-control" id="blindship" ng-model="formData.blindshipcompany">
						
						</div>
					</div>
				</div>
				<div class="row">
					
					<div class="col-xs-3">
						<div class="form-group">
						  	<label class="control-label" for="billto">Bill To</label>
							<select class="form-control" id="billto"  ng-model="formData.billto" bill-To>
							<option></option>
							<option>Third Party</option>
							<option>Reciever</option>
							<option>Sender</option>
							</select>
						</div>
					</div>
					<div class="col-xs-5">
						
						<div class="form-group thp" ng-show="formData.billto == 'Third Party'">
						  	<label class="control-label" for="thirdpartyaccount">Third Party Account</label>
						  	<select class="form-control" id="thirdpartyaccount" ng-model="formData.thirdpartyaccount" ng-options="third_billopt.label group by third_billopt.company_name for third_billopt in thirdpartybilling_opt">
								<option></option>
							</select>
						</div>
						<div class="form-group senderg" ng-show="formData.billto == 'Sender'">
						  	<label class="control-label" for="sender">Sender</label>
							<select class="form-control" id="sender"  ng-model="formData.sender">
								<option></option>
								<option>FCP Mt. Read</option>
								<option>FCP Lee Rd</option>
								<option>HBI</option>
							</select>
						</div>
						
					</div>
					<div class="col-xs-2">
						<div class="form-group thp" ng-show="formData.billto == 'Third Party'">
							<label class="control-label" for="add" >&nbsp;</label>
							<button class="btn btn-primary form-control" id="add" addthirdpartyaccountmodal>
								<span class="glyphicon glyphicon-plus"></span>
								Add
							</button>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-xs-10">
						<div class="form-group">
					  		<label class="control-label" for="promo">Promo</label>
					  		<input type="text" class="form-control" id="promo" ng-model="formData.promocode" />
						</div>
					</div>
				</div>	
				<div class="row">
					<div class="col-xs-10">
						<div class="form-group">
					  		<label class="control-label" for="description">Description of Goods</label>
					  		<textarea class="form-control" id="description" ng-model="formData.description"></textarea>
					  		<!--<input type="text" class="form-control" id="description" ng-model="formData.description" />-->
						</div>
					</div>
				</div>	
				<div class="row">
					<div class="col-xs-10">
						<div class="form-group">
					  		<label class="control-label" for="comments">Comments</label>
					  		<textarea class="form-control" id="comments" ng-model="formData.comments"></textarea>
					  		<!--<input type="text" class="form-control" id="description" ng-model="formData.description" />-->
						</div>
					</div>
				</div>	
				<div class="row">
					<div class="col-xs-6">
					<button class="btn btn-default" ng-click="onCancelBatchShipRequest()">Clear</button>
					<button type="submit" class="btn btn-primary" ng-click="onProcessBatchshipRequest()" ng-disabled="isFormSubmitButtonClicked==true">
						<span class="glyphicon glyphicon-cloud-upload"></span>
						Process
					</button>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>