<?php 
$html=				 	'<div class="container">
							<div class="row">
								<div class="col two_third" >
									<h1>'.__('Conversational forms','conversational-form').'</h1>
									<form class="ajaxForm" action="changeForm" >
										<label >'.__('Choose a form ','conversational-form').' :
											<select name="forms" id="formList">';
foreach ($formList as $key => $form) {
	if($key == 0){
		$html.=									'<option selected value="'.$form->id.'">'.$form->name.'</option>';
	}
	else{
		$html.=									'<option value="'.$form->id.'">'.$form->name.'</option>';
	}
			
}
			
$html.=										'</select>
										</label>
									</form>
	
								</div>
								<div class="col third">
									<form id="add_form" method="post" action="#">
										<input name="newFormName" type="text">
										<button id="add_btn" class="btn" >'.__('Add form ','conversational-form').'</button>
									</form>
									<a id="delete_btn" class="btn" href="#ajouter" data-form-id="'.$formList[0]->id.'">'.__('Delete form ','conversational-form').'</a>

								</div>
							</div>';

	 ?>