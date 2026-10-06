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

class v2_0_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['newtopic_version'])
			&& phpbb_version_compare($this->config['newtopic_version'], '2.0.1', '>=');
	}

	public static function depends_on()
	{
		return array('\salvocortesiano\newtopic\migrations\v2_0_0');
	}

	public function update_data()
	{
		return array(
			array('config.update', array('newtopic_version', '2.0.1')),
		);
	}
}
