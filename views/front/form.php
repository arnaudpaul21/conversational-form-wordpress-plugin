<?php 
$html.=						'<div id="'.$slug.'Container" class="conversational-form-container" style="height:'.$blocHeight.';">
								<form method="post" id="'.$slug.'" action="#" class="conversational-form">';
foreach ($inputsList as $key => $input) {
	switch ($input->type) {
		case 'text':	
			$html.=	'				<input required type="text" cf-error="'.$input->errors.'" cf-questions="'.$input->questions.'" pattern="'.$input->pattern.'"  name="'.$input->name.'">';
			break;
		case 'tel':
			$html.=	'				<input required type="tel" cf-error="'.$input->errors.'" cf-questions="'.$input->questions.'" cf-validation="window.cf_validation_tel"  name="'.$input->name.'">';
			break;
		case 'email':
			$html.=	'				<input required type="email" cf-error="'.$input->errors.'" cf-questions="'.$input->questions.'" cf-validation="window.cf_validation_email" name="'.$input->name.'">';
			break;
		case 'radio':
			$values = explode('|', $input->label);
			foreach ($values as $key=> $value) {
				if($key == 0){
					$html.=			'<input required type="radio" cf-error="'.$input->errors.'" cf-questions="'.$input->questions.'" name="'.$input->name.'" cf-label="'.$value.'" value="'.$value.'">';
				}
				else{
					$html.=			'<input required type="radio" cf-error="'.$input->errors.'" name="'.$input->name.'" cf-label="'.$value.'" value="'.$value.'">';
				}
			}
			break;
		case 'checkbox':
			$values = explode('|', $input->label);
			foreach ($values as $key=> $value) {
				if($key == 0){
					$html.=			'<input required type="checkbox" cf-error="'.$input->errors.'" cf-questions="'.$input->questions.'" name="'.$input->name.'" cf-label="'.$value.'" value="'.$value.'">';
				}
				else{
					$html.=			'<input required type="checkbox" cf-error="'.$input->errors.'" name="'.$input->name.'" cf-label="'.$value.'" value="'.$value.'">';
				}
							
			}
			break;
		case 'select':
			$values = explode('|', $input->label);
			$html.=					'<select required cf-questions="'.$input->questions.'" cf-error="'.$input->errors.'" name="'.$input->name.'">';
			foreach ($values as $key=> $value) {
				$html.=					'<option cf-label="'.$value.'" value="'.$value.'">'.$value.'</option>';
							
			}
			$html.=					'</select>';
			break;
		case 'textarea':
						
			$html.=	'				<input type="text" cf-questions="'.$input->questions.'" cf-error="'.$input->errors.'"  name="'.$input->name.'">';
			break;
		default:
			
			break;
	}
				
}
$html.=							'</form>
							</div>';

 ?>