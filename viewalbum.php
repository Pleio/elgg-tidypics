<?php

	/**
	 * Tidypics Album View Page
	 */

	include_once(dirname(dirname(dirname(__FILE__))) . "/engine/start.php");

	// get the album entity
	$album_guid = (int) get_input('guid');
	$album = get_entity($album_guid);

	// panic if we can't get it
	if (!$album) forward();

	// container should always be set, but just in case
	if ($album->container_guid)
		set_page_owner($album->container_guid);
	else
		set_page_owner($album->owner_guid);

	$owner = page_owner_entity();

	// setup group menu
	if ($owner instanceof ElggGroup) {
		add_submenu_item(	sprintf(elgg_echo('album:group'),$owner->name), 
							$CONFIG->wwwroot . "pg/photos/owned/" . $owner->username);
	}

	if (can_write_to_container(0, $album->container_guid)) {
		if ($owner instanceof ElggGroup) {
			add_submenu_item(	elgg_echo('album:create'),
								$CONFIG->wwwroot . 'pg/photos/new/' . $owner->username,
								'photos');
		}
		add_submenu_item(	elgg_echo('album:addpix'),
							$CONFIG->wwwroot . 'pg/photos/upload/' . $album_guid,
							'photos');
		add_submenu_item(	elgg_echo('album:edit'),
							$CONFIG->wwwroot . 'pg/photos/edit/' . $album_guid,
							'photos');
		add_submenu_item(	elgg_echo('album:delete'),
							$CONFIG->wwwroot . 'pg/photos/delete/' . $album_guid,
							'photos',
							true);
	}

	// set title and body
	$title = $album->title;
	$area2 = elgg_view_title($title);
	$area2 .= elgg_view_entity($album, true);
	$body = elgg_view_layout('two_column_left_sidebar', '', $area2);

	page_draw($title, $body);
?>