<?php
/**
 *
 * New Topic Pro. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2015 dmzx - original "New Topic"
 * @copyright (c) 2026 Salvo Cortesiano <https://netshadows.de>
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 */

namespace salvocortesiano\newtopic\core;

/**
 * Builds the list of forums shown in the "new topic" picker.
 *
 * Every forum gets a "reason" that is empty when the user can really open a
 * topic there, so the picker never offers a forum that posting.php would
 * refuse (categories, links, locked forums, missing permissions...).
 */
class forum_list
{
	const REASON_NONE     = '';
	const REASON_CATEGORY = 'category';
	const REASON_LINK     = 'link';
	const REASON_PARENT   = 'parent';
	const REASON_LOCKED   = 'locked';
	const REASON_NOPERM   = 'noperm';
	const REASON_EXCLUDED = 'excluded';

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\config\db_text */
	protected $config_text;

	/** @var string */
	protected $root_path;

	/** @var string */
	protected $php_ext;

	/** @var array|null */
	protected $rows = null;

	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config, \phpbb\config\db_text $config_text, $root_path, $php_ext)
	{
		$this->db          = $db;
		$this->config      = $config;
		$this->config_text = $config_text;
		$this->root_path   = $root_path;
		$this->php_ext     = $php_ext;
	}

	/**
	 * All forums ordered as in the board index (cached query, 10 minutes;
	 * phpBB clears this cache whenever forums are edited in the ACP).
	 */
	public function get_rows()
	{
		if ($this->rows === null)
		{
			$sql = 'SELECT forum_id, parent_id, forum_name, forum_type, forum_status, left_id, right_id, forum_topics_approved
				FROM ' . FORUMS_TABLE . '
				ORDER BY left_id ASC';
			$result = $this->db->sql_query($sql, 600);
			$this->rows = $this->db->sql_fetchrowset($result);
			$this->db->sql_freeresult($result);
		}

		return $this->rows;
	}

	/**
	 * Forum ids excluded in the ACP.
	 */
	public function get_excluded_ids()
	{
		$raw = $this->config_text->get('newtopic_excluded');
		$ids = $raw ? json_decode($raw, true) : array();

		return is_array($ids) ? array_values(array_unique(array_map('intval', $ids))) : array();
	}

	public function set_excluded_ids(array $ids)
	{
		$ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
		$this->config_text->set('newtopic_excluded', json_encode($ids));
	}

	/**
	 * Build the picker items for a given auth object.
	 *
	 * @param \phpbb\auth\auth $auth
	 * @param array            $options hide_parents (bool), show_denied (bool), excluded (int[])
	 * @return array ['items' => [...], 'selectable' => int, 'reasons' => [reason => count]]
	 */
	public function build(\phpbb\auth\auth $auth, array $options = array())
	{
		$hide_parents = !empty($options['hide_parents']);
		$show_denied  = !empty($options['show_denied']);
		$excluded     = isset($options['excluded']) ? array_flip(array_map('intval', $options['excluded'])) : array();

		$nodes      = array();
		$stack      = array();
		$skip_until = 0;

		foreach ($this->get_rows() as $row)
		{
			$forum_id = (int) $row['forum_id'];
			$left     = (int) $row['left_id'];
			$right    = (int) $row['right_id'];

			// Inside a branch the user cannot even see: skip it whole.
			if ($skip_until && $left < $skip_until)
			{
				continue;
			}
			$skip_until = 0;

			if (!$auth->acl_get('f_list', $forum_id))
			{
				$skip_until = $right;
				continue;
			}

			// Close the branches that ended before this forum.
			while ($stack && $nodes[end($stack)]['right'] < $left)
			{
				array_pop($stack);
			}

			$reason = $this->reason_for($auth, $row, $hide_parents, isset($excluded[$forum_id]));

			$nodes[$forum_id] = array(
				'id'         => $forum_id,
				'parent_id'  => (int) $row['parent_id'],
				'name'       => $row['forum_name'],
				'type'       => (int) $row['forum_type'],
				'topics'     => (int) $row['forum_topics_approved'],
				'left'       => $left,
				'right'      => $right,
				'depth'      => count($stack),
				'ancestors'  => $stack,
				'reason'     => $reason,
				'selectable' => $reason === self::REASON_NONE,
				'visible'    => false,
				'has_kids'   => false,
			);

			$own_visible = $reason === self::REASON_NONE
				|| ($show_denied && (int) $row['forum_type'] === FORUM_POST);

			if ($own_visible)
			{
				$nodes[$forum_id]['visible'] = true;
				foreach ($stack as $ancestor_id)
				{
					$nodes[$ancestor_id]['visible'] = true;
				}
			}

			$stack[] = $forum_id;
		}

		$items      = array();
		$selectable = 0;
		$reasons    = array();

		foreach ($nodes as $forum_id => $node)
		{
			if (!$node['visible'])
			{
				continue;
			}

			if ($node['parent_id'] && isset($nodes[$node['parent_id']]))
			{
				$items_parent = $node['parent_id'];
				if (isset($items[$items_parent]))
				{
					$items[$items_parent]['has_kids'] = true;
				}
			}

			$node['url'] = append_sid("{$this->root_path}posting.{$this->php_ext}", array('mode' => 'post', 'f' => $forum_id));
			$items[$forum_id] = $node;

			if ($node['selectable'])
			{
				$selectable++;
			}
			else
			{
				$reasons[$node['reason']] = isset($reasons[$node['reason']]) ? $reasons[$node['reason']] + 1 : 1;
			}
		}

		return array(
			'items'      => array_values($items),
			'selectable' => $selectable,
			'reasons'    => $reasons,
		);
	}

	/**
	 * Same checks posting.php does before accepting a new topic.
	 */
	protected function reason_for(\phpbb\auth\auth $auth, array $row, $hide_parents, $is_excluded)
	{
		$forum_id = (int) $row['forum_id'];
		$type     = (int) $row['forum_type'];

		if ($type === FORUM_LINK)
		{
			return self::REASON_LINK;
		}
		if ($type !== FORUM_POST)
		{
			return self::REASON_CATEGORY;
		}
		if ($is_excluded)
		{
			return self::REASON_EXCLUDED;
		}
		if ($hide_parents && ((int) $row['right_id'] - (int) $row['left_id']) > 1)
		{
			return self::REASON_PARENT;
		}
		if (!$auth->acl_get('f_post', $forum_id))
		{
			return self::REASON_NOPERM;
		}
		if ((int) $row['forum_status'] === ITEM_LOCKED && !$auth->acl_get('m_edit', $forum_id))
		{
			return self::REASON_LOCKED;
		}

		return self::REASON_NONE;
	}

	/**
	 * Options as configured in the ACP.
	 */
	public function config_options()
	{
		return array(
			'hide_parents' => (bool) $this->config['newtopic_hide_parents'],
			'show_denied'  => (bool) $this->config['newtopic_show_denied'],
			'excluded'     => $this->get_excluded_ids(),
		);
	}
}
