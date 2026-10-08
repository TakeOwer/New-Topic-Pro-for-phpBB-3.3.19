<?php
/**
 *
 * New Topic Pro. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026 Salvo Cortesiano <https://netshadows.de>
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 */

namespace salvocortesiano\newtopic\acp;

class main_module
{
	const EXT_NAME = 'salvocortesiano/newtopic';

	/** @var string */
	public $u_action;

	/** @var string */
	public $tpl_name;

	/** @var string */
	public $page_title;

	/** @var \phpbb\config\config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\language\language */
	protected $language;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var \phpbb\log\log_interface */
	protected $log;

	/** @var \phpbb\user */
	protected $user;

	/** @var \phpbb\auth\auth */
	protected $auth;

	/** @var \phpbb\extension\manager */
	protected $ext_manager;

	/** @var \salvocortesiano\newtopic\core\forum_list */
	protected $forum_list;

	/** @var \salvocortesiano\newtopic\core\checkup */
	protected $checkup;

	public function main($id, $mode)
	{
		global $phpbb_container;

		$this->config      = $phpbb_container->get('config');
		$this->template    = $phpbb_container->get('template');
		$this->language    = $phpbb_container->get('language');
		$this->request     = $phpbb_container->get('request');
		$this->log         = $phpbb_container->get('log');
		$this->user        = $phpbb_container->get('user');
		$this->auth        = $phpbb_container->get('auth');
		$this->ext_manager = $phpbb_container->get('ext.manager');
		$this->forum_list  = $phpbb_container->get('salvocortesiano.newtopic.forum_list');
		$this->checkup     = $phpbb_container->get('salvocortesiano.newtopic.checkup');

		$this->language->add_lang(array('acp_newtopic', 'common'), self::EXT_NAME);

		add_form_key('salvocortesiano_newtopic');

		$this->assign_badges();

		if ($mode === 'checkup')
		{
			$this->tpl_name   = 'acp_newtopic_checkup';
			$this->page_title = 'ACP_NEWTOPIC_CHECKUP';
			$this->checkup_page();
			return;
		}

		$this->tpl_name   = 'acp_newtopic_settings';
		$this->page_title = 'ACP_NEWTOPIC_SETTINGS';
		$this->settings_page();
	}

	protected function settings_page()
	{
		$errors = array();

		if ($this->request->is_set_post('submit'))
		{
			if (!check_form_key('salvocortesiano_newtopic'))
			{
				$errors[] = $this->language->lang('FORM_INVALID');
			}

			$accent = strtolower(trim($this->request->variable('newtopic_accent', '#105289')));
			if (!preg_match('/^#[0-9a-f]{6}$/', $accent))
			{
				$errors[] = $this->language->lang('ACP_NEWTOPIC_ERR_ACCENT');
			}

			$position = $this->request->variable('newtopic_position', \salvocortesiano\newtopic\core\positions::DEFAULT_POSITION);
			if (!\salvocortesiano\newtopic\core\positions::is_valid($position))
			{
				$errors[] = $this->language->lang('ACP_NEWTOPIC_ERR_POSITION');
			}

			$mobile = $this->request->variable('newtopic_mobile_mode', 'bar');
			if (!in_array($mobile, array('bar', 'fab', 'off'), true))
			{
				$mobile = 'bar';
			}

			if (!$errors)
			{
				$this->config->set('newtopic_enable', $this->request->variable('newtopic_enable', 0) ? 1 : 0);
				$this->config->set('newtopic_guests', $this->request->variable('newtopic_guests', 0) ? 1 : 0);
				$this->config->set('newtopic_position', $position);
				$this->config->set('newtopic_hide_parents', $this->request->variable('newtopic_hide_parents', 0) ? 1 : 0);
				$this->config->set('newtopic_show_denied', $this->request->variable('newtopic_show_denied', 0) ? 1 : 0);
				$this->config->set('newtopic_search', $this->request->variable('newtopic_search', 0) ? 1 : 0);
				$this->config->set('newtopic_current', $this->request->variable('newtopic_current', 0) ? 1 : 0);
				$this->config->set('newtopic_recent', max(0, min(10, $this->request->variable('newtopic_recent', 5))));
				$this->config->set('newtopic_mobile_mode', $mobile);
				$this->config->set('newtopic_fab_side', $this->request->variable('newtopic_fab_side', 'right') === 'left' ? 'left' : 'right');
				$this->config->set('newtopic_accent', $accent);
				$this->forum_list->set_excluded_ids($this->request->variable('newtopic_excluded', array(0)));

				$this->log->add('admin', $this->user->data['user_id'], $this->user->ip, 'LOG_NEWTOPIC_SETTINGS');

				trigger_error($this->language->lang('ACP_NEWTOPIC_SAVED') . adm_back_link($this->u_action));
			}
		}

		if (!function_exists('make_forum_select'))
		{
			global $phpbb_root_path, $phpEx;
			include($phpbb_root_path . 'includes/functions_admin.' . $phpEx);
		}

		$this->assign_positions();

		$this->template->assign_vars(array(
			'S_ERROR'                => (bool) $errors,
			'ERROR_MSG'              => implode('<br>', $errors),
			'U_ACTION'               => $this->u_action,

			'NEWTOPIC_ENABLE'        => (bool) $this->config['newtopic_enable'],
			'NEWTOPIC_GUESTS'        => (bool) $this->config['newtopic_guests'],
			'NEWTOPIC_HIDE_PARENTS'  => (bool) $this->config['newtopic_hide_parents'],
			'NEWTOPIC_SHOW_DENIED'   => (bool) $this->config['newtopic_show_denied'],
			'NEWTOPIC_SEARCH'        => (bool) $this->config['newtopic_search'],
			'NEWTOPIC_CURRENT'       => (bool) $this->config['newtopic_current'],
			'NEWTOPIC_RECENT'        => (int) $this->config['newtopic_recent'],
			'NEWTOPIC_MOBILE_MODE'   => $this->config['newtopic_mobile_mode'],
			'NEWTOPIC_FAB_SIDE'      => $this->config['newtopic_fab_side'],
			'NEWTOPIC_ACCENT'        => $this->config['newtopic_accent'],
			'S_EXCLUDED_OPTIONS'     => make_forum_select($this->forum_list->get_excluded_ids(), false, true, true, false),
		));
	}

	protected function checkup_page()
	{
		$ran      = false;
		$username = '';

		if ($this->request->is_set_post('run_checkup'))
		{
			if (!check_form_key('salvocortesiano_newtopic'))
			{
				trigger_error($this->language->lang('FORM_INVALID') . adm_back_link($this->u_action), E_USER_WARNING);
			}

			$ran      = true;
			$username = $this->request->variable('simulate_user', '', true);
			$report   = $this->checkup->run($this->auth, (int) $this->user->data['user_id'], $username, $this->composer_meta());

			$totals = array('ok' => 0, 'warn' => 0, 'error' => 0, 'info' => 0);
			foreach ($report['checks'] as $check)
			{
				$totals[$check['status']]++;
				$this->template->assign_block_vars('checks', array(
					'STATUS'      => $check['status'],
					'STATUS_TEXT' => $this->language->lang('ACP_NEWTOPIC_STATUS_' . strtoupper($check['status'])),
					'TITLE'       => $check['title'],
					'DETAIL'      => $check['detail'],
				));
			}

			if ($report['preview'])
			{
				foreach ($report['preview']['items'] as $item)
				{
					$this->template->assign_block_vars('preview', array(
						'NAME'         => $item['name'],
						'DEPTH'        => $item['depth'],
						'S_SELECTABLE' => $item['selectable'],
						'REASON_TEXT'  => $item['reason'] !== '' ? $this->language->lang('NEWTOPIC_REASON_' . strtoupper($item['reason'])) : '',
					));
				}
			}

			$this->template->assign_vars(array(
				'CHECKUP_OK'      => $totals['ok'],
				'CHECKUP_WARN'    => $totals['warn'],
				'CHECKUP_ERROR'   => $totals['error'],
				'S_PREVIEW'       => (bool) $report['preview'],
				'PREVIEW_TITLE'   => $report['preview'] ? $report['preview']['title'] : '',
			));
		}

		$this->template->assign_vars(array(
			'S_CHECKUP_RAN'  => $ran,
			'SIMULATE_USER'  => $username,
			'U_ACTION'       => $this->u_action,
		));
	}

	/**
	 * Options of the "Button position" select. Positions the default style
	 * cannot show are flagged, so the admin knows before saving.
	 */
	protected function assign_positions()
	{
		$current   = \salvocortesiano\newtopic\core\positions::sanitize((string) $this->config['newtopic_position']);
		$supported = $this->checkup->supported_positions((int) $this->config['default_style'], (bool) $this->config['newtopic_guests']);

		foreach (\salvocortesiano\newtopic\core\positions::all() as $position)
		{
			$this->template->assign_block_vars('positions', array(
				'VALUE'        => $position,
				'NAME'         => $this->language->lang(\salvocortesiano\newtopic\core\positions::lang_key($position)),
				'S_SELECTED'   => $position === $current,
				'S_SUPPORTED'  => in_array($position, $supported, true),
			));
		}

		$this->template->assign_vars(array(
			'S_NT_POSITION_SUPPORTED' => in_array($current, $supported, true),
		));
	}

	/**
	 * Version and licence read from composer.json: one source of truth.
	 */
	protected function composer_meta()
	{
		$meta = array('version' => '?', 'license' => 'GPL-2.0-only');

		try
		{
			$data = $this->ext_manager->create_extension_metadata_manager(self::EXT_NAME)->get_metadata('all');
			$meta['version'] = isset($data['version']) ? $data['version'] : '?';
			$meta['license'] = isset($data['license']) ? $data['license'] : $meta['license'];
		}
		catch (\Exception $e)
		{
			// A broken composer.json must not break the page.
		}

		return $meta;
	}

	protected function assign_badges()
	{
		$meta       = $this->composer_meta();
		$db_version = (string) $this->config['newtopic_version'];
		$phpbb_ok   = phpbb_version_compare($this->config['version'], '3.3.0', '>=') && phpbb_version_compare($this->config['version'], '4.0.0-dev', '<');
		$php_ok     = version_compare(PHP_VERSION, '7.4', '>=');
		$db_ok      = $meta['version'] === '?' || $db_version === $meta['version'];

		$this->template->assign_vars(array(
			'NT_BADGE_VERSION'   => $meta['version'],
			'NT_BADGE_DB'        => $db_version,
			'S_NT_DB_OK'         => $db_ok,
			'NT_BADGE_PHPBB'     => $this->config['version'],
			'S_NT_PHPBB_OK'      => $phpbb_ok,
			'NT_BADGE_PHP'       => PHP_VERSION,
			'S_NT_PHP_OK'        => $php_ok,
			'NT_BADGE_LICENSE'   => $meta['license'],
		));
	}
}
