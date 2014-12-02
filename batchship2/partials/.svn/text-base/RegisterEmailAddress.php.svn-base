<div class="container " ng-controller="RegisterEmailAddressController">
	<form method="get" role="form" ng-submit="onClickSubmit()">
		<h3>Register Email Address</h3>
		<div class="alert alert-success" ng-bind="status" style="display:none;"></div>
		<div class="alert alert-danger" ng-bind="status" style="display:none;" ></div>
		<div class="alert alert-info" ng-bind="status" style="display:none;" ></div>
		<div class="alert alert-warning" ng-bind="status" style="display:none;"></div>
		<p>We would like to collect your information for email notification purposes.</p>
		<div class="form-group">
			<label for="username">Username</label>
			<input type="text" class="form-control" name="username" id="username" ng-model="formData.username" placeholder="Username" required>
		</div>
		<div class="form-group">
			<label for="emailadd">Email Address</label>
			<input type="email" class="form-control" id="emailadd"  name="emailadd" ng-model="formData.emailadd" placeholder="Email Address" required>
			<input type="hidden" id="userid"  name="userid" ng-model="formData.emailadd">
		</div>
		<div class="row">
			<button type="submit" ng-model="form.action" ng-click="form.action='update'" class="btn btn-primary">
				<span class="glyphicon glyphicon-cloud-upload"></span>Submit
			</button>
		</div>
	</form>
</div>
