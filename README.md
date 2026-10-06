# New Topic Pro for phpBB

![Version](https://img.shields.io/badge/version-2.0.1-1f6fb5)
![phpBB](https://img.shields.io/badge/phpBB-3.3.x-3a9b3a)
![PHP](https://img.shields.io/badge/PHP-%E2%89%A5%207.4-3a9b3a)
![License](https://img.shields.io/badge/license-GPL--2.0--only-8a8f94)

**Extension:** `salvocortesiano/newtopic` · **Version:** 2.0.1 · **Requirements:** phpBB 3.3.x, PHP 7.4 or newer (tested with PHP 8.2/8.3) · **License:** GPL-2.0-only

Developed by Salvo Cortesiano – support: info@netshadows.de
Based on “New Topic” by dmzx (`dmzx/newtopic` 1.0.3), rewritten from scratch.

---
<img width="477" height="663" alt="Screenshot 2026-10-06 113311" src="https://github.com/user-attachments/assets/6b328c97-0244-4bdb-bbc9-0d1009bb078d" />
---
<img width="470" height="681" alt="Screenshot 2026-10-06 113322" src="https://github.com/user-attachments/assets/efa13ac4-32ab-4718-af32-10b173035496" />
---
<img width="2279" height="957" alt="Screenshot 2026-10-05 124432" src="https://github.com/user-attachments/assets/eb635f61-a77c-483e-b1dc-9d3185c08aa7" />
---
<img width="2287" height="821" alt="Screenshot 2026-10-05 124445" src="https://github.com/user-attachments/assets/36d7771a-cfdb-471a-a404-8830de75e5a2" />
---
<img width="1906" height="1251" alt="Screenshot 2026-10-05 124453" src="https://github.com/user-attachments/assets/72727ce8-ca93-4446-97a1-94ddc1699011" />
---

## Table of contents

1. [What it does](#1-what-it-does)
2. [What changed compared to dmzx's version](#2-what-changed-compared-to-dmzxs-version)
3. [Installation](#3-installation)
4. [The button and the panel (user side)](#4-the-button-and-the-panel-user-side)
5. [Which forums are clickable](#5-which-forums-are-clickable)
6. [ACP settings](#6-acp-settings)
7. [Check-up](#7-check-up)
8. [Customising the look](#8-customising-the-look)
9. [File structure](#9-file-structure)
10. [Technical details](#10-technical-details)
11. [Updating and uninstalling](#11-updating-and-uninstalling)
12. [Changelog](#12-changelog)
13. [Troubleshooting](#13-troubleshooting)
14. [License](#14-license)

## 1. What it does

It adds a **“New topic”** button to the breadcrumb bar of every page of the board. The button opens a panel listing the forums: picking one takes the user straight to the posting page for a new topic in that forum.

The list shows **only the forums where the user can actually start a topic**. Everything else is either hidden or shown as a non-clickable header.

## 2. What changed compared to dmzx's version

| Issue in 1.0.3 | Cause | Solution in 2.0.0 |
|---|---|---|
| Categories could be selected and led to the error “You are not authorised to post in this forum” | The function building the list was called with `ignore_nonpost = false` | Every forum is checked with the same rules as `posting.php`; forums that would be refused are never clickable |
| The menu did not appear on phones | `responsive-hide` class in the template | Compact or floating button and a bottom sheet below 700 px |
| Plain native `<select>` look | — | Custom panel with search, collapsible tree and keyboard support |
| No configuration | — | ACP page with Settings and Check-up |

## 3. Installation

1. If you have the old extension: ACP › Customise › Manage extensions › **New Topic (dmzx)** › Disable, then **Delete data**, then remove the `ext/dmzx/newtopic` folder. (If it stays enabled you will see two menus: the Check-up reports this as an error.)
2. Upload the folder so that the path is `ext/salvocortesiano/newtopic/composer.json`.
3. ACP › Customise › Manage extensions › **New Topic Pro** › Enable.
4. Purge the cache (ACP › General › Purge the cache).
5. Open ACP › Extensions › New Topic Pro › **Check-up** and run it.

Enabling the extension runs the `v2_0_0` and `v2_0_1` migrations. The first one creates the settings and the ACP module; the second one only updates the version number. No database tables are created.

## 4. The button and the panel (user side)

### On desktop
- The **“+ New topic”** button sits on the right of the breadcrumb bar.
- The panel opens below the button (or above it, if there is no room below) and the cursor goes straight into the search field.
- **Keyboard:**
  - `↓` and `↑` move through the forums;
  - `Enter` opens the selected forum; from the search field it opens the first result;
  - `Home`/`End` jump to the first/last forum;
  - `Esc` closes the panel and returns focus to the button;
  - typing a letter while on the list jumps straight into the search field.
- Clicking outside the panel closes it.

### On phones (below 700 px wide)
- Depending on the ACP setting, a **compact “+” button** appears in the bar, or a **floating button** at the bottom of the screen.
- The list opens as a full-width **bottom sheet** over a dimmed background. It closes with the ✕ or by tapping the background.
- Entries are taller so they are easy to tap; the search field uses a 16 px font so iOS does not zoom the page.

### Panel contents
1. **New topic here:** if you are inside a forum (or a topic of that forum) and may post there, this is the first entry.
2. **Recently used:** the last forums that user picked on that device. They are stored in the browser (`localStorage`), separately for each user.
3. **All forums:** the full tree.
   - **Categories** are bold headers and cannot be clicked.
   - The arrow next to an entry **collapses or expands its subforums**. The choice is remembered.
   - The number of topics is shown on the right of each forum.
   - The forum you are in is marked with a coloured bar.
4. **Search:** filters as you type, ignoring case and accents (“cafe” finds “Café”). It shows the matching forums together with the category they belong to and highlights the matching text. If nothing matches, it says so.

If a user cannot start topics in any forum, the button does not appear at all. It is never shown to bots.

## 5. Which forums are clickable

A forum can be selected only if it passes **all** of these checks, in the same order phpBB uses:

| Check | If it fails | Reason shown |
|---|---|---|
| The user can see the forum (`f_list`) | The forum and all its subforums disappear | — |
| It is a link | Not clickable | External link |
| It is a category | Non-clickable header | Category: pick one of the forums inside it |
| It is among the forums excluded in the ACP | Not clickable | Not available for new topics |
| It has subforums and the ACP is set to “Not selectable” | Non-clickable header | Has subforums: pick one of those below |
| The user lacks the `f_post` permission | Not clickable | You are not allowed to start topics here |
| The forum is locked and the user lacks `m_edit` | Not clickable | Forum locked |

Non-clickable forums **are hidden** by default. With the “Show unavailable forums” option they appear in grey with the reason.
Categories and parent forums always stay visible when they contain at least one clickable forum, so the tree keeps its structure. Empty categories disappear.

> **Note on forums with subforums.** In phpBB a forum with subforums can receive topics, so by default it stays selectable. If you choose “Not selectable”, a parent forum whose subforums are not visible to a given user (for example *Requests* with a locked *Completed requests* subforum) disappears from that user's list.

## 6. ACP settings

ACP › Extensions › New Topic Pro › **Settings**

### General
- **Show the button:** turns the button on or off across the whole board without uninstalling.
- **Show it to guests too:** it only appears if guests have `f_post` in at least one forum.

### Forum list
- **Forums that contain subforums:** *Selectable* (default) or *Not selectable*.
- **Show unavailable forums:** in grey with the reason, or hidden (default).
- **Excluded forums:** multiple selection (Ctrl/Cmd + click). These forums cannot be picked from the button even if the user may post there.
- **Search field:** yes/no.
- **Shortcut for the current forum:** yes/no.
- **Recently used forums:** from 0 to 10 (0 turns the section off). Default: 5.

### Appearance and phones
- **On phones:** *Compact button in the bar* (default), *Floating button at the bottom* or *Hide*.
- **Floating button side:** right or left. Pick the free side if the other already has a chat or a “back to top” button.
- **Accent colour:** colour of the button and of the highlights (`#rrggbb` format). Default `#105289`.

Every save is recorded in the admin log.

Each ACP page shows these badges at the top:

| Badge | Colour | Meaning |
|---|---|---|
| version | blue | Version read from `composer.json` |
| version | orange | The database is behind: a red box also appears asking you to disable and re-enable the extension |
| phpBB, PHP | green | Supported version |
| phpBB, PHP | red | Unsupported version |
| license | grey | Extension license |

## 7. Check-up

ACP › Extensions › New Topic Pro › **Check-up** › “Run the check-up”.

| Check | Possible result |
|---|---|
| PHP version | error below PHP 7.4 |
| phpBB version | error outside 3.3.x |
| Installed version | error if files and database do not match (disable and re-enable without deleting data) |
| Button enabled | warning if it is turned off in the settings |
| Old dmzx/newtopic extension | error if it is still enabled (duplicate menu) |
| Extension files | error if the CSS, the JS, one of the templates or the English language pack is missing |
| Languages | warning for each language installed on the board without a translation (those users see English) |
| Style “…” | for **every active style**, also walking up to parent styles, checks that the `overall_header_breadcrumbs_after` and `overall_header_head_append` template events exist; error if one is missing |
| Board structure | number of forums, categories and links |
| Excluded forums | warning if deleted forums are still in the excluded list (save the settings again to clean it) |
| Accent colour | warning if it is not a valid colour |
| List for you | how many forums you can pick and how many are not clickable, by reason |
| List for guests | the same with the permissions of the Anonymous user |
| List for “user” | if you fill in **Simulate the list for user**, the same simulation with that user's permissions |

Below the table you get a **preview of the list**: exactly the tree that user (or you, if the field is empty) will see, with “Selectable” or the reason next to each entry. It is the quickest way to understand why a forum does not appear for someone.

## 8. Customising the look

- The colour is set in the ACP. All tints (hover background, search highlight, shortcut border) are derived from it.
- For further tweaks, the CSS is in `styles/all/theme/newtopic.css`. All classes start with `nt-`, so they do not clash with the board style.
- The main CSS variables are defined on `.nt-host, .nt-panel, .nt-fab`:

| Variable | Used for |
|---|---|
| `--nt-ink` | Text |
| `--nt-muted` | Secondary text |
| `--nt-line` | Lines and borders |
| `--nt-surface` | Panel background |
| `--nt-step` | Indentation per tree level |

- Panel links use rules stronger than the style's own (`a:link`, `a:visited`) and forum names have their own colour, so they stay readable whatever colour the style gives to links.
- Purge the cache after every change to CSS or templates.

## 9. File structure

```
salvocortesiano/newtopic/
├── composer.json, ext.php, license.txt, README.md
├── acp/main_info.php, acp/main_module.php        ACP module (Settings, Check-up)
├── adm/style/                                    ACP templates, badges and credits
├── config/services.yml
├── core/forum_list.php                           builds the list and applies the posting.php rules
├── core/checkup.php                              checks run by the Check-up tab
├── event/listener.php                            prepares the button on every page (core.page_header)
├── language/it, language/en                      common.php, acp_newtopic.php, info_acp_newtopic.php
├── migrations/v2_0_0.php, v2_0_1.php
├── styles/all/template/event/                    overall_header_breadcrumbs_after.html, overall_header_head_append.html
├── styles/all/template/js/newtopic.js            panel, search, keyboard (no dependencies)
├── styles/all/theme/newtopic.css
└── docs/GUIDA.md                                 user guide (Italian)
```

## 10. Technical details

- **Forum list:** one query on `FORUMS_TABLE`, cached for 10 minutes; phpBB clears that cache by itself whenever forums are edited in the ACP. Permissions are applied per user on every page, in memory.
- **Current forum:** read from `f`. On pages with only `t` or `p`, one extra query is needed, limited to one row on a primary key.
- **Panel:** on load the script moves it into `<body>`, so no container of the style can clip it. It uses `position: fixed` and repositions itself on scroll and resize.
- **Accessibility:**
  - the panel is a labelled `dialog` and the button has `aria-expanded`;
  - an `aria-live` region announces how many forums the search found;
  - focus stays inside the panel and keyboard focus is always visible;
  - when “reduce motion” is on, animations are disabled.
- **Text:** everything lives in the language files (Italian and English included), including the strings used by the script, which receives them through `data-` attributes.
- **Stored configuration:** `newtopic_*` keys in the config table; excluded forums in `config_text` (`newtopic_excluded`, JSON).

## 11. Updating and uninstalling

**Updating:** upload the new files over the old ones, then disable and re-enable the extension **without deleting data**, and purge the cache.

Every new version must update `composer.json` and add a new migration that sets `newtopic_version` to the same number. If the two values differ, the badges turn orange and the Check-up reports an error.

**Uninstalling:** ACP › Customise › Manage extensions › New Topic Pro › Disable, then **Delete data** to remove its settings and ACP module, then delete the `ext/salvocortesiano/newtopic` folder.

## 12. Changelog

| Version | Changes |
|---|---|
| 2.0.1 | Fixed forum names being invisible until hovered: the board style coloured links with `a:link`/`a:visited`. Current-forum marker redrawn as a straight bar. |
| 2.0.0 | Complete rewrite of dmzx's extension: responsive panel, posting.php rules, ACP with Settings and Check-up. |

## 13. Troubleshooting

| Symptom | What to do |
|---|---|
| The button does not appear | Run the Check-up. Typical causes: button turned off, user with no forum to post in, style without the `overall_header_breadcrumbs_after` event, cache not purged. |
| It appears twice | `dmzx/newtopic` is still enabled. Disable it and delete its data. |
| Forum names are not visible | Purge the cache and reload the page with Ctrl+F5: this was fixed in 2.0.1, but the browser may still be using the old CSS. |
| The button has no styling | The style lacks the `overall_header_head_append` event, or the cache was not purged. |
| A forum does not appear for a user | Check-up › Simulate the list for user: the preview shows the reason. |
| On phones it overlaps the chat | Move the floating button to the other side, or use the compact button in the bar. |

## 14. License

[GNU General Public License v2](https://opensource.org/licenses/GPL-2.0) (GPL-2.0-only).
