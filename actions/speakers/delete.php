<?php 

/**
 * Event manager speaker delete
 */

$guid = get_input("guid");
$eventspeaker = get_entity($guid);

$success = false;

if($eventspeaker instanceof EventSpeaker) {
	$container = $eventspeaker->getContainerEntity();
	if ($eventspeaker->canEdit() && $container->canWriteToContainer()) {
		$success = $eventspeaker->delete();
	}
}

if ($success) {
	system_message(elgg_echo('event_manager:speakers:delete:success'));
	
	$output = array();
	
	if ($container instanceof Event) {
		$list =  $container->listEventSpeakers();
		if (empty($list)) {
			$list = '<p class="speakers_empty">'.elgg_echo('event_manager:speakers:empty').'</p>';
		}
		$output['guid'] = $guid;
		$output['list'] = $list;
	}
	
	echo json_encode($output);
}
else {
	register_error(elgg_echo('event_manager:speakers:delete:error'));
}

forward(REFERER);