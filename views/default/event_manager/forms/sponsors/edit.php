<?php

/**
 * Form sponsor edit
 */

$event_guid = elgg_extract('event_guid', $vars, false);
$sponsor_guid = elgg_extract('sponsor_guid', $vars, false);

if ($event_guid && ($entity = get_entity($event_guid))) {
	// assume new sponsor mode
	if (!($entity instanceof Event)) {
		unset($entity);
	}
} 
elseif ($sponsor_guid && ($entity = get_entity($sponsor_guid))) {
	// assume sponsor edit mode
	if (!($entity instanceof EventSponsor)) {
		unset($entity);
	}
}

$error = FALSE;
if ($entity instanceof Event) {
	$count_sponsors = $entity->getEventSponsors(array('count' => TRUE));
	if ($count_sponsors >= EVENT_MANAGER_EVENTSPONSOR_MAX) {
		$error = TRUE;
	}
}

if ($entity && $entity->canEdit() && !$error) {
	if ($entity instanceof EventSponsor) {
		// Assume sponsor edit mode
		$guid = $entity->getGUID();
		$container_guid = $entity->container_guid;
		
		$sponsor_name = $entity->sponsor_name;
		$sponsor_url = $entity->sponsor_url;
	}
	else {
		// Entity is a event
		$guid = 0;
		$container_guid = $entity->getGUID();
	}
	
	// Image
	$form_body = "<div>";
	$form_body .= "<label>".elgg_echo('event_manager:sponsors:form:image')." *</label>";
	$form_body .= elgg_view('input/file', array('name' => 'sponsor_image'));
	$form_body .= '<p><b>'.elgg_echo('event_manager:sponsors:form:image:note').'</b>'.elgg_echo('event_manager:sponsors:form:image:note:unchanged').'</p>';
	$form_body .= "</div>";
	
	// Name
	$form_body .= "<div>";
	$form_body .= "<label>".elgg_echo('event_manager:sponsors:form:name')." *</label>";
	$form_body .= elgg_view('input/text', array('name' => 'sponsor_name', 'value' => $sponsor_name, 'size' => 80));
	$form_body .= "</div>";
	
	// URL
	$form_body .= "<div>";
	$form_body .= "<label>".elgg_echo('event_manager:sponsors:form:url')."</label>";
	$form_body .= elgg_view('input/text', array('name' => 'sponsor_url', 'value' => $sponsor_url, 'size' => 80));
	$form_body .= "</div>";
	
	// Foot
	$form_body .= "<div class=\"elgg-foot\">";
	$form_body .= elgg_view('input/hidden', array('name' => 'guid', 'value' => $guid));
	$form_body .= elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));
	$form_body .= elgg_view('input/submit', array('value' => elgg_echo('event_manager:sponsors:form:save')));
	$form_body .= "</div>";
	
	$body = elgg_view('input/form', array(
		'id' 	=> 'event_manager_sponsor_edit',
		'name' 	=> 'event_manager_sponsor_edit', 
		'action' => $vars['url'] . 'action/event_manager/sponsors/edit',
		'body' => $form_body,
	));
	
	echo elgg_view_module('info', elgg_echo("event_manager:sponsors:form:title"), $body, array("class" => "elgg-module-event-manager-sponsors"));
}
else {
	// TODO: nice error message
	echo elgg_echo("error");
}