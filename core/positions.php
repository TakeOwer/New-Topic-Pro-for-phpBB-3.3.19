<?php
/**
 *
 * New Topic Pro. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026 Salvo Cortesiano <https://netshadows.de>
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 */

namespace salvocortesiano\newtopic\core;

/**
 * Where the "New topic" button can be placed, and the template event
 * each place hooks into. Single source of truth for the listener, the
 * ACP and the Check-up.
 */
class positions
{
	const DEFAULT_POSITION = 'breadcrumbs';

	/**
	 * position => template events that must exist in the style.
	 * The first event is the one that renders the button; for
	 * "navbar_right" guests use a second event (see guest_event()).
	 */
	protected static $map = array(
		'breadcrumbs'  => array('overall_header_breadcrumbs_after'),
		'navbar_left'  => array('overall_header_navigation_append'),
		'navbar_right' => array('navbar_header_user_profile_append'),
		'quicklinks'   => array('navbar_header_quick_links_after'),
		'page_top'     => array('overall_header_page_body_before'),
		'floating'     => array('overall_header_page_body_before'),
	);

	/** Event used for guests where it differs from registered users. */
	protected static $guest_map = array(
		'navbar_right' => 'navbar_header_logged_out_content',
	);

	/** Event every position needs, for the stylesheet. */
	const HEAD_EVENT = 'overall_header_head_append';

	/**
	 * @return string[] All position keys, in display order.
	 */
	public static function all()
	{
		return array_keys(self::$map);
	}

	public static function is_valid($position)
	{
		return isset(self::$map[$position]);
	}

	public static function sanitize($position)
	{
		return self::is_valid($position) ? $position : self::DEFAULT_POSITION;
	}

	/**
	 * Template events the style must provide for a position.
	 *
	 * @param string $position
	 * @param bool   $guests   Whether the button is also shown to guests
	 * @return string[]
	 */
	public static function required_events($position, $guests = false)
	{
		$position = self::sanitize($position);
		$events   = array_merge(array(self::HEAD_EVENT), self::$map[$position]);

		if ($guests && isset(self::$guest_map[$position]))
		{
			$events[] = self::$guest_map[$position];
		}

		return array_values(array_unique($events));
	}

	/**
	 * Language key of a position's name, e.g. ACP_NEWTOPIC_POS_NAVBAR_LEFT.
	 */
	public static function lang_key($position)
	{
		return 'ACP_NEWTOPIC_POS_' . strtoupper(self::sanitize($position));
	}
}
