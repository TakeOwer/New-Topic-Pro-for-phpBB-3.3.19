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
	'ACP_NEWTOPIC_TITLE'    => 'New Topic Pro',
	'ACP_NEWTOPIC_SETTINGS' => 'Settings',
	'ACP_NEWTOPIC_CHECKUP'  => 'Check-up',
	'LOG_NEWTOPIC_SETTINGS' => '<strong>New Topic Pro: settings updated</strong>',
));
