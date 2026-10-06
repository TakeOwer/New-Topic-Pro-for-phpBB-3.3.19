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
	// Settings
	'ACP_NEWTOPIC_SETTINGS_EXPLAIN' => 'The “New topic” button appears in the breadcrumb bar of every page and opens a list of the forums the user can actually post in. Categories, links, locked forums and forums without permission are never clickable.',
	'ACP_NEWTOPIC_LEGEND_GENERAL'   => 'General',
	'ACP_NEWTOPIC_LEGEND_LIST'      => 'Forum list',
	'ACP_NEWTOPIC_LEGEND_LOOK'      => 'Appearance and phones',

	'ACP_NEWTOPIC_ENABLE'           => 'Show the button',
	'ACP_NEWTOPIC_ENABLE_EXPLAIN'   => 'When off, the button disappears from every page without uninstalling the extension.',
	'ACP_NEWTOPIC_GUESTS'           => 'Show it to guests too',
	'ACP_NEWTOPIC_GUESTS_EXPLAIN'   => 'It only appears if guests are allowed to start topics in at least one forum.',
	'ACP_NEWTOPIC_HIDE_PARENTS'     => 'Forums that contain subforums',
	'ACP_NEWTOPIC_HIDE_PARENTS_EXPLAIN' => 'Choose “Not selectable” if parent forums are not meant for posting: they stay visible as headers of their subforums.',
	'ACP_NEWTOPIC_PARENTS_ALLOW'    => 'Selectable',
	'ACP_NEWTOPIC_PARENTS_BLOCK'    => 'Not selectable',
	'ACP_NEWTOPIC_SHOW_DENIED'      => 'Show unavailable forums',
	'ACP_NEWTOPIC_SHOW_DENIED_EXPLAIN' => 'When on, forums the user cannot post in are listed in grey with the reason. When off they are hidden and the list stays shorter.',
	'ACP_NEWTOPIC_EXCLUDED'         => 'Excluded forums',
	'ACP_NEWTOPIC_EXCLUDED_EXPLAIN' => 'These forums cannot be picked from the button, even if the user may post there. Hold Ctrl (Cmd on Mac) to select more than one.',
	'ACP_NEWTOPIC_SEARCH'           => 'Search field',
	'ACP_NEWTOPIC_SEARCH_EXPLAIN'   => 'Filters the list while typing, ignoring case and accents.',
	'ACP_NEWTOPIC_CURRENT'          => 'Shortcut for the current forum',
	'ACP_NEWTOPIC_CURRENT_EXPLAIN'  => 'When the user is inside a forum or topic, “New topic here” appears at the top of the list.',
	'ACP_NEWTOPIC_RECENT'           => 'Recently used forums',
	'ACP_NEWTOPIC_RECENT_EXPLAIN'   => 'How many of the forums that user recently picked on that device to show at the top. 0 to turn off.',
	'ACP_NEWTOPIC_MOBILE'           => 'On phones',
	'ACP_NEWTOPIC_MOBILE_EXPLAIN'   => 'Below 700 pixels wide the list always opens as a bottom sheet.',
	'ACP_NEWTOPIC_MOBILE_BAR'       => 'Compact button in the bar',
	'ACP_NEWTOPIC_MOBILE_FAB'       => 'Floating button at the bottom',
	'ACP_NEWTOPIC_MOBILE_OFF'       => 'Hide',
	'ACP_NEWTOPIC_FAB_SIDE'         => 'Floating button side',
	'ACP_NEWTOPIC_FAB_SIDE_EXPLAIN' => 'Pick the free side if the other already has a chat or a “back to top” button.',
	'ACP_NEWTOPIC_FAB_LEFT'         => 'Left',
	'ACP_NEWTOPIC_FAB_RIGHT'        => 'Right',
	'ACP_NEWTOPIC_ACCENT'           => 'Accent colour',
	'ACP_NEWTOPIC_ACCENT_EXPLAIN'   => 'Colour of the button and of the highlight in the list.',
	'ACP_NEWTOPIC_ERR_ACCENT'       => 'The colour must be a hexadecimal value like #rrggbb.',
	'ACP_NEWTOPIC_SAVED'            => 'Settings saved.',

	// Badges and credits
	'ACP_NEWTOPIC_BADGE_VERSION'    => 'version',
	'ACP_NEWTOPIC_BADGE_PHPBB'      => 'phpBB',
	'ACP_NEWTOPIC_BADGE_PHP'        => 'PHP',
	'ACP_NEWTOPIC_BADGE_LICENSE'    => 'license',
	'ACP_NEWTOPIC_BADGE_OK'         => 'Compatible version',
	'ACP_NEWTOPIC_BADGE_BAD'        => 'Unsupported version',
	'ACP_NEWTOPIC_BADGE_DB_BAD'     => 'Database is at version %2$s, files at %1$s',
	'ACP_NEWTOPIC_DB_MISMATCH'      => 'The extension files are at version %1$s but the database is still at %2$s. Go to Customise › Manage extensions, disable New Topic Pro and enable it again (without deleting data).',
	'ACP_NEWTOPIC_CREDITS'          => 'Developed by <strong>Salvo Cortesiano</strong> | Support: <a href="mailto:info@netshadows.de">info@netshadows.de</a> | Based on “New Topic” by dmzx',

	// Check-up
	'ACP_NEWTOPIC_CHECKUP_EXPLAIN'  => 'Checks that the extension is installed correctly, that active styles have the required hooks, and shows the list exactly as a user will see it.',
	'ACP_NEWTOPIC_SIMULATE_USER'    => 'Simulate the list for user',
	'ACP_NEWTOPIC_SIMULATE_USER_EXPLAIN' => 'Optional. Enter a username to see which forums they can pick with their permissions. Leave empty to see your own list.',
	'ACP_NEWTOPIC_RUN'              => 'Run the check-up',
	'ACP_NEWTOPIC_SUMMARY'          => 'Passed: %1$d, warnings: %2$d, errors: %3$d',
	'ACP_NEWTOPIC_COL_STATUS'       => 'Result',
	'ACP_NEWTOPIC_COL_CHECK'        => 'Check',
	'ACP_NEWTOPIC_COL_DETAIL'       => 'Details',
	'ACP_NEWTOPIC_STATUS_OK'        => 'OK',
	'ACP_NEWTOPIC_STATUS_WARN'      => 'Warning',
	'ACP_NEWTOPIC_STATUS_ERROR'     => 'Error',
	'ACP_NEWTOPIC_STATUS_INFO'      => 'Info',
	'ACP_NEWTOPIC_PREVIEW_ADMIN'    => 'Preview of your list',
	'ACP_NEWTOPIC_PREVIEW_USER'     => 'Preview of %s’s list',
	'ACP_NEWTOPIC_PREVIEW_EXPLAIN'  => 'Grey entries appear as headers or non-clickable forums; the reason is shown on the right.',
	'ACP_NEWTOPIC_PREVIEW_OK'       => 'Selectable',

	'ACP_NEWTOPIC_CHK_PHP'          => 'PHP version',
	'ACP_NEWTOPIC_CHK_PHP_OK'       => 'PHP %s, supported.',
	'ACP_NEWTOPIC_CHK_PHP_BAD'      => 'PHP %s: at least PHP 7.4 is required.',
	'ACP_NEWTOPIC_CHK_PHPBB'        => 'phpBB version',
	'ACP_NEWTOPIC_CHK_PHPBB_OK'     => 'phpBB %s, supported.',
	'ACP_NEWTOPIC_CHK_PHPBB_BAD'    => 'phpBB %s: the extension requires phpBB 3.3.x.',
	'ACP_NEWTOPIC_CHK_VERSION'      => 'Installed version',
	'ACP_NEWTOPIC_CHK_VERSION_OK'   => 'Files and database both at version %s.',
	'ACP_NEWTOPIC_CHK_VERSION_BAD'  => 'Files are at %1$s but the database at %2$s: disable and enable the extension without deleting data.',
	'ACP_NEWTOPIC_CHK_COMPOSER_BAD' => 'composer.json cannot be read: check that the file was uploaded intact.',
	'ACP_NEWTOPIC_CHK_ENABLED'      => 'Button enabled',
	'ACP_NEWTOPIC_CHK_ENABLED_ON'   => 'The button is shown.',
	'ACP_NEWTOPIC_CHK_ENABLED_OFF'  => 'The button is turned off in the Settings tab, so it appears on no page.',
	'ACP_NEWTOPIC_CHK_OLD'          => 'Old dmzx/newtopic extension',
	'ACP_NEWTOPIC_CHK_OLD_ON'       => 'Still enabled: the board shows two menus. Disable it and delete its data from Manage extensions.',
	'ACP_NEWTOPIC_CHK_OLD_OFF'      => 'Not enabled, no duplicate.',
	'ACP_NEWTOPIC_CHK_FILES'        => 'Extension files',
	'ACP_NEWTOPIC_CHK_FILES_OK'     => 'All %d main files are present and readable.',
	'ACP_NEWTOPIC_CHK_FILES_BAD'    => 'Missing or unreadable files: %s. Upload the extension again and purge the cache.',
	'ACP_NEWTOPIC_CHK_LANG'         => 'Languages',
	'ACP_NEWTOPIC_CHK_LANG_OK'      => 'Translations available for: %s.',
	'ACP_NEWTOPIC_CHK_LANG_BAD'     => 'No translation for: %s. Users of these languages will see English text.',
	'ACP_NEWTOPIC_CHK_STYLE'        => 'Style “%s”',
	'ACP_NEWTOPIC_CHK_STYLE_DEFAULT' => '(default)',
	'ACP_NEWTOPIC_CHK_STYLE_OK'     => 'Has both template events used by the extension.',
	'ACP_NEWTOPIC_CHK_STYLE_BAD'    => 'Missing template events %s: with this style the button does not appear or is unstyled.',
	'ACP_NEWTOPIC_CHK_STRUCTURE'    => 'Board structure',
	'ACP_NEWTOPIC_CHK_STRUCTURE_INFO' => 'Forums: %1$d, categories: %2$d, links: %3$d.',
	'ACP_NEWTOPIC_CHK_EXCLUDED'     => 'Excluded forums',
	'ACP_NEWTOPIC_CHK_EXCLUDED_OK'  => '%d excluded forums, all existing.',
	'ACP_NEWTOPIC_CHK_EXCLUDED_BAD' => 'These excluded forums no longer exist (ID %s): save the settings again to clean the list.',
	'ACP_NEWTOPIC_CHK_ACCENT'       => 'Accent colour',
	'ACP_NEWTOPIC_CHK_ACCENT_OK'    => 'Colour %s is valid.',
	'ACP_NEWTOPIC_CHK_ACCENT_BAD'   => 'The colour “%s” is not valid: the default blue is used.',
	'ACP_NEWTOPIC_CHK_SIM_ADMIN'    => 'List for you',
	'ACP_NEWTOPIC_CHK_SIM_GUESTS'   => 'List for guests',
	'ACP_NEWTOPIC_CHK_SIM_USER'     => 'List for %s',
	'ACP_NEWTOPIC_CHK_GUESTS_OFF'   => 'Button hidden from guests. With the current permissions they could pick %d forums.',
	'ACP_NEWTOPIC_CHK_USER_NOT_FOUND' => 'No user with this name.',
	'ACP_NEWTOPIC_CHK_SIM_NONE'     => 'No selectable forum: for this user the button does not appear.',
	'ACP_NEWTOPIC_CHK_SIM_OK'       => array(
		1 => '%d selectable forum.',
		2 => '%d selectable forums.',
	),
	'ACP_NEWTOPIC_CHK_SIM_DISABLED' => 'Not clickable: %s.',
	'ACP_NEWTOPIC_REASON_COUNT_CATEGORY' => array(1 => '%d category', 2 => '%d categories'),
	'ACP_NEWTOPIC_REASON_COUNT_LINK'     => array(1 => '%d link', 2 => '%d links'),
	'ACP_NEWTOPIC_REASON_COUNT_PARENT'   => array(1 => '%d forum with subforums', 2 => '%d forums with subforums'),
	'ACP_NEWTOPIC_REASON_COUNT_LOCKED'   => array(1 => '%d locked forum', 2 => '%d locked forums'),
	'ACP_NEWTOPIC_REASON_COUNT_NOPERM'   => array(1 => '%d without permission', 2 => '%d without permission'),
	'ACP_NEWTOPIC_REASON_COUNT_EXCLUDED' => array(1 => '%d excluded', 2 => '%d excluded'),
));
