<?php

/**
 * Form speaker edit
 */

$event_guid = elgg_extract('event_guid', $vars, false);
$speaker_guid = elgg_extract('speaker_guid', $vars, false);

if ($event_guid && ($entity = get_entity($event_guid))) {
	// assume new speaker mode
	if (!($entity instanceof Event)) {
		unset($entity);
	}
} 
elseif ($speaker_guid && ($entity = get_entity($speaker_guid))) {
	// assume speaker edit mode
	if (!($entity instanceof EventSpeaker)) {
		unset($entity);
	}
}

$error = FALSE;
if ($entity instanceof Event) {
	$count_speakers = $entity->getEventSpeakers(array('count' => TRUE));
	if ($count_speakers >= EVENT_MANAGER_EVENTSPEAKER_MAX) {
		$error = TRUE;
	}
}

if ($entity && $entity->canEdit() && !$error) {
	if ($entity instanceof EventSpeaker) {
		// Assume speader edit mode
		$guid = $entity->getGUID();
		$container_guid = $entity->container_guid;
		
		$speaker_name = $entity->speaker_name;
		$speaker_url = $entity->speaker_url;
		$speaker_bio = $entity->speaker_bio;
	}
	else {
		// Entity is a event
		$guid = 0;
		$container_guid = $entity->getGUID();
	}
	
	// Image
	$form_body = "<div>";
	$form_body .= "<label>".elgg_echo('event_manager:speakers:form:image')." *</label>";
	$form_body .= elgg_view('input/file', array('name' => 'speaker_image'));
	$form_body .= '<p><b>'.elgg_echo('event_manager:speakers:form:image:note').'</b>'.elgg_echo('event_manager:speakers:form:image:note:unchanged').'</p>';
	$form_body .= "</div>";
	
	// Name
	$form_body .= "<div>";
	$form_body .= "<label>".elgg_echo('event_manager:speakers:form:name')." *</label>";
	$form_body .= elgg_view('input/text', array('name' => 'speaker_name', 'value' => $speaker_name, 'size' => 80));
	$form_body .= "</div>";
	
	// URL
	$form_body .= "<div>";
	$form_body .= "<label>".elgg_echo('event_manager:speakers:form:url')."</label>";
	$form_body .= elgg_view('input/text', array('name' => 'speaker_url', 'value' => $speaker_url, 'size' => 80));
	$form_body .= "</div>";
	
	// Description
	$form_body .= "<div>";
	$form_body .= "<label>".elgg_echo('event_manager:speakers:form:bio')."</label>";
	$form_body .= elgg_view('input/longtext', array('name' => 'speaker_bio', 'value' => $speaker_bio, 'rows' => 5, 'cols' => 77));
	$form_body .= '<p><b>'.elgg_echo('event_manager:speakers:form:bio:note').'</b>'.elgg_echo('event_manager:speakers:form:bio:characters', array(EVENT_MANAGER_EVENTSPEAKER_BIO_MAX)).'</p>';
	$form_body .= "</div>";
	
	// Foot
	$form_body .= "<div class=\"elgg-foot\">";
	$form_body .= elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
	$form_body .= elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
	$form_body .= elgg_view('input/submit', array('value' => elgg_echo('event_manager:speakers:form:save')));
	$form_body .= "</div>";
	
	$body = elgg_view('input/form', array(
		'id' 	=> 'event_manager_speaker_edit',
		'name' 	=> 'event_manager_speaker_edit', 
		'action' => $vars['url'] . 'action/event_manager/speakers/edit',
		'body' => $form_body,
	));
	
	echo elgg_view_module('info', elgg_echo("event_manager:spakers:form:title"), $body, array("class" => "elgg-module-event-manager-speakers"));
}
else {
	// TODO: nice error message
	echo elgg_echo("error");
}