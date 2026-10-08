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
 * Self-test run from the ACP "Check-up" tab.
 */
class checkup
{
	const EXT_NAME = 'salvocortesiano/newtopic';

	/** @var \phpbb\db\driver\driver_interface */
	protected $db;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/** @var forum_list */
	protected $forum_list;

	/** @var string */
	protected $root_path;

	/** @var array */
	protected $checks = array();

	public function __construct(\phpbb\db\driver\driver_interface $db, \phpbb\config\config $config, \phpbb\language\language $language, \phpbb\extension\manager $ext_manager, forum_list $forum_list, $root_path)
	{
		$this->db          = $db;
		$this->config      = $config;
		$this->language    = $language;
		$this->ext_manager = $ext_manager;
		$this->forum_list  = $forum_list;
		$this->root_path   = $root_path;
	}

	/**
	 * @param \phpbb\auth\auth $admin_auth  Auth of the admin running the check-up
	 * @param int              $admin_id
	 * @param string           $username    Optional user to simulate
	 * @param array            $meta        composer.json version / licence
	 * @return array ['checks' => [...], 'preview' => array|null]
	 */
	public function run(\phpbb\auth\auth $admin_auth, $admin_id, $username, array $meta)
	{
		$this->checks = array();
		$options      = $this->forum_list->config_options();

		$this->check_environment($meta);
		$this->check_old_extension();
		$this->check_files();
		$this->check_languages();
		$this->check_styles();
		$this->check_structure($options);
		$this->check_accent();

		// Admin: what the person running the check-up sees.
		$admin_list = $this->forum_list->build($admin_auth, $options);
		$this->add_simulation_check($this->language->lang('ACP_NEWTOPIC_CHK_SIM_ADMIN'), $admin_list);

		// Guests.
		$guest_auth = $this->auth_for_user(ANONYMOUS);
		if ($guest_auth)
		{
			$guest_list = $this->forum_list->build($guest_auth, $options);
			if (!empty($this->config['newtopic_guests']))
			{
				$this->add_simulation_check($this->language->lang('ACP_NEWTOPIC_CHK_SIM_GUESTS'), $guest_list);
			}
			else
			{
				$this->add('info', $this->language->lang('ACP_NEWTOPIC_CHK_SIM_GUESTS'), $this->language->lang('ACP_NEWTOPIC_CHK_GUESTS_OFF', $guest_list['selectable']));
			}
		}

		$preview = array(
			'title' => $this->language->lang('ACP_NEWTOPIC_PREVIEW_ADMIN'),
			'items' => $admin_list['items'],
		);

		// Optional: a specific member.
		$username = trim($username);
		if ($username !== '')
		{
			$user_row = $this->find_user($username);
			if (!$user_row)
			{
				$this->add('warn', $this->language->lang('ACP_NEWTOPIC_CHK_SIM_USER', $username), $this->language->lang('ACP_NEWTOPIC_CHK_USER_NOT_FOUND'));
			}
			else
			{
				$user_auth = $this->auth_for_user((int) $user_row['user_id']);
				$user_list = $this->forum_list->build($user_auth, $options);
				$this->add_simulation_check($this->language->lang('ACP_NEWTOPIC_CHK_SIM_USER', $user_row['username']), $user_list);
				$preview = array(
					'title' => $this->language->lang('ACP_NEWTOPIC_PREVIEW_USER', $user_row['username']),
					'items' => $user_list['items'],
				);
			}
		}

		return array('checks' => $this->checks, 'preview' => $preview);
	}

	protected function add($status, $title, $detail)
	{
		$this->checks[] = array('status' => $status, 'title' => $title, 'detail' => $detail);
	}

	protected function check_environment(array $meta)
	{
		$php_ok = version_compare(PHP_VERSION, '7.4', '>=');
		$this->add($php_ok ? 'ok' : 'error', $this->language->lang('ACP_NEWTOPIC_CHK_PHP'), $this->language->lang($php_ok ? 'ACP_NEWTOPIC_CHK_PHP_OK' : 'ACP_NEWTOPIC_CHK_PHP_BAD', PHP_VERSION));

		$phpbb_ok = phpbb_version_compare($this->config['version'], '3.3.0', '>=') && phpbb_version_compare($this->config['version'], '4.0.0-dev', '<');
		$this->add($phpbb_ok ? 'ok' : 'error', $this->language->lang('ACP_NEWTOPIC_CHK_PHPBB'), $this->language->lang($phpbb_ok ? 'ACP_NEWTOPIC_CHK_PHPBB_OK' : 'ACP_NEWTOPIC_CHK_PHPBB_BAD', $this->config['version']));

		$db_version = (string) $this->config['newtopic_version'];
		if ($meta['version'] === '?')
		{
			$this->add('warn', $this->language->lang('ACP_NEWTOPIC_CHK_VERSION'), $this->language->lang('ACP_NEWTOPIC_CHK_COMPOSER_BAD'));
		}
		else if ($db_version !== $meta['version'])
		{
			$this->add('error', $this->language->lang('ACP_NEWTOPIC_CHK_VERSION'), $this->language->lang('ACP_NEWTOPIC_CHK_VERSION_BAD', $meta['version'], $db_version));
		}
		else
		{
			$this->add('ok', $this->language->lang('ACP_NEWTOPIC_CHK_VERSION'), $this->language->lang('ACP_NEWTOPIC_CHK_VERSION_OK', $meta['version']));
		}

		$this->add(
			empty($this->config['newtopic_enable']) ? 'warn' : 'ok',
			$this->language->lang('ACP_NEWTOPIC_CHK_ENABLED'),
			$this->language->lang(empty($this->config['newtopic_enable']) ? 'ACP_NEWTOPIC_CHK_ENABLED_OFF' : 'ACP_NEWTOPIC_CHK_ENABLED_ON')
		);
	}

	protected function check_old_extension()
	{
		$old_enabled = $this->ext_manager->is_enabled('dmzx/newtopic');
		$this->add(
			$old_enabled ? 'error' : 'ok',
			$this->language->lang('ACP_NEWTOPIC_CHK_OLD'),
			$this->language->lang($old_enabled ? 'ACP_NEWTOPIC_CHK_OLD_ON' : 'ACP_NEWTOPIC_CHK_OLD_OFF')
		);
	}

	protected function check_files()
	{
		$path  = $this->ext_manager->get_extension_path(self::EXT_NAME, true);
		$files = array(
			'styles/all/theme/newtopic.css',
			'styles/all/template/js/newtopic.js',
			'styles/all/template/newtopic_widget.html',
			'styles/all/template/event/overall_header_breadcrumbs_after.html',
			'styles/all/template/event/overall_header_navigation_append.html',
			'styles/all/template/event/navbar_header_user_profile_append.html',
			'styles/all/template/event/navbar_header_logged_out_content.html',
			'styles/all/template/event/navbar_header_quick_links_after.html',
			'styles/all/template/event/overall_header_page_body_before.html',
			'styles/all/template/event/overall_header_head_append.html',
			'language/en/common.php',
		);

		$missing = array();
		foreach ($files as $file)
		{
			if (!is_readable($path . $file))
			{
				$missing[] = $file;
			}
		}

		if ($missing)
		{
			$this->add('error', $this->language->lang('ACP_NEWTOPIC_CHK_FILES'), $this->language->lang('ACP_NEWTOPIC_CHK_FILES_BAD', implode(', ', $missing)));
		}
		else
		{
			$this->add('ok', $this->language->lang('ACP_NEWTOPIC_CHK_FILES'), $this->language->lang('ACP_NEWTOPIC_CHK_FILES_OK', count($files)));
		}
	}

	protected function check_languages()
	{
		$path = $this->ext_manager->get_extension_path(self::EXT_NAME, true);

		$sql    = 'SELECT lang_iso FROM ' . LANG_TABLE;
		$result = $this->db->sql_query($sql);
		$missing = $present = array();
		while ($row = $this->db->sql_fetchrow($result))
		{
			$iso = basename($row['lang_iso']);
			if (is_readable($path . 'language/' . $iso . '/common.php') && is_readable($path . 'language/' . $iso . '/acp_newtopic.php'))
			{
				$present[] = $iso;
			}
			else
			{
				$missing[] = $iso;
			}
		}
		$this->db->sql_freeresult($result);

		if ($missing)
		{
			$this->add('warn', $this->language->lang('ACP_NEWTOPIC_CHK_LANG'), $this->language->lang('ACP_NEWTOPIC_CHK_LANG_BAD', implode(', ', $missing)));
		}
		else
		{
			$this->add('ok', $this->language->lang('ACP_NEWTOPIC_CHK_LANG'), $this->language->lang('ACP_NEWTOPIC_CHK_LANG_OK', implode(', ', $present)));
		}
	}

	/** @var array|null style_id => row */
	protected $styles = null;

	/** @var array chain key => event names */
	protected $event_cache = array();

	/**
	 * Every active style must expose the template events used by the chosen position.
	 */
	protected function check_styles()
	{
		$position = positions::sanitize((string) $this->config['newtopic_position']);
		$guests   = !empty($this->config['newtopic_guests']);
		$required = positions::required_events($position, $guests);
		$pos_name = $this->language->lang(positions::lang_key($position));

		foreach ($this->load_styles() as $style_id => $style)
		{
			if (empty($style['style_active']))
			{
				continue;
			}

			$events    = $this->style_events($style_id);
			$missing   = array_values(array_diff($required, $events));
			$available = $this->position_names($this->supported_positions($style_id, $guests));

			$title = $this->language->lang('ACP_NEWTOPIC_CHK_STYLE', $style['style_name']);
			if ((int) $this->config['default_style'] === $style_id)
			{
				$title .= ' ' . $this->language->lang('ACP_NEWTOPIC_CHK_STYLE_DEFAULT');
			}

			if ($missing)
			{
				$this->add('error', $title, $this->language->lang('ACP_NEWTOPIC_CHK_STYLE_BAD', $pos_name, implode(', ', $missing), $available));
			}
			else
			{
				$this->add('ok', $title, $this->language->lang('ACP_NEWTOPIC_CHK_STYLE_OK', $pos_name, $available));
			}
		}
	}

	/**
	 * Positions a style can show the button in.
	 *
	 * @param int  $style_id
	 * @param bool $guests
	 * @return string[]
	 */
	public function supported_positions($style_id, $guests = false)
	{
		$events    = $this->style_events((int) $style_id);
		$supported = array();

		foreach (positions::all() as $position)
		{
			if (!array_diff(positions::required_events($position, $guests), $events))
			{
				$supported[] = $position;
			}
		}

		return $supported;
	}

	protected function position_names(array $list)
	{
		if (!$list)
		{
			return $this->language->lang('ACP_NEWTOPIC_CHK_STYLE_NONE');
		}

		$names = array();
		foreach ($list as $position)
		{
			$names[] = $this->language->lang(positions::lang_key($position));
		}

		return implode(', ', $names);
	}

	protected function load_styles()
	{
		if ($this->styles === null)
		{
			$sql    = 'SELECT style_id, style_name, style_path, style_parent_id, style_active FROM ' . STYLES_TABLE;
			$result = $this->db->sql_query($sql);
			$this->styles = array();
			while ($row = $this->db->sql_fetchrow($result))
			{
				$this->styles[(int) $row['style_id']] = $row;
			}
			$this->db->sql_freeresult($result);
		}

		return $this->styles;
	}

	/**
	 * Template events found in the templates a style really uses.
	 *
	 * A child style that overrides a template (e.g. navbar_header.html)
	 * replaces the parent's copy, so each file is taken from the first
	 * style of the chain that has it, child first, like phpBB does.
	 *
	 * @param int $style_id
	 * @return string[]
	 */
	public function style_events($style_id)
	{
		$styles = $this->load_styles();
		$chain  = array();
		$guard  = 0;
		$cursor = (int) $style_id;

		while ($cursor && isset($styles[$cursor]) && $guard++ < 10)
		{
			$chain[] = basename($styles[$cursor]['style_path']);
			$cursor  = (int) $styles[$cursor]['style_parent_id'];
		}

		$key = implode('/', $chain);
		if (isset($this->event_cache[$key]))
		{
			return $this->event_cache[$key];
		}

		$seen   = array();
		$events = array();

		foreach ($chain as $style_path)
		{
			$dir = $this->root_path . 'styles/' . $style_path . '/template/';
			if (!is_dir($dir))
			{
				continue;
			}

			foreach ((array) glob($dir . '*.html') as $file)
			{
				$name = basename($file);
				if (isset($seen[$name]))
				{
					continue;
				}
				$seen[$name] = true;

				if (preg_match_all('/EVENT\s+([a-z0-9_]+)/', (string) @file_get_contents($file), $m))
				{
					foreach ($m[1] as $event_name)
					{
						$events[$event_name] = true;
					}
				}
			}
		}

		return $this->event_cache[$key] = array_keys($events);
	}

	protected function check_structure(array $options)
	{
		$counts = array(FORUM_CAT => 0, FORUM_POST => 0, FORUM_LINK => 0);
		$ids    = array();
		foreach ($this->forum_list->get_rows() as $row)
		{
			$counts[(int) $row['forum_type']] = (isset($counts[(int) $row['forum_type']]) ? $counts[(int) $row['forum_type']] : 0) + 1;
			$ids[(int) $row['forum_id']] = true;
		}

		$this->add('info', $this->language->lang('ACP_NEWTOPIC_CHK_STRUCTURE'), $this->language->lang('ACP_NEWTOPIC_CHK_STRUCTURE_INFO', $counts[FORUM_POST], $counts[FORUM_CAT], $counts[FORUM_LINK]));

		$stale = array();
		foreach ($options['excluded'] as $forum_id)
		{
			if (!isset($ids[$forum_id]))
			{
				$stale[] = $forum_id;
			}
		}

		if ($stale)
		{
			$this->add('warn', $this->language->lang('ACP_NEWTOPIC_CHK_EXCLUDED'), $this->language->lang('ACP_NEWTOPIC_CHK_EXCLUDED_BAD', implode(', ', $stale)));
		}
		else
		{
			$this->add('ok', $this->language->lang('ACP_NEWTOPIC_CHK_EXCLUDED'), $this->language->lang('ACP_NEWTOPIC_CHK_EXCLUDED_OK', count($options['excluded'])));
		}
	}

	protected function check_accent()
	{
		$ok = (bool) preg_match('/^#[0-9a-fA-F]{6}$/', (string) $this->config['newtopic_accent']);
		$this->add($ok ? 'ok' : 'warn', $this->language->lang('ACP_NEWTOPIC_CHK_ACCENT'), $this->language->lang($ok ? 'ACP_NEWTOPIC_CHK_ACCENT_OK' : 'ACP_NEWTOPIC_CHK_ACCENT_BAD', $this->config['newtopic_accent']));
	}

	protected function add_simulation_check($title, array $list)
	{
		if (!$list['selectable'])
		{
			$this->add('warn', $title, $this->language->lang('ACP_NEWTOPIC_CHK_SIM_NONE'));
			return;
		}

		$parts = array();
		foreach ($list['reasons'] as $reason => $count)
		{
			$parts[] = $this->language->lang('ACP_NEWTOPIC_REASON_COUNT_' . strtoupper($reason), $count);
		}

		$detail = $this->language->lang('ACP_NEWTOPIC_CHK_SIM_OK', $list['selectable']);
		if ($parts)
		{
			$detail .= ' ' . $this->language->lang('ACP_NEWTOPIC_CHK_SIM_DISABLED', implode(', ', $parts));
		}

		$this->add('ok', $title, $detail);
	}

	protected function find_user($username)
	{
		$sql = 'SELECT user_id, username
			FROM ' . USERS_TABLE . "
			WHERE username_clean = '" . $this->db->sql_escape(utf8_clean_string($username)) . "'";
		$result = $this->db->sql_query_limit($sql, 1);
		$row    = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		return $row ?: null;
	}

	/**
	 * A fresh auth object loaded with another user's permissions.
	 */
	protected function auth_for_user($user_id)
	{
		$sql = 'SELECT user_id, username, user_type, user_permissions
			FROM ' . USERS_TABLE . '
			WHERE user_id = ' . (int) $user_id;
		$result = $this->db->sql_query_limit($sql, 1);
		$row    = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);

		if (!$row)
		{
			return null;
		}

		$auth = new \phpbb\auth\auth();
		$auth->acl($row);

		return $auth;
	}
}
