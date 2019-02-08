function send_api_request (params) {

	$.ajax({ 
	      type: 'POST',
	      url: '/app/api.php',
	      dataType: 'json',
	      data: params,
	      success: function(data) {
	    	
	    	  if(data.result.function != null & data.result.params == null) {
	    		  
	    		  window[data.result.function]();
	    		  
	    	  }
	    	  else if(data.result.function != null & data.result.params != null) {
	    		  
	    		  window[data.result.function](data.result.params);
	    		  
	    	  }
	    	  
	    	  if(data.result.update_function != null & data.result.update_params == null) {
	    		  
	    		  window[data.result.update_function]();
	    		  
	    	  }
	    	  else if(data.result.update_function != null & data.result.update_params != null) {
	    		  
	    		  window[data.result.update_function](data.result.update_params);
	    		  
	    	  }
	    	  
	      }
	
	});
	
}

function render_user_edit (params) {
	$.each(params, function(k, v){
		$('.edit_user [name="' + k + '"]').val(v);
	});
}

function get_user_info () {
	var params = new Array();

	params.push({name: 'data[method]', value: 'user'});
	params.push({name: 'data[action]', value: 'GetUserInfo'});
	params.push({name: 'data[id]', value: $('[name="id"]').val()});
	
	send_api_request(params);
}

function render_user_list (params) {
	var parent = $('.user_list tbody');

	parent.html('');
	
	$.each(params, function (k, v){
		if (v.cards != null)
			var cards = Object.values(v.cards).join(', ');
		else
			var cards = '';
		parent.append(
			'<tr>' +
				'<td>' + v.name + '</td>' +
				'<td>' + v.last_name + '</td>' +
				'<td>' + v.email + '</td>' +
				'<td>' + v.phone + '</td>' +
				'<td>' + cards + '</td>' +
			'</tr>'
		);
	});
}

function get_user_list() {
	var params = new Array();

	params.push({name: 'data[method]', value: 'user'});
	params.push({name: 'data[action]', value: 'GetUserList'});
	params.push({name: 'data[name]', value: $('[name="search_user_name"]').val()});
	params.push({name: 'data[last_name]', value: $('[name="search_user_last_name"]').val()});
	params.push({name: 'data[card_number]', value: $('[name="search_card_number"]').val()});
	
	send_api_request(params);
}

function render_top_list(params) {
	var parent = $('.top_user_list tbody');

	$('#users_count').html(params.users_count);
	$('#cards_count').html(params.cards_count);

	parent.html('');

	$.each(params.users, function (k, v){
		parent.append(
			'<tr>' +
				'<td>' + (k+1) + '</td>' +
				'<td>' + v.name + '</td>' +
				'<td>' + v.last_name + '</td>' +
				'<td>' + v.email + '</td>' +
				'<td>' + v.phone + '</td>' +
				'<td>' + v.summ + '</td>' +
			'</tr>'
		);
	});
}

function create_new_user() {
	$('[data-field]').removeClass('red');

	var params = new Array();

	params.push({name: 'data[method]', value: 'user'});
	params.push({name: 'data[action]', value: 'NewUser'});
	params.push({name: 'data[info][name]', value: $('.new_user [name="name"]').val()});
	params.push({name: 'data[info][last_name]', value: $('.new_user [name="last_name"]').val()});
	params.push({name: 'data[info][address]', value: $('.new_user [name="address"]').val()});
	params.push({name: 'data[info][email]', value: $('.new_user [name="email"]').val()});
	params.push({name: 'data[info][phone]', value: $('.new_user [name="phone"]').val()});
	params.push({name: 'data[info][card_id]', value: $('.new_user [name="card"]').val()});
	
	send_api_request(params);
}

function get_reports() {
	var params = new Array();

	params.push({name: 'data[method]', value: 'user'});
	params.push({name: 'data[action]', value: 'GetReports'});
	params.push({name: 'data[count]', value: 10});
	
	send_api_request(params);
}

function fill_db() {
	var params = new Array();

	params.push({name: 'data[method]', value: 'user'});
	params.push({name: 'data[action]', value: 'FillDB'});
	
	send_api_request(params);
}

function show_form_error(params) {
	$('[data-field]').removeClass('red');

	$.each(params, function(k, v){
		$('[data-field="' + v + '"]').addClass('red');
	});
}

function fill_free_cards(params) {
	var parent = $('select[name="card"]');
	$('.new_user input').val('');
	parent.html('');

	$.each(params, function(k, v){
		type = 'B';
		if (v.type == 2) type = 'T';
		parent.append('<option value="' + v.id + '">' + type + ' ' + v.number + '</option>');
	});
}

function get_free_cards() {
	var params = new Array();

	params.push({name: 'data[method]', value: 'user'});
	params.push({name: 'data[action]', value: 'GetFreeCards'});
	
	send_api_request(params);
}

$(document).ready(function(){
	if ($('.user_list').length > 0) get_user_list();
	if ($('.top_user_list').length > 0) get_reports();
	if ($('.edit_user').length > 0) get_user_info();
	if ($('select[name="card"]').length > 0) get_free_cards();

	$(document).on('click', '.create_user_button', create_new_user);
	$(document).on('click', '.fill_base', fill_db);

	$(document).on('click', '.search_user', get_user_list);
});