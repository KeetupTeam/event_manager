<?php

/**
 * Event Sponsor
 */

class EventSponsor extends ElggObject {

	const SUBTYPE = "eventsponsor";

	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes["subtype"] = self::SUBTYPE;
	}

}