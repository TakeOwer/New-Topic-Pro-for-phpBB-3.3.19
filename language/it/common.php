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
	'NEWTOPIC_BUTTON'         => 'Nuovo argomento',
	'NEWTOPIC_BUTTON_TITLE'   => 'Apri un nuovo argomento in un forum a scelta',
	'NEWTOPIC_PANEL_TITLE'    => 'Dove vuoi aprire l’argomento?',
	'NEWTOPIC_CLOSE'          => 'Chiudi',
	'NEWTOPIC_SEARCH'         => 'Cerca un forum…',
	'NEWTOPIC_SEARCH_LABEL'   => 'Cerca un forum',
	'NEWTOPIC_HERE'           => 'Nuovo argomento qui',
	'NEWTOPIC_RECENT'         => 'Usati di recente',
	'NEWTOPIC_ALL'            => 'Tutti i forum',
	'NEWTOPIC_NO_RESULTS'     => 'Nessun forum contiene «%s». Prova con una parola diversa.',
	'NEWTOPIC_RESULTS'        => array(
		0 => 'Nessun forum trovato',
		1 => '%d forum trovato',
		2 => '%d forum trovati',
	),
	'NEWTOPIC_TOGGLE'         => 'Mostra o nascondi i sottoforum',
	'NEWTOPIC_KBD_HINT'       => '↑ ↓ per scegliere, Invio per aprire, Esc per chiudere',
	'NEWTOPIC_TOPICS'         => array(
		0 => 'nessun argomento',
		1 => '%d argomento',
		2 => '%d argomenti',
	),

	'NEWTOPIC_REASON_CATEGORY' => 'Categoria: scegli uno dei forum al suo interno',
	'NEWTOPIC_REASON_LINK'     => 'Collegamento esterno',
	'NEWTOPIC_REASON_PARENT'   => 'Contiene sottoforum: scegli uno di quelli sotto',
	'NEWTOPIC_REASON_LOCKED'   => 'Forum chiuso',
	'NEWTOPIC_REASON_NOPERM'   => 'Non hai il permesso di aprire argomenti qui',
	'NEWTOPIC_REASON_EXCLUDED' => 'Non disponibile per i nuovi argomenti',
));
