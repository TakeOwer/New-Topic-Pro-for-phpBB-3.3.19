<?php
/**
 *
 * New Topic Pro. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026 Salvo Cortesiano <https://netshadows.de>
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 */

if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'NEWTOPIC_BUTTON'         => 'New topic',
	'NEWTOPIC_BUTTON_TITLE'   => 'Start a new topic in a forum of your choice',
	'NEWTOPIC_PANEL_TITLE'    => 'Where do you want to post?',
	'NEWTOPIC_CLOSE'          => 'Close',
	'NEWTOPIC_SEARCH'         => 'Search forums…',
	'NEWTOPIC_SEARCH_LABEL'   => 'Search forums',
	'NEWTOPIC_HERE'           => 'New topic here',
	'NEWTOPIC_RECENT'         => 'Recently used',
	'NEWTOPIC_ALL'            => 'All forums',
	'NEWTOPIC_NO_RESULTS'     => 'No forum contains “%s”. Try a different word.',
	'NEWTOPIC_RESULTS'        => array(
		0 => 'No forums found',
		1 => '%d forum found',
		2 => '%d forums found',
	),
	'NEWTOPIC_TOGGLE'         => 'Show or hide subforums',
	'NEWTOPIC_KBD_HINT'       => '↑ ↓ to choose, Enter to open, Esc to close',
	'NEWTOPIC_TOPICS'         => array(
		0 => 'no topics',
		1 => '%d topic',
		2 => '%d topics',
	),

	'NEWTOPIC_REASON_CATEGORY' => 'Category: pick one of the forums inside it',
	'NEWTOPIC_REASON_LINK'     => 'External link',
	'NEWTOPIC_REASON_PARENT'   => 'Has subforums: pick one of those below',
	'NEWTOPIC_REASON_LOCKED'   => 'Forum locked',
	'NEWTOPIC_REASON_NOPERM'   => 'You are not allowed to start topics here',
	'NEWTOPIC_REASON_EXCLUDED' => 'Not available for new topics',
));
