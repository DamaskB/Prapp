<div class="container">
	<div class="col-md-3">
		<div class="row">
			<form class="edit_user">
				<input name="id" value="<?=$url[1]?>" type="hidden">
				<div class="form-group">
					<label>Name *</label>
					<input class="form-control" name="name" data-field="name">
				</div>
				<div class="form-group">
					<label>Last name *</label>
					<input class="form-control" name="last_name" data-field="last_name">
				</div>
				<div class="form-group">
					<label>Address</label>
					<input class="form-control" name="address" data-field="address">
				</div>
				<div class="form-group">
					<label>E-mail *</label>
					<input class="form-control" type="email" name="email" data-field="email">
				</div>
				<div class="form-group">
					<label>Phone</label>
					<input class="form-control" name="phone" data-field="phone">
				</div>
				<button type="button" class="btn btn-success edit_user_button">Save user</button>
			</form>
		</div>
	</div>
</div>