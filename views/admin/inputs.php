<?php 
$html.=					'<div class="row">
							<div class="col two_third" >
								<ul id="inputsList" class="container">';

foreach ($inputsList as $key => $input) {
	$html.= 						'<li class="input" data-id="'.$input->id.'" data-form-id="'.$currentFormId.'">
										<form action="post">
											<div class="row">
												<div class="type col third">'.__('Input type ','conversational-form').' : '.$input->type.'</div>';
	if($input->type == 'radio' || $input->type == 'checkbox' || $input->type == 'select' ){
		$html.=									'<div class="label col two_third" style="padding-bottom:20px">
													<label>'.__('Input value ','conversational-form').': <textarea rows="2" cols="46" style="margin-bottom:-17px" name="label_input" placeholder="'.__('eg : Project manager | Web designer ','conversational-form').'" >'.$input->label.'</textarea></label>
												</div>';
	}
	$html.=										'<div class="delete_input" >x</div>
											</div>
											<div class="row">
												<div class="questions col" style="padding-bottom:20px">
													<label>'.__('Questions ','conversational-form').': <textarea rows="2" cols="89" style="margin-bottom:-17px" name="questions_input" placeholder="'.__('Your question here (place | between each question if multiples) ','conversational-form').'" >'.$input->questions.'</textarea></label>
												</div>
											</div>
											<div class="row">
												<div class="name col third">
													<label>'.__('Input name ','conversational-form').' : <input type="text" name="name_input" placeholder="'.__('eg:','conversational-form').' first_name " value="'.$input->name.'"></label>
												</div>	';
	if($input->type == 'text'){
		$html.=									'<div class="pattern col two_third">
													<label>'.__('Validation pattern ','conversational-form').': <input type="text" name="pattern_input" placeholder="'.__('eg:','conversational-form').' ^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$" value="'.$input->pattern.'"></label>
												</div>';
	}
	$html.=									'</div>
											<div class="row">
												<div class="errors col">
													<label>'.__('Errors ','conversational-form').' : <input type="text" name="errors_input" placeholder="'.__('Your error message here ( place | between each message if multiple','conversational-form').'" value="'.$input->errors.'"></label>
												</div>
											</div>

											<div class="row">
												<div class="col third"></div>
												<div class="col third"></div>
												<div class="submit col third">
													<input type="submit" value="'.__('Register','conversational-form').'">
												</div>
											</div>
												
										</form>
									</li>';
}
$html.=							'</ul>
							</div>';

	 ?>