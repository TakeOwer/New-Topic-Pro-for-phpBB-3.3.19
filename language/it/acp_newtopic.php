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
	// Impostazioni
	'ACP_NEWTOPIC_SETTINGS_EXPLAIN' => 'Il pulsante «Nuovo argomento» compare in ogni pagina, nella posizione scelta qui sotto, e apre un elenco dei forum in cui l’utente può davvero scrivere. Categorie, collegamenti, forum chiusi e forum senza permesso non sono mai cliccabili.',
	'ACP_NEWTOPIC_LEGEND_GENERAL'   => 'Generale',
	'ACP_NEWTOPIC_LEGEND_LIST'      => 'Elenco dei forum',
	'ACP_NEWTOPIC_LEGEND_LOOK'      => 'Aspetto e smartphone',

	'ACP_NEWTOPIC_ENABLE'           => 'Mostra il pulsante',
	'ACP_NEWTOPIC_ENABLE_EXPLAIN'   => 'Se disattivato, il pulsante scompare da tutte le pagine senza disinstallare l’estensione.',
	'ACP_NEWTOPIC_GUESTS'           => 'Mostralo anche agli ospiti',
	'ACP_NEWTOPIC_GUESTS_EXPLAIN'   => 'Compare solo se gli ospiti hanno il permesso di aprire argomenti in almeno un forum.',
	'ACP_NEWTOPIC_HIDE_PARENTS'     => 'Forum che contengono sottoforum',
	'ACP_NEWTOPIC_HIDE_PARENTS_EXPLAIN' => 'Scegli «Non selezionabili» se nei forum padre non si deve scrivere: resteranno visibili come intestazione dei loro sottoforum.',
	'ACP_NEWTOPIC_PARENTS_ALLOW'    => 'Selezionabili',
	'ACP_NEWTOPIC_PARENTS_BLOCK'    => 'Non selezionabili',
	'ACP_NEWTOPIC_SHOW_DENIED'      => 'Mostra i forum non disponibili',
	'ACP_NEWTOPIC_SHOW_DENIED_EXPLAIN' => 'Se attivo, i forum in cui l’utente non può scrivere compaiono in grigio con il motivo. Se disattivo vengono nascosti e l’elenco resta più corto.',
	'ACP_NEWTOPIC_EXCLUDED'         => 'Forum esclusi',
	'ACP_NEWTOPIC_EXCLUDED_EXPLAIN' => 'Questi forum non si possono scegliere dal pulsante, anche se l’utente ha il permesso di scriverci. Tieni premuto Ctrl (Cmd su Mac) per selezionarne più di uno.',
	'ACP_NEWTOPIC_SEARCH'           => 'Campo di ricerca',
	'ACP_NEWTOPIC_SEARCH_EXPLAIN'   => 'Filtra l’elenco mentre si scrive, ignorando maiuscole e accenti.',
	'ACP_NEWTOPIC_CURRENT'          => 'Scorciatoia per il forum corrente',
	'ACP_NEWTOPIC_CURRENT_EXPLAIN'  => 'Quando l’utente è dentro un forum o un argomento, in cima all’elenco compare «Nuovo argomento qui».',
	'ACP_NEWTOPIC_RECENT'           => 'Forum usati di recente',
	'ACP_NEWTOPIC_RECENT_EXPLAIN'   => 'Quanti forum mostrare in cima all’elenco, tra quelli scelti di recente da quell’utente su quel dispositivo. 0 per disattivare.',
	'ACP_NEWTOPIC_MOBILE'           => 'Su smartphone',
	'ACP_NEWTOPIC_MOBILE_EXPLAIN'   => 'Sotto i 700 pixel di larghezza l’elenco si apre sempre come pannello dal basso. Con la posizione «Solo pulsante fluttuante» conta solo «Nascondi».',
	'ACP_NEWTOPIC_MOBILE_BAR'       => 'Pulsante compatto nella barra',
	'ACP_NEWTOPIC_MOBILE_FAB'       => 'Pulsante fluttuante in basso',
	'ACP_NEWTOPIC_MOBILE_OFF'       => 'Nascondi',
	'ACP_NEWTOPIC_FAB_SIDE'         => 'Lato del pulsante fluttuante',
	'ACP_NEWTOPIC_FAB_SIDE_EXPLAIN' => 'Scegli il lato libero, se dall’altro hai già la chat o il pulsante «torna su».',
	'ACP_NEWTOPIC_FAB_LEFT'         => 'Sinistra',
	'ACP_NEWTOPIC_FAB_RIGHT'        => 'Destra',
	'ACP_NEWTOPIC_ACCENT'           => 'Colore principale',
	'ACP_NEWTOPIC_ACCENT_EXPLAIN'   => 'Colore del pulsante e dell’evidenziazione nell’elenco.',
	'ACP_NEWTOPIC_ERR_ACCENT'       => 'Il colore deve essere nel formato esadecimale #rrggbb.',
	'ACP_NEWTOPIC_SAVED'            => 'Impostazioni salvate.',

	// Posizione / Position
	'ACP_NEWTOPIC_LEGEND_POSITION'  => 'Posizione',
	'ACP_NEWTOPIC_POSITION'         => 'Posizione del pulsante',
	'ACP_NEWTOPIC_POSITION_EXPLAIN' => 'Dove compare il pulsante «Nuovo argomento» in tutte le pagine del forum. Il pannello con l’elenco dei forum è lo stesso in tutte le posizioni.',
	'ACP_NEWTOPIC_POS_BREADCRUMBS'  => 'Barra dei percorsi (a destra)',
	'ACP_NEWTOPIC_POS_NAVBAR_LEFT'  => 'Barra di navigazione (accanto a FAQ)',
	'ACP_NEWTOPIC_POS_NAVBAR_RIGHT' => 'Barra di navigazione (accanto al profilo)',
	'ACP_NEWTOPIC_POS_QUICKLINKS'   => 'Menu «Collegamenti rapidi»',
	'ACP_NEWTOPIC_POS_PAGE_TOP'     => 'Sopra il contenuto della pagina',
	'ACP_NEWTOPIC_POS_FLOATING'     => 'Solo pulsante fluttuante',
	'ACP_NEWTOPIC_POS_UNSUPPORTED'  => '(non supportata dallo stile predefinito)',
	'ACP_NEWTOPIC_POSITION_WARN'    => 'Lo stile predefinito non ha il punto di aggancio per questa posizione: con questo stile il pulsante non comparirebbe. Scegli un’altra posizione oppure controlla gli stili nella scheda Check-up.',
	'ACP_NEWTOPIC_ERR_POSITION'     => 'La posizione scelta non è valida.',
	'ACP_NEWTOPIC_CHK_STYLE_NONE'   => 'nessuna',

	// Badge e crediti
	'ACP_NEWTOPIC_BADGE_VERSION'    => 'versione',
	'ACP_NEWTOPIC_BADGE_PHPBB'      => 'phpBB',
	'ACP_NEWTOPIC_BADGE_PHP'        => 'PHP',
	'ACP_NEWTOPIC_BADGE_LICENSE'    => 'licenza',
	'ACP_NEWTOPIC_BADGE_OK'         => 'Versione compatibile',
	'ACP_NEWTOPIC_BADGE_BAD'        => 'Versione non supportata',
	'ACP_NEWTOPIC_BADGE_DB_BAD'     => 'Il database è alla versione %2$s, i file alla %1$s',
	'ACP_NEWTOPIC_DB_MISMATCH'      => 'I file dell’estensione sono alla versione %1$s ma il database è rimasto alla %2$s. Vai in Personalizza › Gestione estensioni, disattiva New Topic Pro e riattivalo (senza eliminare i dati).',
	'ACP_NEWTOPIC_CREDITS'          => 'Sviluppata da <strong>Salvo Cortesiano</strong> | Supporto: <a href="mailto:info@netshadows.de">info@netshadows.de</a> | Basata su «New Topic» di dmzx',

	// Check-up
	'ACP_NEWTOPIC_CHECKUP_EXPLAIN'  => 'Verifica che l’estensione sia installata correttamente, che gli stili attivi abbiano i punti di aggancio e mostra l’elenco esattamente come lo vedrà un utente.',
	'ACP_NEWTOPIC_SIMULATE_USER'    => 'Simula l’elenco per l’utente',
	'ACP_NEWTOPIC_SIMULATE_USER_EXPLAIN' => 'Facoltativo. Scrivi un nome utente per vedere quali forum può scegliere con i suoi permessi. Se lo lasci vuoto vedi il tuo elenco.',
	'ACP_NEWTOPIC_RUN'              => 'Esegui il check-up',
	'ACP_NEWTOPIC_SUMMARY'          => 'Superati: %1$d, avvisi: %2$d, errori: %3$d',
	'ACP_NEWTOPIC_COL_STATUS'       => 'Esito',
	'ACP_NEWTOPIC_COL_CHECK'        => 'Controllo',
	'ACP_NEWTOPIC_COL_DETAIL'       => 'Dettaglio',
	'ACP_NEWTOPIC_STATUS_OK'        => 'OK',
	'ACP_NEWTOPIC_STATUS_WARN'      => 'Avviso',
	'ACP_NEWTOPIC_STATUS_ERROR'     => 'Errore',
	'ACP_NEWTOPIC_STATUS_INFO'      => 'Info',
	'ACP_NEWTOPIC_PREVIEW_ADMIN'    => 'Anteprima del tuo elenco',
	'ACP_NEWTOPIC_PREVIEW_USER'     => 'Anteprima dell’elenco di %s',
	'ACP_NEWTOPIC_PREVIEW_EXPLAIN'  => 'Le voci in grigio compaiono come intestazioni o forum non cliccabili; il motivo è indicato a destra.',
	'ACP_NEWTOPIC_PREVIEW_OK'       => 'Selezionabile',

	'ACP_NEWTOPIC_CHK_PHP'          => 'Versione di PHP',
	'ACP_NEWTOPIC_CHK_PHP_OK'       => 'PHP %s, supportato.',
	'ACP_NEWTOPIC_CHK_PHP_BAD'      => 'PHP %s: serve almeno PHP 7.4.',
	'ACP_NEWTOPIC_CHK_PHPBB'        => 'Versione di phpBB',
	'ACP_NEWTOPIC_CHK_PHPBB_OK'     => 'phpBB %s, supportato.',
	'ACP_NEWTOPIC_CHK_PHPBB_BAD'    => 'phpBB %s: l’estensione richiede phpBB 3.3.x.',
	'ACP_NEWTOPIC_CHK_VERSION'      => 'Versione installata',
	'ACP_NEWTOPIC_CHK_VERSION_OK'   => 'File e database allineati alla versione %s.',
	'ACP_NEWTOPIC_CHK_VERSION_BAD'  => 'I file sono alla %1$s ma il database alla %2$s: disattiva e riattiva l’estensione senza eliminare i dati.',
	'ACP_NEWTOPIC_CHK_COMPOSER_BAD' => 'Impossibile leggere composer.json: controlla che il file sia stato caricato integro.',
	'ACP_NEWTOPIC_CHK_ENABLED'      => 'Pulsante attivo',
	'ACP_NEWTOPIC_CHK_ENABLED_ON'   => 'Il pulsante viene mostrato.',
	'ACP_NEWTOPIC_CHK_ENABLED_OFF'  => 'Il pulsante è disattivato nella scheda Impostazioni, quindi non compare in nessuna pagina.',
	'ACP_NEWTOPIC_CHK_OLD'          => 'Vecchia estensione dmzx/newtopic',
	'ACP_NEWTOPIC_CHK_OLD_ON'       => 'È ancora attiva: sul forum compaiono due menu. Disattivala ed elimina i suoi dati da Gestione estensioni.',
	'ACP_NEWTOPIC_CHK_OLD_OFF'      => 'Non attiva, nessun doppione.',
	'ACP_NEWTOPIC_CHK_FILES'        => 'File dell’estensione',
	'ACP_NEWTOPIC_CHK_FILES_OK'     => 'Tutti i %d file principali sono presenti e leggibili.',
	'ACP_NEWTOPIC_CHK_FILES_BAD'    => 'File mancanti o non leggibili: %s. Ricarica l’estensione e svuota la cache.',
	'ACP_NEWTOPIC_CHK_LANG'         => 'Lingue',
	'ACP_NEWTOPIC_CHK_LANG_OK'      => 'Traduzioni presenti per: %s.',
	'ACP_NEWTOPIC_CHK_LANG_BAD'     => 'Manca la traduzione per: %s. Chi usa queste lingue vedrà i testi in inglese.',
	'ACP_NEWTOPIC_CHK_STYLE'        => 'Stile «%s»',
	'ACP_NEWTOPIC_CHK_STYLE_DEFAULT' => '(predefinito)',
	'ACP_NEWTOPIC_CHK_STYLE_OK'     => 'Con la posizione scelta («%1$s») il pulsante compare. Posizioni disponibili con questo stile: %2$s.',
	'ACP_NEWTOPIC_CHK_STYLE_BAD'    => 'Con la posizione scelta («%1$s») il pulsante non compare: mancano gli eventi template %2$s. Posizioni disponibili con questo stile: %3$s.',
	'ACP_NEWTOPIC_CHK_STRUCTURE'    => 'Struttura del forum',
	'ACP_NEWTOPIC_CHK_STRUCTURE_INFO' => 'Forum: %1$d, categorie: %2$d, collegamenti: %3$d.',
	'ACP_NEWTOPIC_CHK_EXCLUDED'     => 'Forum esclusi',
	'ACP_NEWTOPIC_CHK_EXCLUDED_OK'  => '%d forum esclusi, tutti esistenti.',
	'ACP_NEWTOPIC_CHK_EXCLUDED_BAD' => 'Questi forum esclusi non esistono più (ID %s): salva di nuovo le impostazioni per ripulire l’elenco.',
	'ACP_NEWTOPIC_CHK_ACCENT'       => 'Colore principale',
	'ACP_NEWTOPIC_CHK_ACCENT_OK'    => 'Colore %s valido.',
	'ACP_NEWTOPIC_CHK_ACCENT_BAD'   => 'Il colore «%s» non è valido: viene usato il blu predefinito.',
	'ACP_NEWTOPIC_CHK_SIM_ADMIN'    => 'Elenco per te',
	'ACP_NEWTOPIC_CHK_SIM_GUESTS'   => 'Elenco per gli ospiti',
	'ACP_NEWTOPIC_CHK_SIM_USER'     => 'Elenco per %s',
	'ACP_NEWTOPIC_CHK_GUESTS_OFF'   => 'Pulsante nascosto agli ospiti. Con i permessi attuali potrebbero scegliere %d forum.',
	'ACP_NEWTOPIC_CHK_USER_NOT_FOUND' => 'Nessun utente con questo nome.',
	'ACP_NEWTOPIC_CHK_SIM_NONE'     => 'Nessun forum selezionabile: per questo utente il pulsante non compare.',
	'ACP_NEWTOPIC_CHK_SIM_OK'       => array(
		1 => '%d forum selezionabile.',
		2 => '%d forum selezionabili.',
	),
	'ACP_NEWTOPIC_CHK_SIM_DISABLED' => 'Non cliccabili: %s.',
	'ACP_NEWTOPIC_REASON_COUNT_CATEGORY' => array(1 => '%d categoria', 2 => '%d categorie'),
	'ACP_NEWTOPIC_REASON_COUNT_LINK'     => array(1 => '%d collegamento', 2 => '%d collegamenti'),
	'ACP_NEWTOPIC_REASON_COUNT_PARENT'   => array(1 => '%d forum con sottoforum', 2 => '%d forum con sottoforum'),
	'ACP_NEWTOPIC_REASON_COUNT_LOCKED'   => array(1 => '%d forum chiuso', 2 => '%d forum chiusi'),
	'ACP_NEWTOPIC_REASON_COUNT_NOPERM'   => array(1 => '%d senza permesso', 2 => '%d senza permesso'),
	'ACP_NEWTOPIC_REASON_COUNT_EXCLUDED' => array(1 => '%d escluso', 2 => '%d esclusi'),
));
