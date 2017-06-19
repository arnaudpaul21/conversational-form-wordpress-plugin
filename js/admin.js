

var $ = jQuery;
window.onload = function(){

function escapeHtml(text) {
  var map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };

  return text.replace(/[&<>"']/g, function(m) { return map[m]; });
}

	var cf = {
		config:{
			orderTimeout:0
		},
		init:function(){
			cf.bindFormEvents();
			cf.bindInputEvents();
			cf.bindDragDropEvents();
		},
		bindFormEvents:function(){

			$(document).on('change','#formList',function(){
				var $this = $(this)

				var data = {
					'action': 'change_form',
					'id': parseInt($this.val())
				}
				$.post('/wp-admin/admin-ajax.php', data, function(res) {
					res = res.slice(0,-1);
					var obj = JSON.parse(res);
					
					if(obj.message == "Done" && !obj.error_message){
						$('#inputsList .input').fadeOut(400,function(){
							$(this).remove();
						});
						var html='';
						console.log(obj);
						if(obj.inputs.length >0){
							for (var i = 0; i < obj.inputs.length; i++) {
								var input = obj.inputs[i];

								html+= '<li class="input" data-id="'+input.id+'" data-form-id="'+obj.form_id+'"><form action="post">';
								html+= '<div class="row"><div class="type col third">'+cf_datas.input_labels.input_type+' : '+input.type+'</div>';
								if(input.type == 'radio' || input.type == 'checkbox' || input.type == 'select' || input.type == 'submit'){
									html+= '<div class="label col two_third" style="padding-bottom:20px"><label>'+cf_datas.input_labels.input_value+': <textarea rows="2" cols="46" style="margin-bottom:-17px" name="label_input" placeholder="'+cf_datas.input_labels.input_select_placeholder+'" >'+input.label+'</textarea></div>';
								}
								
								html+= '<div class="delete_input" >x</div></div>';
								html+= '<div class="row"><div class="questions col" style="padding-bottom:20px"><label>'+cf_datas.input_labels.input_questions_label+': <textarea rows="2" cols="89" style="margin-bottom:-17px" name="questions_input" placeholder="'+cf_datas.input_labels.input_questions_placeholder+'" >'+input.questions+'</textarea></label></div></div>';
								
								html+= '<div class="row"><div class="name col third"><label>'+cf_datas.input_labels.input_name+': <input type="text" name="name_input" placeholder="'+cf_datas.input_labels.input_eg+' first_name" value="'+input.name+'"></label></div>	';

								
								if(input.type == 'text'){
								html+=	'<div class="pattern col two_third"><label>'+cf_datas.input_labels.input_validation_pattern+': <input type="text" name="pattern_input" placeholder="'+cf_datas.input_labels.input_eg+' ^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$" value="'+input.pattern+'"></label></div>';
									
								}
								html+= '</div><div class="row"><div class="errors col"><label>'+cf_datas.input_labels.input_error_label+': <input type="text" name="errors_input" placeholder="'+cf_datas.input_labels.input_error_placeholder+'" value="'+input.errors+'"></label></div></div>';

								
								html+= '<div class="row"><div class="col third"></div><div class="col third"></div><div class="submit col third"><input type="submit" value="'+cf_datas.input_labels.input_register+'"></div></div></form></li>';
							}
						}
						else{								
							html+='<h3>Il n\'y a encore aucune question pour ce formulaire.</h3>';
						}
						$('#inputsList h3').remove();
						$('#inputsList').append(html);
						$('#form_shortcode pre').text('[conversational-form id="'+obj.form_slug+'"]');
						$('#delete_btn').attr('data-form-id',obj.form_id);
						$('#advanced_params_form').attr('data-form-id',obj.form_id);
						
						$('#redirectLink').val(obj.params.redirect_link);
						$('#dataTreatmentLink').val(obj.params.send_data_to);
						$('#confirmMessage').val(obj.params.confirmation_message);
						$('#emailDestination').val(obj.params.mailto);
						$('#imageRobot').val(obj.params.robot_image);
						$('#imageUser').val(obj.params.user_image);
						
					}
					else{
						console.log(obj.error_message);
					}

				});
			});

			$(document).on('submit',"#add_form",function(e){
				e.preventDefault;
				var $this = $(this)
				var data = {
					'action': 'add_form',
					'newFormName': $this[0][0].value
				}
				$.post('/wp-admin/admin-ajax.php', data, function(res) {
					res = res.slice(0,-1);
					var obj = JSON.parse(res);
					console.log(obj);
					if(obj.message == "Done" && !obj.error_message){
						$('#add_form input[name="newFormName"]').val('');	

						$('#formList').append('<option value="'+obj.form_id+'">'+obj.form_name+'</option>');
						$('#formList').val(obj.form_id).trigger('change');
					}
					else{
						$('#add_form input[name="newFormName"]').val(obj.error_message);
					}

				});
				return false;
			});

			$(document).on('click',"#delete_btn",function(e){
				e.preventDefault();
				var $this = $(this)
				var data = {
					'action': 'delete_form',
					'formId': $this.attr('data-form-id')
				};
				$.post('/wp-admin/admin-ajax.php', data, function(res) {
					res = res.slice(0,-1);
					var obj = JSON.parse(res);
					console.log(obj);
					if(obj.message == "Done" && !obj.error_message){
						$('#formList option[value="'+obj.form_id+'"]').remove();
						$('#formList').val($("#formList option:first").val()).trigger('change');
					}
					else{
						console.log(obj.error_message);
					}
				});
				return false;
			});


			$(document).on('submit',"#advanced_params_form",function(e){
				e.preventDefault;
				var $this = $(this)
				var data = {
					'action': 'update_params',
					'formId': $this.attr('data-form-id'),
					'redirect_link':$this.find('input[name="redirect_link"]').val(),
					'send_data_to':$this.find('input[name="send_data_to"]').val(),
					'confirmation_message':$this.find('input[name="confirmation_message"]').val(),
					'mailto':$this.find('input[name="mailto"]').val(),
					'user_image':$this.find('input[name="user_image"]').val(),
					'robot_image':$this.find('input[name="robot_image"]').val(),


				}
				$.post('/wp-admin/admin-ajax.php', data, function(res) {
					res = res.slice(0,-1);
					var obj = JSON.parse(res);
					console.log(obj);

					if(obj.message == "Done" && !obj.error_message){
						$this.find('input[type="submit"]').addClass('done');
						setTimeout(function(){
							$this.find('input[type="submit"]').removeClass('done');
						},2000)
					}
					else{
						$this.find('input[type="submit"]').addClass('error');
						setTimeout(function(){
							$this.find('input[type="submit"]').removeClass('error');
						},2000)
					}

				});
				return false;
			});




		},
		bindInputEvents:function(){
			$(document).on('submit','#inputsList .input>form',function(e){
				e.preventDefault();
				var $this = $(this)
				
				var data={
					'action':'update_input',
					'input_id':$this.closest('.input').attr('data-id'),
					'questions':$this.find('.questions textarea[name="questions_input"]').val(),
					'name': $this.find('.name input[name="name_input"]').val(),
					'pattern':$this.find('.pattern input[name="pattern_input"]').val(),
					'errors': $this.find('.errors input[name="errors_input"]').val(),
					'label': $this.find('.label textarea[name="label_input"]').val()
				};	

				$.post('/wp-admin/admin-ajax.php', data, function(res) {
					res = res.slice(0,-1);
					var obj = JSON.parse(res);
					console.log(obj);
					if(obj.message == "Done" && !obj.error_message){
						$this.find('input[type="submit"]').addClass('done');
						setTimeout(function(){
							$this.find('input[type="submit"]').removeClass('done');
						},2000);
						
					}
					else{
						$this.find('input[type="submit"]').addClass('error');
						setTimeout(function(){
							$this.find('input[type="submit"]').removeClass('error');
						},2000);
						console.log(obj.error_message);
					}
				});

				console.log(data);

				return false;
			});


			$(document).on('click','#inputsList .delete_input',function(e){
				e.preventDefault();
				var $this = $(this)
				
				var data={
					'action':'delete_input',
					'input_id':$this.closest('.input').attr('data-id')
				};	

				$.post('/wp-admin/admin-ajax.php', data, function(res) {
					res = res.slice(0,-1);
					var obj = JSON.parse(res);
					console.log(obj);
					if(obj.message == "Done" && !obj.error_message){
						$this.closest('.input').fadeOut(400,function(){
							$(this).remove();
						})
						
					}
					else{
						console.log(obj.error_message);
					}
				});

				console.log(data);

				return false;
			});
			$(document).on('click','#add_inputs .add_input',function(e){
				e.preventDefault();
				var $this = $(this)
				
				var inputType = $this.attr('data-type')
				var formId = $('#delete_btn').attr('data-form-id');
				var order = $('#inputsList').children().size();
				order = parseInt(order)+1
				var data={
					'action':'add_input',
					'input_type': inputType,
					'form_id':formId,
					'order':order
				};	

				$.post('/wp-admin/admin-ajax.php', data, function(res) {
					res = res.slice(0,-1);
					var obj = JSON.parse(res);
					console.log(obj);
					if(obj.message == "Done" && !obj.error_message){
						var html='';

						html+= '<li class="input" data-id="'+obj.input_id+'" data-form-id="'+formId+'"><form action="post">';
						html+= '<div class="row"><div class="type col third">'+cf_datas.input_labels.input_type+' : '+inputType+'</div>';
						if(inputType == 'radio' || inputType == 'checkbox' || inputType == 'select' ){
							html+= '<div class="label col two_third" style="padding-bottom:20px"><label>'+cf_datas.input_labels.input_value+': <textarea rows="2" cols="46" style="margin-bottom:-17px" name="label_input" placeholder="'+cf_datas.input_labels.input_select_placeholder+'" ></textarea></div>';
						}
								
						html+= '<div class="delete_input" >x</div></div>';
						// if(inputType != 'radio' && inputType != 'checkbox' && inputType != 'select' ){
							html+= '<div class="row"><div class="questions col" style="padding-bottom:20px"><label>'+cf_datas.input_labels.input_questions_label+': <textarea rows="2" cols="89" style="margin-bottom:-17px" name="questions_input" placeholder="'+cf_datas.input_labels.input_questions_placeholder+'" ></textarea></label></div></div>';
						// }
							
							html+= '<div class="row"><div class="name col third"><label>'+cf_datas.input_labels.input_name+': <input type="text" name="name_input" placeholder="'+cf_datas.input_labels.input_eg+' first_name" value=""></label></div>';
							if(inputType == 'text'){
								html+='<div class="pattern col two_third"><label>'+cf_datas.input_labels.input_validation_pattern+': <input type="text" name="pattern_input" placeholder="'+cf_datas.input_labels.input_eg+' ^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$" value=""></label></div></div>';
							}
							html+= '<div class="row"><div class="errors col"><label>'+cf_datas.input_labels.input_error_label+': <input type="text" name="errors_input" placeholder="'+cf_datas.input_labels.input_error_placeholder+'" value=""></label></div></div>';
						
						html+= '<div class="row"><div class="col third"></div><div class="col third"></div><div class="submit col third"><input type="submit" value="'+cf_datas.input_labels.input_register+'"></div></div></form></li>';
						$('#inputsList').append(html);
						$('html,body').animate({
							scrollTop: $(document).height()-$(window).height()
						},500);
					}
					else{
						$this.addClass('error');
						setTimeout(function(){
							$this.removeClass('error');
						},2000);
						console.log(obj.error_message);
					}
				});

				console.log(data);

				return false;
			});


		},
		bindDragDropEvents:function(){
			$('#inputsList').sortable({
				start:function(e,ui){
					clearTimeout(cf.config.orderTimeout);
				},
				stop:function(e,ui){
					

					var inputs = $('#inputsList')[0].children;
					
					var formatedInputs = [];
					for (var i = 0; i < inputs.length; i++) {
					
						formatedInputs[i] = {
							'inputId':parseInt(inputs[i].dataset.id),
							'order':i
						}
						
					}
					console.log(formatedInputs);
					
					var data={
						'action':'update_input_order',
						'inputs': formatedInputs
					};
					$this = $(this);
					cf.config.orderInterval = setTimeout(function(){
						$.post('/wp-admin/admin-ajax.php', data, function(res) {
							res = res.slice(0,-1);
							var obj = JSON.parse(res);
							console.log(obj);
							if(obj.message == "Done" && !obj.error_message){
								$this.addClass('done');
								setTimeout(function(){
									$this.removeClass('done');
								},2000);
							}
							else{
								$this.addClass('error');
								setTimeout(function(){
									$this.removeClass('error');
								},2000);
								console.log(obj.error_message);
							}
						});
					},2000);	


				}
			});



		}
	};
	cf.init();


}