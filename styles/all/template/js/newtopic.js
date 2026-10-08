/**
 * New Topic Pro - salvocortesiano/newtopic
 * (c) 2026 Salvo Cortesiano - GPL-2.0-only
 *
 * No dependencies. All visible text comes from the language files through
 * data-* attributes rendered by the template.
 */
(function () {
	'use strict';

	var MOBILE_MAX = 700;

	function ready(fn) {
		if (document.readyState !== 'loading') {
			fn();
		} else {
			document.addEventListener('DOMContentLoaded', fn);
		}
	}

	function norm(text) {
		var s = String(text);
		if (s.normalize) {
			s = s.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
		}
		return s.toLowerCase();
	}

	function load(key) {
		try {
			var raw = window.localStorage.getItem(key);
			return raw ? JSON.parse(raw) : null;
		} catch (e) {
			return null;
		}
	}

	function save(key, value) {
		try {
			window.localStorage.setItem(key, JSON.stringify(value));
		} catch (e) {
			// Private mode or storage full: the picker still works.
		}
	}

	function toArray(list) {
		return Array.prototype.slice.call(list);
	}

	ready(function () {
		var host = document.querySelector('[data-nt-host]');
		if (!host) {
			return;
		}

		var panel = host.querySelector('[data-nt-panel]');
		var fab = host.querySelector('[data-nt-fab]');
		// "Floating button only" has no button in the page: the floating one is the trigger.
		var trigger = host.querySelector('[data-nt-trigger]') || fab;
		if (!trigger || !panel) {
			return;
		}
		var hasBarTrigger = trigger !== fab;

		// Live in <body> so no navbar overflow can clip the panel.
		var backdrop = document.createElement('div');
		backdrop.className = 'nt-backdrop';
		backdrop.hidden = true;
		document.body.appendChild(backdrop);
		document.body.appendChild(panel);
		if (fab) {
			document.body.appendChild(fab);
		}

		var search = panel.querySelector('[data-nt-search]');
		var list = panel.querySelector('[data-nt-list]');
		var scroller = panel.querySelector('[data-nt-scroll]');
		var empty = panel.querySelector('[data-nt-empty]');
		var live = panel.querySelector('[data-nt-live]');
		var recentBox = panel.querySelector('[data-nt-recent]');
		var recentList = recentBox ? recentBox.querySelector('ul') : null;
		var allTitle = panel.querySelector('[data-nt-all-title]');

		var userKey = host.getAttribute('data-user') || '0';
		var maxRecent = parseInt(host.getAttribute('data-recent') || '0', 10) || 0;
		var keyCollapsed = 'nt:collapsed';
		var keyRecent = 'nt:recent:' + userKey;

		var rows = toArray(list.querySelectorAll('.nt-row'));
		var byId = {};

		rows.forEach(function (row) {
			var nameEl = row.querySelector('.nt-name');
			var anc = row.getAttribute('data-anc');
			row._id = row.getAttribute('data-id');
			row._anc = anc ? anc.split(' ') : [];
			row._nameEl = nameEl;
			row._name = nameEl.textContent;
			row._norm = norm(row._name);
			row._link = row.querySelector('.nt-link');
			row._toggle = row.querySelector('[data-nt-toggle]');
			byId[row._id] = row;
		});

		var collapsed = {};
		(load(keyCollapsed) || []).forEach(function (id) {
			if (byId[id] && byId[id]._toggle) {
				collapsed[id] = true;
			}
		});

		var opener = null;
		var isOpen = false;
		var query = '';

		function isMobile() {
			return window.innerWidth < MOBILE_MAX;
		}

		function setExpanded(value) {
			trigger.setAttribute('aria-expanded', value ? 'true' : 'false');
			if (fab) {
				fab.setAttribute('aria-expanded', value ? 'true' : 'false');
			}
		}

		/* ---------- name highlighting ---------- */

		function paintName(row, needle) {
			var el = row._nameEl;
			var name = row._name;
			var at = needle ? row._norm.indexOf(needle) : -1;

			// Only highlight when normalising kept the same length.
			if (at < 0 || row._norm.length !== name.length) {
				if (el.childNodes.length !== 1 || el.firstChild.nodeType !== 3) {
					el.textContent = name;
				}
				return;
			}

			el.textContent = '';
			el.appendChild(document.createTextNode(name.slice(0, at)));
			var mark = document.createElement('mark');
			mark.textContent = name.slice(at, at + needle.length);
			el.appendChild(mark);
			el.appendChild(document.createTextNode(name.slice(at + needle.length)));
		}

		/* ---------- tree / filter ---------- */

		function applyTree() {
			rows.forEach(function (row) {
				var hidden = row._anc.some(function (id) {
					return collapsed[id];
				});
				row.hidden = hidden;
				if (row._toggle) {
					row._toggle.setAttribute('aria-expanded', collapsed[row._id] ? 'false' : 'true');
				}
				paintName(row, '');
			});
		}

		function pluralResults(count) {
			var text = panel.getAttribute(count === 0 ? 'data-l-none' : (count === 1 ? 'data-l-one' : 'data-l-many')) || '';
			return text.replace('%d', String(count));
		}

		function filter(value) {
			query = value;
			var needle = norm(value.trim());

			if (!needle) {
				panel.classList.remove('is-searching');
				applyTree();
				empty.hidden = true;
				live.textContent = '';
				return;
			}

			panel.classList.add('is-searching');

			var show = {};
			var count = 0;
			rows.forEach(function (row) {
				if (row._link && row._norm.indexOf(needle) > -1) {
					count++;
					show[row._id] = 'match';
					row._anc.forEach(function (id) {
						if (!show[id]) {
							show[id] = 'context';
						}
					});
				}
			});

			rows.forEach(function (row) {
				row.hidden = !show[row._id];
				paintName(row, show[row._id] === 'match' ? needle : '');
			});

			if (count) {
				empty.hidden = true;
			} else {
				empty.textContent = (panel.getAttribute('data-l-empty') || '').replace('%s', value.trim());
				empty.hidden = false;
			}

			live.textContent = pluralResults(count);
			scroller.scrollTop = 0;
		}

		/* ---------- recent forums ---------- */

		function renderRecent() {
			if (!recentBox || !maxRecent) {
				return;
			}

			var ids = (load(keyRecent) || []).filter(function (id) {
				return byId[id] && byId[id]._link;
			}).slice(0, maxRecent);

			recentList.textContent = '';
			ids.forEach(function (id) {
				var row = byId[id];
				var link = row._link.cloneNode(true);
				link.removeAttribute('aria-current');
				link.querySelector('.nt-name').textContent = row._name;
				var li = document.createElement('li');
				li.className = 'nt-flat-row';
				li.appendChild(link);
				recentList.appendChild(li);
			});

			recentBox.hidden = !ids.length;
			if (allTitle) {
				allTitle.hidden = !ids.length;
			}
		}

		function remember(id) {
			if (!maxRecent || !id) {
				return;
			}
			var ids = (load(keyRecent) || []).filter(function (x) {
				return String(x) !== String(id);
			});
			ids.unshift(String(id));
			save(keyRecent, ids.slice(0, 10));
		}

		/* ---------- placement ---------- */

		// offsetParent is null for position:fixed elements (the floating
		// button), so look at the rendered box instead.
		function visible(el) {
			if (!el || !el.getClientRects().length) {
				return false;
			}
			var r = el.getBoundingClientRect();
			return r.width > 0 && r.height > 0 && window.getComputedStyle(el).visibility !== 'hidden';
		}

		function inDropdown(el) {
			return !!(el && el.closest && el.closest('.dropdown-contents'));
		}

		// Close phpBB's own dropdowns (Quick links, profile...) when the panel opens.
		function closePhpbbDropdowns() {
			try {
				var $ = window.jQuery;
				var phpbb = window.phpbb;
				if ($ && phpbb && phpbb.dropdownHandles && phpbb.toggleDropdown) {
					$(phpbb.dropdownHandles).each(phpbb.toggleDropdown);
				}
			} catch (e) {
				// Not a phpBB page or a modified core: nothing to close.
			}
		}

		function place() {
			if (isMobile()) {
				panel.classList.add('nt-sheet');
				panel.style.top = panel.style.bottom = panel.style.left = panel.style.width = panel.style.maxHeight = '';
				backdrop.hidden = false;
				document.documentElement.classList.add('nt-lock');
				return;
			}

			panel.classList.remove('nt-sheet');

			var vw = document.documentElement.clientWidth;
			var vh = window.innerHeight;
			var width = Math.min(440, vw - 16);
			var anchor = visible(opener) ? opener : (visible(trigger) ? trigger : null);

			// Opened from a menu that has closed (Quick links): show it as a centred dialog.
			if (!anchor || inDropdown(opener)) {
				panel.classList.add('nt-centered');
				backdrop.hidden = false;
				document.documentElement.classList.add('nt-lock');
				panel.style.bottom = '';
				panel.style.width = width + 'px';
				panel.style.left = Math.round((vw - width) / 2) + 'px';
				panel.style.top = Math.round(Math.max(16, vh * 0.1)) + 'px';
				panel.style.maxHeight = Math.max(260, Math.min(620, vh - Math.max(16, vh * 0.1) - 24)) + 'px';
				return;
			}

			panel.classList.remove('nt-centered');
			backdrop.hidden = true;
			document.documentElement.classList.remove('nt-lock');

			var rect = anchor.getBoundingClientRect();
			// Buttons on the left half open the panel to the right, and vice versa.
			var fromLeft = (rect.left + rect.width / 2) < vw / 2;
			var left = fromLeft ? rect.left : rect.right - width;
			left = Math.min(Math.max(8, left), vw - width - 8);
			var below = vh - rect.bottom - 16;
			var above = rect.top - 16;
			var room;

			if (below < 280 && above > below) {
				panel.style.top = '';
				panel.style.bottom = (vh - rect.top + 8) + 'px';
				room = above;
			} else {
				panel.style.bottom = '';
				panel.style.top = (rect.bottom + 8) + 'px';
				room = below;
			}

			panel.style.left = left + 'px';
			panel.style.width = width + 'px';
			panel.style.maxHeight = Math.max(220, Math.min(600, room)) + 'px';
		}

		/* ---------- open / close ---------- */

		function open(from) {
			if (isOpen) {
				return;
			}
			isOpen = true;
			opener = from || trigger;

			if (inDropdown(opener)) {
				closePhpbbDropdowns();
			}

			renderRecent();
			applyTree();
			panel.hidden = false;
			place();
			setExpanded(true);

			// Next frame, so the opening transition runs.
			window.requestAnimationFrame(function () {
				panel.classList.add('is-open');
			});

			if (!isMobile() && search) {
				search.focus();
			} else {
				panel.focus({ preventScroll: true });
			}

			var current = list.querySelector('.nt-row--current');
			if (current && !current.hidden) {
				current.scrollIntoView({ block: 'nearest' });
			}
		}

		function close(returnFocus) {
			if (!isOpen) {
				return;
			}
			isOpen = false;

			panel.classList.remove('is-open', 'nt-centered');
			panel.hidden = true;
			backdrop.hidden = true;
			document.documentElement.classList.remove('nt-lock');
			setExpanded(false);

			if (search && search.value) {
				search.value = '';
				filter('');
			}

			if (returnFocus && opener) {
				opener.focus();
			}
		}

		function toggleFrom(el) {
			if (isOpen) {
				close(false);
			} else {
				open(el);
			}
		}

		/* ---------- keyboard ---------- */

		function focusables() {
			return toArray(panel.querySelectorAll('a.nt-link, a.nt-here')).filter(function (a) {
				return a.offsetParent !== null;
			});
		}

		panel.addEventListener('keydown', function (e) {
			var key = e.key;

			if (key === 'Escape') {
				e.preventDefault();
				close(true);
				return;
			}

			var items = focusables();
			var active = document.activeElement;
			var index = items.indexOf(active);
			var inSearch = active === search;

			if (key === 'ArrowDown') {
				e.preventDefault();
				if (items.length) {
					items[index < 0 ? 0 : Math.min(index + 1, items.length - 1)].focus();
				}
				return;
			}

			if (key === 'ArrowUp') {
				e.preventDefault();
				if (index > 0) {
					items[index - 1].focus();
				} else if (search) {
					search.focus();
				}
				return;
			}

			if (!inSearch && (key === 'Home' || key === 'End') && items.length) {
				e.preventDefault();
				items[key === 'Home' ? 0 : items.length - 1].focus();
				return;
			}

			if (inSearch && key === 'Enter') {
				e.preventDefault();
				if (items.length) {
					items[0].click();
				}
				return;
			}

			// Typing while on the list goes straight into the search field.
			if (search && !inSearch && key.length === 1 && !e.ctrlKey && !e.metaKey && !e.altKey) {
				search.focus();
			}

			// Keep Tab inside the panel.
			if (key === 'Tab') {
				var all = toArray(panel.querySelectorAll('button, input, a[href]')).filter(function (el) {
					return el.offsetParent !== null;
				});
				if (!all.length) {
					return;
				}
				var first = all[0];
				var last = all[all.length - 1];
				if (e.shiftKey && active === first) {
					e.preventDefault();
					last.focus();
				} else if (!e.shiftKey && active === last) {
					e.preventDefault();
					first.focus();
				}
			}
		});

		if (search) {
			search.addEventListener('input', function () {
				filter(search.value);
			});
		}

		/* ---------- clicks ---------- */

		if (hasBarTrigger) {
			trigger.addEventListener('click', function (e) {
				// Inside a phpBB menu: do not let the click reach phpBB's own handlers.
				if (inDropdown(trigger)) {
					e.preventDefault();
				}
				toggleFrom(trigger);
			});
		}

		if (fab) {
			fab.addEventListener('click', function () {
				toggleFrom(fab);
			});
		}

		backdrop.addEventListener('click', function () {
			close(false);
		});

		panel.addEventListener('click', function (e) {
			var target = e.target;

			if (target.closest('[data-nt-close]')) {
				close(true);
				return;
			}

			var toggle = target.closest('[data-nt-toggle]');
			if (toggle) {
				var id = toggle.closest('.nt-row')._id;
				if (collapsed[id]) {
					delete collapsed[id];
				} else {
					collapsed[id] = true;
				}
				save(keyCollapsed, Object.keys(collapsed));
				if (!query.trim()) {
					applyTree();
				}
				return;
			}

			var pick = target.closest('[data-nt-pick]');
			if (pick) {
				remember(pick.getAttribute('data-nt-pick'));
			}
		});

		document.addEventListener('pointerdown', function (e) {
			if (!isOpen) {
				return;
			}
			var t = e.target;
			if (panel.contains(t) || trigger.contains(t) || (fab && fab.contains(t)) || t === backdrop) {
				return;
			}
			close(false);
		});

		window.addEventListener('resize', function () {
			if (isOpen) {
				place();
			}
		});

		window.addEventListener('scroll', function () {
			if (isOpen && !isMobile()) {
				place();
			}
		}, { passive: true });

		// Back/forward cache: never come back with the panel open.
		window.addEventListener('pageshow', function () {
			close(false);
		});
	});
}());
