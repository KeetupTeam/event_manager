<?php

/**
 * Event manager registration wrapper
 */

$event = elgg_extract('entity', $vars, false);
if (!($event instanceof Event)) {
	return false;
}
$guid = $event->getGUID();

$output  ='<ul id="event_manager_registrationform_fields">';

if($registration_form = $event->getRegistrationFormQuestions()) {
	foreach($registration_form as $question) {
		$output .= elgg_view('event_manager/registration/question', array('entity' => $question));
	}
}

$output .= '</ul>';	
$output .= '<br /><a rel="'.$guid.'" id="event_manager_questions_add" href="javascript:void(0);" class="elgg-button elgg-button-action">' . elgg_echo('event_manager:editregistration:addfield') . '</a>';

echo $output;