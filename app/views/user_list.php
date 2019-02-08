<div class="container">
	<div class="col-md-3">
		<form class="new_user">
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
			<div class="form-group">
				<label>Card</label>
				<select class="form-control" name="card">
				</select>
			</div>
			<button type="button" class="btn btn-success create_user_button">Create new user</button>
		</form>
	</div>
	<div class="col-md-9">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label>Name</label>
					<input type="text" name="search_user_name">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Last name</label>
					<input type="text" name="search_user_last_name">
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label>Card</label>
					<input type="text" name="search_card_number">
				</div>
			</div>
			<div class="col-md-3">
				<button type="button" class="btn btn-primary search_user">Search</button>
			</div>
		</div>
		<table class="user_list table table-bordered table-striped">
			<thead>
				<tr>
					<th>Name</th>
					<th>Last name</th>
					<th>E-mail</th>
					<th>Phone</th>
					<th>Cards</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
		<button class="btn btn-lg btn-warning fill_base">Fill database with random data</button>
	</div>
</div>