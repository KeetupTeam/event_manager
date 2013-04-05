<?php

forward();

gatekeeper();

$title_text = elgg_echo("event_manager:editregistration:title");

$guid = get_input("guid");

if ($entity = get_entity($guid)) {
	if ($entity->getSubtype() == Event::SUBTYPE) {
		$event = $entity;
	}
}

if (!empty($event)) {
	if ($event->canEdit()) {
		elgg_push_breadcrumb($entity->title, $event->getURL());
		elgg_push_breadcrumb($title_text);

		$output = elgg_view('event_manager/registration/wrapper', array(
			'entity' => $event,
				));

		$body = elgg_view_layout('content', array(
			'filter' => '',
			'content' => $output,
			'title' => $title_text,
				));

		echo elgg_view_page($title_text, $body);
	} else {
		forward($event->getURL());
	}
} else {
	register_error(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
	forward(REFERER);
}