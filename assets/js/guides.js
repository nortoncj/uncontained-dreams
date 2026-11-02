/**
 * Guides
 * - Hub ([ucd_guides] and [ucd_guides_search]): topic buttons and the search
 *   box filter the cards as you type. The topic goes in the address
 *   (?topic=) so a filtered view can be shared; the page renders the same
 *   view without this script.
 * - Newsletter ([ucd_newsletter_form]): checks the email and signs up in the
 *   background. Without this script the form posts and the page reloads.
 */
(function () {
  'use strict';

  var list = document.getElementById('guides-list');
  if (list) {
    var cards = Array.prototype.slice.call(list.querySelectorAll('.ucd-guide-card'));
    var buttons = Array.prototype.slice.call(document.querySelectorAll('.ucd-guide-filters .ucd-filter'));
    var q = document.getElementById('guides-q');
    var start = document.getElementById('start');
    var count = document.getElementById('guides-count');
    var empty = document.getElementById('guides-empty');
    var active = buttons.filter(function (b) { return b.getAttribute('aria-pressed') === 'true'; })[0];
    var topic = active ? active.getAttribute('data-topic') : '';

    var render = function () {
      var term = q ? q.value.trim().toLowerCase() : '';
      var shown = 0;
      cards.forEach(function (card) {
        var match = (!topic || card.getAttribute('data-topic') === topic) &&
          (!term || card.getAttribute('data-text').indexOf(term) !== -1);
        card.hidden = !match;
        if (match) shown++;
      });
      count.textContent = shown ? shown + (shown === 1 ? ' guide' : ' guides') : '';
      empty.hidden = shown > 0;
      buttons.forEach(function (b) { b.setAttribute('aria-pressed', String(b.getAttribute('data-topic') === topic)); });
      // Start here is for first-time readers; hide it while someone is filtering or searching.
      if (start) start.hidden = !!topic || !!term;
    };

    buttons.forEach(function (b) {
      b.addEventListener('click', function () {
        topic = b.getAttribute('data-topic');
        var url = new URL(location.href);
        if (topic) url.searchParams.set('topic', topic); else url.searchParams.delete('topic');
        history.replaceState(null, '', url);
        render();
      });
    });

    if (q) {
      q.addEventListener('input', render);
      q.form.addEventListener('submit', function (e) {
        e.preventDefault();
        document.getElementById('all').scrollIntoView({ behavior: 'smooth' });
      });
    }
  }

  var form = document.getElementById('news-form');
  if (form) {
    var email = document.getElementById('n-email');
    var err = document.getElementById('n-email-err');
    var fail = document.getElementById('news-fail');
    var valid = function () { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email.value.trim()); };
    var btn = form.querySelector('button');

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      var ok = valid();
      email.setAttribute('aria-invalid', String(!ok));
      err.hidden = ok;
      fail.hidden = true;
      if (!ok) { email.focus(); return; }
      btn.disabled = true;
      btn.textContent = 'Sending…';
      fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } })
        .then(function (res) { return res.json(); })
        .then(function (json) {
          if (!json.success) throw new Error('rejected');
          form.hidden = true;
          document.getElementById('news-note').hidden = true;
          document.getElementById('news-done').hidden = false;
          // Conversion event. Swap for the site's analytics call once it's chosen.
          window.dataLayer = window.dataLayer || [];
          window.dataLayer.push({ event: 'newsletter_signup', tag: 'guides' });
        })
        .catch(function () {
          fail.hidden = false;
          btn.disabled = false;
          btn.textContent = 'Sign me up';
        });
    });

    email.addEventListener('input', function () {
      if (email.getAttribute('aria-invalid') === 'true' && valid()) {
        email.setAttribute('aria-invalid', 'false');
        err.hidden = true;
      }
    });
  }
})();
