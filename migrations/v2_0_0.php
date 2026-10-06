<?php
/**
 *
 * New Topic Pro. An extension for the phpBB Forum Software package.
 *
 * @copyright (c) 2026 Salvo Cortesiano <https://netshadows.de>
 * @license GNU General Public License, version 2 (GPL-2.0-only)
 *
 */

namespace salvocortesiano\newtopic\migrations;

class v2_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['newtopic_version'])
			&& phpbb_version_compare($this->config['newtopic_version'], '2.0.0', '>=');
	}

	public static function depends_on()
	{
		return array('\phpbb\db\migration\data\v330\v330');
	}

	public function update_data()
	{
		return array(
			array('config.add', array('newtopic_enable', 1)),
			array('config.add', array('newtopic_guests', 0)),
			array('config.add', array('newtopic_hide_parents', 0)),
			array('config.add', array('newtopic_show_denied', 0)),
			array('config.add', array('newtopic_search', 1)),
			array('config.add', array('newtopic_current', 1)),
			array('config.add', array('newtopic_recent', 5)),
			array('config.add', array('newtopic_mobile_mode', 'bar')),
			array('config.add', array('newtopic_fab_side', 'right')),
			array('config.add', array('newtopic_accent', '#105289')),
			array('config.add', array('newtopic_version', '2.0.0')),
			array('config_text.add', array('newtopic_excluded', '[]')),

			array('module.add', array('acp', 'ACP_CAT_DOT_MODS', 'ACP_NEWTOPIC_TITLE')),
			array('module.add', array('acp', 'ACP_NEWTOPIC_TITLE', array(
				'module_basename' => '\salvocortesiano\newtopic\acp\main_module',
				'modes'           => array('settings', 'checkup'),
			))),
		);
	}
}
