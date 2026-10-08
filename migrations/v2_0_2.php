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

/**
 * 2.0.2: choose where the "New topic" button is shown.
 */
class v2_0_2 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['newtopic_position'])
			&& isset($this->config['newtopic_version'])
			&& phpbb_version_compare($this->config['newtopic_version'], '2.0.2', '>=');
	}

	public static function depends_on()
	{
		return array('\salvocortesiano\newtopic\migrations\v2_0_1');
	}

	public function update_data()
	{
		return array(
			// Existing boards keep the button where it was.
			array('config.add', array('newtopic_position', 'breadcrumbs')),
			array('config.update', array('newtopic_version', '2.0.2')),
		);
	}
}
