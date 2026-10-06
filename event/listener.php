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

namespace salvocortesiano\newtopic\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class listener implements EventSubscriberInterface
{
	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \salvocortesiano\newtopic\core\forum_list */
	protected $forum_list;

	public function __construct(\phpbb\template\template $template, \phpbb\db\driver\driver_interface $db, \phpbb\auth\auth $auth, \phpbb\user $user, \phpbb\language\language $language, \phpbb\config\config $config, \phpbb\request\request_interface $request, \salvocortesiano\newtopic\core\forum_list $forum_list)
	{
		$this->template   = $template;
		$this->db         = $db;
		$this->auth       = $auth;
		$this->user       = $user;
		$this->language   = $language;
		$this->config     = $config;
		$this->request    = $request;
		$this->forum_list = $forum_list;
	}

	public static function getSubscribedEvents()
	{
		return array(
			'core.page_header' => 'page_header',
		);
	}

	public function page_header($event)
	{
		if (empty($this->config['newtopic_enable']) || !empty($this->user->data['is_bot']))
		{
			return;
		}

		if (empty($this->user->data['is_registered']) && empty($this->config['newtopic_guests']))
		{
			return;
		}

		$list = $this->forum_list->build($this->auth, $this->forum_list->config_options());

		// Nowhere to write: show nothing rather than an empty menu.
		if (!$list['selectable'])
		{
			return;
		}

		$this->language->add_lang('common', 'salvocortesiano/newtopic');

		$current_id   = $this->current_forum_id();
		$current_item = null;

		foreach ($list['items'] as $item)
		{
			if ($item['id'] === $current_id && $item['selectable'])
			{
				$current_item = $item;
			}

			$this->template->assign_block_vars('newtopic_forums', array(
				'ID'           => $item['id'],
				'NAME'         => $item['name'],
				'DEPTH'        => $item['depth'],
				'ANCESTORS'    => implode(' ', $item['ancestors']),
				'U_POST'       => $item['url'],
				'TOPICS'       => $this->language->lang('NEWTOPIC_TOPICS', $item['topics']),
				'REASON'       => $item['reason'],
				'REASON_TEXT'  => $item['reason'] !== '' ? $this->language->lang('NEWTOPIC_REASON_' . strtoupper($item['reason'])) : '',
				'S_SELECTABLE' => $item['selectable'],
				'S_HAS_KIDS'   => $item['has_kids'],
				'S_CATEGORY'   => $item['type'] !== FORUM_POST,
				'S_CURRENT'    => $item['id'] === $current_id,
			));
		}

		$accent = (string) $this->config['newtopic_accent'];
		if (!preg_match('/^#[0-9a-fA-F]{6}$/', $accent))
		{
			$accent = '#105289';
		}

		// Plural forms for the live "N forums found" message, kept raw (%d) for the script.
		$results = $this->language->lang_raw('NEWTOPIC_RESULTS');
		$results = is_array($results) ? $results : array();

		$this->template->assign_vars(array(
			'NEWTOPIC_L_RESULTS_0' => isset($results[0]) ? $results[0] : '',
			'NEWTOPIC_L_RESULTS_1' => isset($results[1]) ? $results[1] : '',
			'NEWTOPIC_L_RESULTS_2' => isset($results[2]) ? $results[2] : '',
			'S_NEWTOPIC_SHOW'      => true,
			'S_NEWTOPIC_SEARCH'    => (bool) $this->config['newtopic_search'],
			'NEWTOPIC_MOBILE'      => in_array($this->config['newtopic_mobile_mode'], array('bar', 'fab', 'off'), true) ? $this->config['newtopic_mobile_mode'] : 'bar',
			'NEWTOPIC_FAB_SIDE'    => $this->config['newtopic_fab_side'] === 'left' ? 'left' : 'right',
			'NEWTOPIC_ACCENT'      => $accent,
			'NEWTOPIC_RECENT'      => max(0, min(10, (int) $this->config['newtopic_recent'])),
			'NEWTOPIC_USER_KEY'    => (int) $this->user->data['user_id'],
			'NEWTOPIC_COUNT'       => $list['selectable'],
			'S_NEWTOPIC_CURRENT'   => $current_item !== null && !empty($this->config['newtopic_current']),
			'NEWTOPIC_CURRENT_ID'  => $current_item ? $current_item['id'] : 0,
			'NEWTOPIC_CURRENT_NAME' => $current_item ? $current_item['name'] : '',
			'U_NEWTOPIC_CURRENT'   => $current_item ? $current_item['url'] : '',
		));
	}

	/**
	 * Forum the user is currently looking at (viewforum, viewtopic, posting).
	 */
	protected function current_forum_id()
	{
		$forum_id = $this->request->variable('f', 0);
		if ($forum_id)
		{
			return (int) $forum_id;
		}

		$topic_id = $this->request->variable('t', 0);
		$post_id  = $this->request->variable('p', 0);

		if ($topic_id)
		{
			$sql = 'SELECT forum_id FROM ' . TOPICS_TABLE . ' WHERE topic_id = ' . (int) $topic_id;
		}
		else if ($post_id)
		{
			$sql = 'SELECT forum_id FROM ' . POSTS_TABLE . ' WHERE post_id = ' . (int) $post_id;
		}
		else
		{
			return 0;
		}

		$result   = $this->db->sql_query_limit($sql, 1);
		$forum_id = (int) $this->db->sql_fetchfield('forum_id');
		$this->db->sql_freeresult($result);

		return $forum_id;
	}
}
