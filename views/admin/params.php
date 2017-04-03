<?php 

$html.='				<div class="col third" >
							<div id="form_shortcode">'.__('Form shortcode ','conversational-form').' :<pre style="border: 1px inset grey;background:#fff;padding:10px;">[conversational-form id="'.$formList[0]->slug.'"]</pre>
							</div>
							<div id="add_inputs" class="container">
								<div class="row">
									<div class="col third">
										<div class="add_input" data-type="text">
											<img src="'.CF_DIR_URL.'/img/text.svg" alt="'.__('Text','conversational-form').'">
											<h4>'.__('Text','conversational-form').'</h4>
										</div>
									</div>
									<div class="col third">
										<div class="add_input" data-type="tel">
											<img src="'.CF_DIR_URL.'/img/tel.svg" alt="'.__('Phone','conversational-form').'">
											<h4>'.__('Phone','conversational-form').'</h4>
										</div>
									</div>
									<div class="col third">
										<div class="add_input" data-type="email">
											<img src="'.CF_DIR_URL.'/img/email.svg" alt="'.__('Email','conversational-form').'">
											<h4>'.__('Email','conversational-form').'</h4>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col third">
										<div class="add_input" data-type="textarea">
											<img src="'.CF_DIR_URL.'/img/textarea.svg" alt="'.__('Text area','conversational-form').'">
											<h4>'.__('Text area','conversational-form').'</h4>
										</div>
									</div>
									<div class="col third">
										<div class="add_input" data-type="select">
											<img src="'.CF_DIR_URL.'/img/select.svg" alt="'.__('Select','conversational-form').'">
											<h4>'.__('Select','conversational-form').'</h4>
										</div>
									</div>
								</div>
								
							</div>
							<div id="advanced_params" >
								<h3 >'.__('Advanced parameters','conversational-form').'</h3>
				
								<form id="advanced_params_form" action="/" method="post" data-form-id="'.$formList[0]->id.'">
									<div class="row">
										<div class="col third">
											<label for="redirectLink" >'.__('Redirect link','conversational-form').'</label>
										</div>
										<div class="col two_third">
											<input type="text" name="redirect_link" value="'.$formList[0]->redirect_link.'" id="redirectLink">
										</div>
									</div>
									<div class="row">
										<div class="col third">
											<label for="dataTreatmentLink" >'.__('Data treatment link','conversational-form').'</label>
										</div>
										<div class="col two_third">
											<input type="text" name="send_data_to" value="'.$formList[0]->send_data_to.'" id="dataTreatmentLink">
										</div>
									</div>
									<div class="row">
										<div class="col third">
											<label for="confirmMessage" >'.__('Confirmation message','conversational-form').'</label>
										</div>
										<div class="col two_third">
											<input type="text" name="confirmation_message" value="'.$formList[0]->confirmation_message.'" id="confirmMessage">
										</div>
									</div>
									<div class="row">
										<div class="col third">
											<label for="emailDestination" >'.__('Email to ?','conversational-form').'</label>
										</div>
										<div class="col two_third">
											<input type="text" name="mailto" value="'.$formList[0]->mailto.'" id="emailDestination">
										</div>
									</div>
									<div class="row">
										<div class="col third">
											<label for="imageRobot" >'.__('Robot image','conversational-form').'</label>
										</div>
										<div class="col two_third">
											<input type="text" name="robot_image" value="'.$formList[0]->robot_image.'" id="imageRobot">
										</div>
									</div>
									<div class="row">
										<div class="col third">
											<label for="imageUser" >'.__('User image','conversational-form').'</label>
										</div>
										<div class="col two_third">
											<input type="text" name="user_image" value="'.$formList[0]->user_image.'" id="imageUser">
										</div>
									</div>
									<div class="row">
										<div class="col third">
											
										</div>
										<div class="col two_third">
											<input type="submit" value="'.__('Register','conversational-form').'">
										</div>
									</div>
								</form>	
							</div>
						</div>
					</div>
				</div>';
 ?>


<!--  <div class="col third">
										<div class="add_input" data-type="radio">
											<img src="'.CF_PLUGIN_URL.'img/radio.svg" alt="Radio">
											<h4>Radio</h4>
										</div>
									</div>
									<div class="col third">
										<div class="add_input" data-type="checkbox">
											<img src="'.CF_PLUGIN_URL.'img/checkbox.svg" alt="">
											<h4>Checkbox</h4>
										</div>
									</div> -->