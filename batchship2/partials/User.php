<div class="container">
	<div id="myTab">
	<!-- Nav tabs -->
		<ul class="nav nav-tabs">
			<li class="active">
				<a show-tab class="manage_batches" href="#home" data-toggle="tab">Manage Batches</a>
			</li>
		  	<li>
		  		<a show-tab class="address_verification" href="#profile" data-toggle="tab">Upload Batch</a>
		  	</li>
		</ul>
		<!-- Tab panes -->
		<div class="tab-content">	
			<div class="tab-pane fade in active" id="home">
				<!-- Start Manage Batchship -->
				
<div class="container" ng-controller="ManageBatchController" ng-cloak>
	<form method="post" name="ManageBatches" id="ManageBatches" role="form" ng-submit="onClickSubmit()">
		<h3>Manage Batches</h3>
		<div class="alert alert-success" style="display:none;"></div>
		<div class="alert alert-danger" style="display:none;" ></div>
		<div class="checkbox"><label><input type="checkbox" ng-model="search.processed" ng-init="search.processed='f'" ng-true-value='f' ng-false-value=''>Do not show processed</label></div>
			<div class="form-group">
		<label for="searchbatches">Search Batches</label>
			<input type="text" name="searchbatches" class="form-control" ng-model="search.$" />
		</div>
		<table class="table">
			<thead>
				<tr>
					<th>Modify</th>
					<th>BatchID</th>
					<th>FCP Job#</th>
					<th>Company Name</th>
					<th>Timestamp</th>
					<th>Files</th>
					<th>Status</th>
				</tr>
			</thead>
			<tbody>
				<tr ng-repeat="batch_item in batchlist | filter:search:strict | orderBy:'id':'reverse'" data-id="{{$index}}">
					<td>
						<input type="checkbox" name="cb_checkbox[]" ng-click="onSelectMod(this)" ng-model="formData.batchid" ng-true-value="{{batch_item.id}}" ng-false-value="" />
					</td>
					<td>{{batch_item.id}}</td>
					<td ng-bind="batch_item.fcp_jobnumber"></td>
					<td>{{batch_item.company_name}}</td>
					<td ng-bind="batch_item.lastmodified|timestamp"></td>
			
					<td filejsonparse></td>
					<td is-Proc></td>
				</tr>
			</tbody>	
			<tfoot></tfoot>
		</table>
		<div class="ManageBatchControls">
			<input type="hidden" name="hf_btn_selected" value="" />
			<button name="btn_delete" ng-click="onClickDelete()" class="btn btn-danger" ng-disabled="(formData.batchid == '') || (bShowDelete == false)">
				 <span class="glyphicon glyphicon-floppy-remove"></span>
				Delete
			</button>
			<button name="btn_reupload_batch" ng-click="onClickReUploadBatch()" class="btn btn btn-danger" ng-disabled="(formData.batchid == '') || (bShowDelete == false)">
				 <span class="glyphicon glyphicon-cloud-upload"></span>
				Re-Upload Batch
			</button>
			<button name="btn_updateaddresses_batch" ng-click="onClickUpdateAddresses()" class="btn btn-primary" ng-disabled="formData.batchid == ''">
				 <span class="glyphicon glyphicon-cog"></span>
				Update Addresses
			</button>
			<button name="btn_process_batch" ng-click="onClickProcessBatch()" class="btn btn-primary" ng-disabled="formData.batchid == ''">
				 <span class="glyphicon glyphicon-ok-circle"></span>
				Process Batch
			</button>
			
			
		</div>
	</form>
	<!-- generic modal -->
	<form name="UploadAddressesForm" id="UploadAddressesForm" role="form">
	<div class="modal fade updateaddresses" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	  <div class="modal-dialog modal-sm">
	    <div class="modal-content">
	    	<!--<div class="modal-header"></div>-->
	    	<div class="modal-body">
	    		<!--<div class="alert alert-success" role="alert"></div>
				<div class="alert alert-info" role="alert"></div>
				<div class="alert alert-warning" role="alert"></div>
				<div class="alert alert-danger" role="alert"></div>-->
				
				<h3>Update Addresses</h3>
	    		<div class="form-group">
					<label for="txt">Batch ID#</label>
					<input type="text" class="form-control" name="txt" id="txt" ng-model="txt" disabled="true" required/>
				</div>
				<p><b>Excel file input</b></p>
				<span class="btn btn-default btn-file">
				    Browse <input type="file" name="file" file-model="file" file-Upload>
				</span>
				<p><span class="file_upload_status"></span></p>
	    	</div>
	    	<div class="modal-footer">
	    		<button name="btn_cancel" class="btn btn-default" data-dismiss="modal">
					Cancel
				</button>
	    		<button name="btn_update" class="btn btn-danger" data-dismiss="modal" on-Click-Upload>
				 	<span class="glyphicon glyphicon-cloud-upload"></span>
					Update
				</button>
				
	    	</div>
	    </div>
	  </div>
	</div>
	</form>
</div>

				
				<!-- End Manage Batchship -->			
				</div>
		 	<div class="tab-pane fade" id="profile">
		 		<!-- Start Upload Batchfile -->	 		
		 		
<div class="container" ng-controller="Address_Verification_Ctrl">
	<form method="post" name="Address_Verification" id="Address_Verification" enctype="multipart/form-data" role="form" ng-submit="onClickSubmit()">
		
		<div class="row">
			<h3>Upload Batch</h3>
			
			<div class="alert alert-success" style="display:none;"></div>
			<div class="alert alert-danger" style="display:none;"></div>
			  <div class="col-md-4">
			  	<div class="row">
			  		<img src="./content/microsoft-excel-icon.png" width="20px"/>
					<a href="./content/headertemplate.xlsx">Batchship Template (9/4/2014)</a><br/><br/>
				  	<div class="form-group">
						<label class="control-label" for="jobnumber">FCP Job #</label>
						<input type="text" class="form-control" name="jobnumber" id="jobnumber" ng-model="formData.job_number" ng-change="change()" placeholder="FCP Job Number" required>
					</div>	
				</div>
				<div class="row">
					<div class="form-group">
						<label class="control-label" for="customername">Customer</label>
						<input type="text" class="form-control" name="customername" id="customername" ng-model="formData.customer_name" placeholder="Customer Name" required readonly="readonly">
						<!--<input type="hidden" name="username" ng-value="formData.firstname">-->
						<input type="hidden" name="userid" ng-value="formData.userid">
						<input type="hidden" name="csremail" ng-value="formData.csr_email">
					</div>	
				</div>
				<div class="row">
					
				  	<!--<p><b>Excel file input</b></p>-->
				  	<div class="form-group">
					  	<label class="control-label" for="file">Excel file input</label><br/>
						<span class="btn btn-default btn-file">
							<!--Browse <input type="file" name="file" file-model="file" file-Upload>-->
						    Browse <input type="file" class="form-control" name="file" id="file" file-model="file" file-Upload>
						</span>
					</div>
				</div>
				
				<div class="row"><br/>
					<p class="file_upload_status"></p>
					<button class="btn btn-default" ng-click="onClickClearBtn()">Clear</button>
					<button type="submit" class="btn btn-primary">
					<span class="glyphicon glyphicon-cloud-upload"></span>
						Upload
					</button>
					</div>
			  </div>	  
		</div>
	</form>
</div>	
		 		
		 		<!-- End Upload Batchfile -->
		 	</div>
		</div>
	</div>
</div>