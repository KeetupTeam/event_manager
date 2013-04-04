<?php 
$guid = (int) get_input("guid");

if(!empty($guid) && $entity = get_entity($guid)){
	if($entity->getSubtype() == Event::SUBTYPE)	{
		$event = $entity;
		if($event->delete())		{
			system_message(elgg_echo("event_manager:action:event:delete:ok"));
		} 
		
		$forward_url = '/events';
		if (get_input('admin', 0)) {
			$forward_url = REFERER;
		}
		forward($forward_url);
	}
}

system_message(elgg_echo("InvalidParameterException:GUIDNotFound", array($guid)));
forward(REFERER);