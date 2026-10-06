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

class main_info
{
	public function module()
	{
		return array(
			'filename' => '\salvocortesiano\newtopic\acp\main_module',
			'title'    => 'ACP_NEWTOPIC_TITLE',
			'modes'    => array(
				'settings' => array(
					'title' => 'ACP_NEWTOPIC_SETTINGS',
					'auth'  => 'ext_salvocortesiano/newtopic && acl_a_board',
					'cat'   => array('ACP_NEWTOPIC_TITLE'),
				),
				'checkup' => array(
					'title' => 'ACP_NEWTOPIC_CHECKUP',
					'auth'  => 'ext_salvocortesiano/newtopic && acl_a_board',
					'cat'   => array('ACP_NEWTOPIC_TITLE'),
				),
			),
		);
	}
}
