/**
 * Mega menu
 * - Desktop: opens on hover (with intent delay) or click; one panel at a time.
 * - Mobile: hamburger drawer with accordion panels.
 * - Keyboard: Enter/Space toggles, Escape closes and returns focus, focus
 *   leaving an item closes its panel.
 */
(function () {
  'use strict';

  var header = document.querySelector('.site-header');
  if (!header) return;

  var nav = header.querySelector('.primary-nav');
  var toggle = header.querySelector('.nav-toggle');
  var items = Array.prototype.slice.call(header.querySelectorAll('.menu-item-has-children'));
  var desktop = window.matchMedia('(min-width: 1100px)');
  var canHover = window.matchMedia('(hover: hover)');

  var OPEN_DELAY = 90;
  var CLOSE_DELAY = 220;
  var timer = null;

  var scrim = document.createElement('div');
  scrim.className = 'mega-scrim';
  scrim.setAttribute('aria-hidden', 'true');
  document.body.appendChild(scrim);

  function triggerOf(item) {
    return item.querySelector('.menu-trigger');
  }

  function openItem(item) {
    items.forEach(function (other) {
      if (other !== item) closeItem(other);
    });
    item.classList.add('is-open');
    triggerOf(item).setAttribute('aria-expanded', 'true');
    if (desktop.matches) scrim.classList.add('is-visible');
  }

  function closeItem(item) {
    item.classList.remove('is-open');
    triggerOf(item).setAttribute('aria-expanded', 'false');
    if (!header.querySelector('.menu-item.is-open')) scrim.classList.remove('is-visible');
  }

  function closeAll() {
    items.forEach(closeItem);
  }

  function setDrawer(open) {
    nav.classList.toggle('is-open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    document.body.classList.toggle('nav-open', open);
    if (!open) closeAll();
  }

  items.forEach(function (item) {
    var trigger = triggerOf(item);

    trigger.addEventListener('click', function () {
      clearTimeout(timer);
      if (item.classList.contains('is-open')) closeItem(item);
      else openItem(item);
    });

    item.addEventListener('mouseenter', function () {
      if (!desktop.matches || !canHover.matches) return;
      clearTimeout(timer);
      // Switch instantly when moving between top-level items
      var delay = header.querySelector('.menu-item.is-open') ? 0 : OPEN_DELAY;
      timer = setTimeout(function () { openItem(item); }, delay);
    });

    item.addEventListener('mouseleave', function () {
      if (!desktop.matches || !canHover.matches) return;
      clearTimeout(timer);
      timer = setTimeout(function () { closeItem(item); }, CLOSE_DELAY);
    });

    item.addEventListener('focusout', function (e) {
      if (desktop.matches && !item.contains(e.relatedTarget)) closeItem(item);
    });
  });

  if (toggle) {
    toggle.addEventListener('click', function () {
      setDrawer(!nav.classList.contains('is-open'));
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key !== 'Escape') return;
    var open = header.querySelector('.menu-item.is-open');
    if (open) {
      closeItem(open);
      triggerOf(open).focus();
    } else if (nav.classList.contains('is-open')) {
      setDrawer(false);
      toggle.focus();
    }
  });

  document.addEventListener('click', function (e) {
    if (desktop.matches && !header.contains(e.target)) closeAll();
  });

  // Following any link inside the menu (e.g. an on-page anchor) closes it
  nav.addEventListener('click', function (e) {
    if (!e.target.closest('a')) return;
    closeAll();
    if (!desktop.matches) setDrawer(false);
  });

  desktop.addEventListener('change', function () {
    setDrawer(false);
  });
})();
