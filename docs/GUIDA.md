# New Topic Pro – Guida all'uso

**Estensione:** `salvocortesiano/newtopic` · **Versione:** 2.0.1 · **Requisiti:** phpBB 3.3.x, PHP 7.4 o superiore (testata con PHP 8.2/8.3) · **Licenza:** GPL-2.0-only

Sviluppata da Salvo Cortesiano – supporto: info@netshadows.de
Basata su «New Topic» di dmzx (`dmzx/newtopic` 1.0.3), riscritta da zero.

---

## 1. A cosa serve

Mette in ogni pagina del forum un pulsante **«Nuovo argomento»** nella barra dei percorsi (breadcrumb). Il pulsante apre un pannello con l'elenco dei forum: scegliendone uno si arriva direttamente alla pagina di scrittura del nuovo argomento in quel forum.

L'elenco mostra **solo i forum in cui l'utente può davvero aprire un argomento**. Tutto il resto o non compare o compare come intestazione non cliccabile.

## 2. Cosa cambia rispetto alla versione di dmzx

| Problema della 1.0.3 | Causa | Soluzione nella 2.0.0 |
|---|---|---|
| Le categorie erano selezionabili e portavano all'errore «Non puoi aprire argomenti in questo forum» | La funzione che costruiva l'elenco veniva chiamata con `ignore_nonpost = false` | Ogni forum viene controllato con le stesse regole di `posting.php`; quelli rifiutati non sono mai cliccabili |
| Da smartphone la combo non compariva | Classe `responsive-hide` nel template | Pulsante compatto o fluttuante e pannello dal basso sotto i 700 px |
| Aspetto da `<select>` nativa | — | Pannello personalizzato con ricerca, albero richiudibile e tastiera |
| Nessuna configurazione | — | Pagina ACP con impostazioni e Check-up |

## 3. Installazione

1. Se hai la vecchia estensione: ACP › Personalizza › Gestione estensioni › **New Topic (dmzx)** › Disattiva, poi **Elimina dati**, poi cancella la cartella `ext/dmzx/newtopic`. (Se resta attiva vedrai due menu: il Check-up te lo segnala come errore.)
2. Carica la cartella in modo che il percorso sia `ext/salvocortesiano/newtopic/composer.json`.
3. ACP › Personalizza › Gestione estensioni › **New Topic Pro** › Attiva.
4. Svuota la cache (ACP › Generale › Svuota la cache).
5. Apri ACP › Estensioni › New Topic Pro › **Check-up** ed esegui il controllo.

L'attivazione esegue le migrazioni `v2_0_0` e `v2_0_1`: la prima crea le impostazioni e il modulo ACP. La seconda aggiorna solo il numero di versione. Non viene creata nessuna tabella.

## 4. Il pulsante e il pannello (lato utente)

### Su computer
- Il pulsante blu **«+ Nuovo argomento»** sta a destra nella barra dei percorsi.
- Il pannello si apre sotto il pulsante (o sopra, se sotto non c'è spazio) e il cursore va subito nel campo di ricerca.
- **Tastiera:**
  - `↓` e `↑` scorrono i forum;
  - `Invio` apre il forum selezionato; dal campo di ricerca apre il primo risultato;
  - `Home`/`Fine` vanno al primo/ultimo forum;
  - `Esc` chiude il pannello e riporta il focus sul pulsante;
  - scrivendo una lettera mentre sei sull'elenco si passa direttamente alla ricerca.
- Il pannello si chiude cliccando fuori.

### Su smartphone (sotto i 700 px di larghezza)
- In base all'impostazione ACP compare un **pulsante compatto «+»** nella barra oppure un **pulsante fluttuante** in basso.
- L'elenco si apre come **pannello dal basso** a tutta larghezza, con lo sfondo oscurato. Si chiude con la ✕ o toccando lo sfondo.
- Le voci sono più alte, per toccarle facilmente; il campo di ricerca usa caratteri da 16 px, così iOS non ingrandisce la pagina.

### Contenuto del pannello
1. **Nuovo argomento qui:** se sei dentro un forum (o in un argomento di quel forum) e puoi scriverci, è la prima voce.
2. **Usati di recente:** gli ultimi forum scelti da quell'utente su quel dispositivo. Vengono salvati nel browser (`localStorage`), separati per utente.
3. **Tutti i forum:** l'albero completo.
   - Le **categorie** sono intestazioni in grassetto, non cliccabili.
   - La freccia accanto a una voce **chiude o apre i suoi sottoforum**. La scelta viene ricordata.
   - A destra di ogni forum c'è il numero di argomenti.
   - Il forum in cui ti trovi è evidenziato con una barra colorata.
4. **Ricerca:** filtra mentre scrivi e ignora maiuscole e accenti («citta» trova «Città»). Mostra i forum trovati insieme alla categoria a cui appartengono ed evidenzia il testo trovato. Se non trova nulla, lo dice.

Se un utente non può aprire argomenti in nessun forum, il pulsante non compare affatto. Ai bot non viene mai mostrato.

## 5. Quali forum sono cliccabili

Un forum è selezionabile solo se passa **tutti** questi controlli, nello stesso ordine usato da phpBB:

| Controllo | Se fallisce | Motivo mostrato |
|---|---|---|
| L'utente può vedere il forum (`f_list`) | Il forum e tutti i suoi sottoforum spariscono | — |
| È un collegamento | Non cliccabile | Collegamento esterno |
| È una categoria | Intestazione non cliccabile | Categoria: scegli uno dei forum al suo interno |
| È tra i forum esclusi in ACP | Non cliccabile | Non disponibile per i nuovi argomenti |
| Ha sottoforum e in ACP hai scelto «Non selezionabili» | Intestazione non cliccabile | Contiene sottoforum: scegli uno di quelli sotto |
| L'utente non ha il permesso `f_post` | Non cliccabile | Non hai il permesso di aprire argomenti qui |
| Il forum è chiuso e l'utente non ha `m_edit` | Non cliccabile | Forum chiuso |

I forum non cliccabili **vengono nascosti** di default. Con l'opzione «Mostra i forum non disponibili» compaiono in grigio con il motivo.
Categorie e forum padre restano comunque visibili quando contengono almeno un forum cliccabile, così l'albero non perde la struttura. Le categorie vuote spariscono.

> **Nota sui forum con sottoforum.** In phpBB un forum con sottoforum può ricevere argomenti, quindi di default resta selezionabile. Se imposti «Non selezionabili», un forum padre i cui sottoforum non sono visibili a quell'utente (per esempio *Richieste* con *Richieste Evase* chiuso) sparisce dal suo elenco.

## 6. Impostazioni ACP

ACP › Estensioni › New Topic Pro › **Impostazioni**

### Generale
- **Mostra il pulsante:** spegne o accende il pulsante su tutto il forum senza disinstallare.
- **Mostralo anche agli ospiti:** compare solo se gli ospiti hanno `f_post` in almeno un forum.

### Elenco dei forum
- **Forum che contengono sottoforum:** *Selezionabili* (predefinito) o *Non selezionabili*.
- **Mostra i forum non disponibili:** in grigio con il motivo oppure nascosti (predefinito).
- **Forum esclusi:** selezione multipla (Ctrl/Cmd + clic). Questi forum non si possono scegliere dal pulsante anche se l'utente può scriverci.
- **Campo di ricerca:** sì/no.
- **Scorciatoia per il forum corrente:** sì/no.
- **Forum usati di recente:** da 0 a 10 (0 disattiva la sezione). Predefinito: 5.

### Aspetto e smartphone
- **Su smartphone:** *Pulsante compatto nella barra* (predefinito), *Pulsante fluttuante in basso* oppure *Nascondi*.
- **Lato del pulsante fluttuante:** destra o sinistra. Scegli il lato libero se dall'altro c'è la chat o il «torna su».
- **Colore principale:** colore del pulsante e delle evidenziazioni (formato `#rrggbb`). Predefinito `#105289`.

Ogni salvataggio viene registrato nel registro amministratore.

In cima a ogni pagina ACP ci sono i badge:

| Badge | Colore | Significato |
|---|---|---|
| versione | blu | Versione letta da `composer.json` |
| versione | arancione | Il database è indietro: compare anche un riquadro rosso che chiede di disattivare e riattivare l'estensione |
| phpBB, PHP | verde | Versione supportata |
| phpBB, PHP | rosso | Versione non supportata |
| licenza | grigio | Licenza dell'estensione |

## 7. Check-up

ACP › Estensioni › New Topic Pro › **Check-up** › «Esegui il check-up».

| Controllo | Esito possibile |
|---|---|
| Versione di PHP | errore sotto PHP 7.4 |
| Versione di phpBB | errore fuori dalla 3.3.x |
| Versione installata | errore se file e database non coincidono (disattiva e riattiva senza eliminare i dati) |
| Pulsante attivo | avviso se è spento nelle impostazioni |
| Vecchia estensione dmzx/newtopic | errore se è ancora attiva (doppio menu) |
| File dell'estensione | errore se manca CSS, JS, uno dei template o la lingua inglese |
| Lingue | avviso per ogni lingua installata sul forum senza traduzione (quegli utenti vedono l'inglese) |
| Stile «…» | per **ogni stile attivo**, controlla, risalendo anche agli stili genitori, che esistano gli eventi template `overall_header_breadcrumbs_after` e `overall_header_head_append`; errore se ne manca uno |
| Struttura del forum | numero di forum, categorie e collegamenti |
| Forum esclusi | avviso se tra gli esclusi ci sono forum cancellati (risalva le impostazioni per ripulire) |
| Colore principale | avviso se non è un colore valido |
| Elenco per te | quanti forum puoi scegliere tu e quanti non sono cliccabili, per motivo |
| Elenco per gli ospiti | stessa cosa con i permessi dell'utente Anonymous |
| Elenco per «utente» | se compili il campo **Simula l'elenco per l'utente**, la stessa simulazione con i permessi di quell'utente |

Sotto la tabella compare l'**anteprima dell'elenco**: è esattamente l'albero che vedrà quell'utente (o tu, se il campo è vuoto), con «Selezionabile» oppure il motivo accanto a ogni voce. È il modo più rapido per capire perché un forum non compare a qualcuno.

## 8. Personalizzare l'aspetto

- Il colore si cambia da ACP. Tutte le tinte (sfondo al passaggio del mouse, evidenziazione della ricerca, bordo della scorciatoia) derivano da quel colore.
- Per altri ritocchi, il CSS è in `styles/all/theme/newtopic.css`. Tutte le classi iniziano con `nt-`, quindi non si scontrano con lo stile del forum.
- Le variabili CSS principali sono definite su `.nt-host, .nt-panel, .nt-fab`:

| Variabile | Uso |
|---|---|
| `--nt-ink` | Testo |
| `--nt-muted` | Testo secondario |
| `--nt-line` | Linee e bordi |
| `--nt-surface` | Sfondo del pannello |
| `--nt-step` | Rientro per livello dell'albero |

- I link del pannello hanno regole più forti di quelle dello stile (`a:link`, `a:visited`) e i nomi dei forum hanno un colore proprio, quindi restano leggibili qualunque colore lo stile dia ai link.
- Dopo ogni modifica a CSS o template, svuota la cache.

## 9. Struttura dei file

```
salvocortesiano/newtopic/
├── composer.json, ext.php, license.txt, README.md
├── acp/main_info.php, acp/main_module.php        modulo ACP (Impostazioni, Check-up)
├── adm/style/                                    template ACP, badge e crediti
├── config/services.yml
├── core/forum_list.php                           costruisce l'elenco e applica le regole di posting.php
├── core/checkup.php                              controlli della scheda Check-up
├── event/listener.php                            prepara il pulsante in ogni pagina (core.page_header)
├── language/it, language/en                      common.php, acp_newtopic.php, info_acp_newtopic.php
├── migrations/v2_0_0.php, v2_0_1.php
├── styles/all/template/event/                    overall_header_breadcrumbs_after.html, overall_header_head_append.html
├── styles/all/template/js/newtopic.js            pannello, ricerca, tastiera (nessuna dipendenza)
├── styles/all/theme/newtopic.css
└── docs/GUIDA.md                                 questa guida
```

## 10. Dettagli tecnici

- **Elenco dei forum:** una query su `FORUMS_TABLE` con cache di 10 minuti, che phpBB svuota da sola quando modifichi i forum in ACP. I permessi vengono applicati per ogni utente a ogni pagina, in memoria.
- **Forum corrente:** viene letto da `f`. Nelle pagine con solo `t` o `p` serve una query in più, limitata a una riga su chiave primaria.
- **Pannello:** al caricamento lo script lo sposta in `<body>`, così nessun contenitore dello stile può tagliarlo. Usa `position: fixed` e si riposiziona su scroll e ridimensionamento.
- **Accessibilità:**
  - il pannello è un `dialog` etichettato e il pulsante ha `aria-expanded`;
  - una regione `aria-live` annuncia quanti forum ha trovato la ricerca;
  - il focus resta dentro il pannello e il focus da tastiera è sempre visibile;
  - con «riduci movimento» attivo, le animazioni vengono disattivate.
- **Testi:** tutti nei file di lingua, compresi quelli usati dallo script, che li riceve tramite attributi `data-`.
- **Configurazione salvata:** chiavi `newtopic_*` nella tabella config; i forum esclusi in `config_text` (`newtopic_excluded`, JSON).

## 11. Aggiornamenti futuri

A ogni nuova versione vanno aggiornati `composer.json` e una nuova migrazione che porta `newtopic_version` allo stesso numero. Se i due valori non coincidono, i badge diventano arancioni e il Check-up segnala l'errore.

Per aggiornare: carica i file sopra i precedenti, poi disattiva e riattiva l'estensione **senza eliminare i dati** e svuota la cache.

## 12. Cronologia delle versioni

| Versione | Modifiche |
|---|---|
| 2.0.1 | Corretti i nomi dei forum invisibili finché non ci si passava sopra col mouse: lo stile del forum colorava i link con `a:link`/`a:visited`. Indicatore del forum corrente ridisegnato come barra dritta. |
| 2.0.0 | Riscrittura completa dell'estensione di dmzx: pannello responsive, regole di posting.php, ACP con Impostazioni e Check-up. |

## 13. Risoluzione dei problemi

| Sintomo | Cosa fare |
|---|---|
| Il pulsante non compare | Esegui il Check-up. Le cause tipiche sono: pulsante spento, utente senza forum in cui scrivere, stile senza l'evento `overall_header_breadcrumbs_after`, cache non svuotata. |
| Compare due volte | È ancora attiva `dmzx/newtopic`. Disattivala ed elimina i suoi dati. |
| I nomi dei forum non si vedono | Svuota la cache e ricarica la pagina con Ctrl+F5: dalla 2.0.1 il problema è corretto, ma il browser potrebbe usare ancora il CSS vecchio. |
| Il pulsante non ha grafica | Allo stile manca l'evento `overall_header_head_append`, oppure la cache non è stata svuotata. |
| Un forum non compare per un utente | Check-up › Simula l'elenco per l'utente: l'anteprima mostra il motivo. |
| Su smartphone si sovrappone alla chat | Imposta il pulsante fluttuante sull'altro lato, oppure usa il pulsante compatto nella barra. |
