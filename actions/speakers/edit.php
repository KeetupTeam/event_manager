<?php

/**
 * Action speakers edit
 */

$guid = get_input('guid');
$container_guid = get_input('container_guid');
$name = get_input('speaker_name');
$url = get_input('speaker_url');
$bio = get_input('speaker_bio');

$eventspeaker = get_entity($guid);
$event = get_entity($container_guid);

// Validate
if (!($event instanceof Event)) {
	register_error(elgg_echo('event_manager:speakers:save:error'));
	forward(REFERER);
}
if (!$event->canWriteToContainer()) {
	register_error(elgg_echo('event_manager:speakers:save:error'));
	forward(REFERER);
}

// Is new?
$new = true;
if ($eventspeaker instanceof EventSpeaker) {
	$new = false;
}

// Validate image
if ($new && !isset($_FILES['speaker_image'])) {
	register_error(elgg_echo('event_manager:speakers:image:error'));
	forward(REFERER);
}
if (isset($_FILES['speaker_image']) && $_FILES['speaker_image']['error'] != 0) {
	register_error(elgg_echo('event_manager:speakers:image:error'));
	forward(REFERER);
}
if (isset($_FILES['speaker_image']) && !substr_count($_FILES['speaker_image']['type'],'image/')) {
	register_error(elgg_echo('event_manager:speakers:image:type:error'));
	forward(REFERER);
}

// Validate name
if (empty($name)) {
	register_error(elgg_echo('event_manager:speakers:name:error'));
	forward(REFERER);
}

// Validate url
//$v = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i";
$v = "/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i";
if (!empty($url) && !preg_match($v, $url)) {
	register_error(elgg_echo('event_manager:speakers:url:error'));
	forward(REFERER);
}

// Validate bio
if (!empty($bio) && strlen($bio) > EVENT_MANAGER_EVENTSPEAKER_BIO_MAX) {
	register_error(elgg_echo('event_manager:speakers:bio:error', array(EVENT_MANAGER_EVENTSPEAKER_BIO_MAX)));
	forward(REFERER);
}

if ($new) {
	$eventspeaker = new EventSpeaker();
	$eventspeaker->owner_guid = $event->owner_guid;
	$eventspeaker->container_guid = $event->guid;
	$eventspeaker->access_id = $event->access_id;
}

$eventspeaker->speaker_name = $name;
$eventspeaker->speaker_url = $url;
$eventspeaker->speaker_bio = $bio;

$success = $eventspeaker->save();

// Save image
$icon_sizes = elgg_get_config('icon_sizes');

$prefix = "eventspeaker/" . $eventspeaker->guid;

$filehandler = new ElggFile();
$filehandler->owner_guid = $eventspeaker->owner_guid;
$filehandler->setFilename($prefix . ".jpg");
$filehandler->open("write");
$filehandler->write(get_uploaded_file('speaker_image'));
$filehandler->close();
$filename = $filehandler->getFilenameOnFilestore();

$sizes = array('tiny', 'small', 'medium', 'large');

$thumbs = array();
foreach ($sizes as $size) {
	$thumbs[$size] = get_resized_image_from_existing_file(
		$filename,
		$icon_sizes[$size]['w'],
		$icon_sizes[$size]['h'],
		$icon_sizes[$size]['square']
	);
}

if ($thumbs['tiny']) { // just checking if resize successful
	$thumb = new ElggFile();
	$thumb->owner_guid = $eventspeaker->owner_guid;
	$thumb->setMimeType('image/jpeg');

	foreach ($sizes as $size) {
		$thumb->setFilename("{$prefix}{$size}.jpg");
		$thumb->open("write");
		$thumb->write($thumbs[$size]);
		$thumb->close();
	}

	$eventspeaker->icontime = time();
}

if ($success) {
	$output = array();
	$output['guid'] = $success;
	$output['list'] = $event->listEventSpeakers();
	
	echo json_encode($output);
	
	system_message(elgg_echo('event_manager:speakers:save:success'));
}
else {
	register_error(elgg_echo('event_manager:speakers:save:error'));
}

forward(REFERER);