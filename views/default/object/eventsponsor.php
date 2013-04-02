<?php

/**
 * Object event sponsor
 */

$entity = elgg_extract('entity', $vars, false);
if (!($entity instanceof EventSponsor)) {
	return false;
}

$full = elgg_extract('full', $vars, false);

if ($full) {
	$href = FALSE;
	if (!empty($entity->sponsor_url)) {
		$href = $entity->sponsor_url;
	}
	
	$image = elgg_view_entity_icon($entity, 'medium', array(
		'href' => $href,
		'target' => '_blank',
	));
	
	$title = $entity->sponsor_name;
	if ($href) {
		$title = elgg_view('output/url', array(
			'href' => $href,
			'text' => $entity->sponsor_name,
			'target' => '_blank',
		));
	}
	
//	$metadata = elgg_view_menu('entity', array(
//		'entity' => $entity,
//		'handler' => 'event_manager',
//		'sort_by' => 'priority',
//		'class' => 'elgg-menu-hz',
//	));
	
	if($entity->canEdit()) {
//		$edit = "<a href='#' class='event_manager_program_slot_edit' rel='" . $slot->getGUID() . "'>" . elgg_echo("edit") . "</a>";
//		$delete_slot = "<a href='#' class='event_manager_program_slot_delete'>" . elgg_echo("delete") . "</a>";
		
		$edit = elgg_view('output/url', array(
			'href' => 'javascript:void(0)',
			'text' => elgg_Echo('edit'),
			'class' => 'event-manager-sponsors-edit',
			'rel' => $entity->getGUID(),
		));
		
		$delete = elgg_view('output/url', array(
			'href' => elgg_add_action_tokens_to_url('action/event_manager/sponsors/delete?guid='.$entity->guid),
			'text' => elgg_echo('delete'),
			'class' => 'event-manager-sponsors-delete',
		));

		$title .= " [ " . $edit . " | " . $delete . " ]";
	}
	
	$params = array(
		'entity' => $entity,
		'title' => $title,
//		'metadata' => $metadata,
		'content' => $content,
	);
	$params = $params + $vars;
	$body = elgg_view('object/elements/summary', $params);
	
	echo elgg_view_image_block($image, $body, $vars);
?>

<?php
}