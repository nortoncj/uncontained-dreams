/**
 * FAQ page
 * - Search box: filters the questions (Details blocks inside ucd-faq-group
 *   topics) as you type. Answers stay in the HTML for search engines; search
 *   only hides and shows them.
 * - A link like /faq/#delivery-cost opens that answer.
 */
(function () {
  'use strict';

  var groups = document.querySelectorAll('.ucd-faq-group');
  if (!groups.length) return;

  var config = window.ucdFaq || {};
  var questions = document.querySelectorAll('.ucd-faq-group .wp-block-details');
  var openBefore = null;

  // Search box, in the hero intro when there is one
  var search = document.createElement('div');
  search.innerHTML =
    '<form class="ucd-faq-search" role="search">' +
    '<label class="screen-reader-text" for="ucd-faq-q">Search questions</label>' +
    '<svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#5A666C" stroke-width="2" stroke-linecap="square"><circle cx="11" cy="11" r="7"/><path d="M16.5 16.5L21 21"/></svg>' +
    '<input id="ucd-faq-q" type="search" placeholder="Search questions, like &quot;delivery&quot; or &quot;permits&quot;" autocomplete="off">' +
    '</form>' +
    '<p class="ucd-faq-count" role="status"></p>';
  var intro = document.querySelector('.ucd-faq-intro');
  var layout = document.querySelector('.ucd-faq-layout');
  while (search.firstChild) {
    if (intro) intro.appendChild(search.firstChild);
    else layout.parentNode.insertBefore(search.firstChild, layout);
  }

  var form = document.querySelector('.ucd-faq-search');
  var input = document.getElementById('ucd-faq-q');
  var count = document.querySelector('.ucd-faq-count');
  form.addEventListener('submit', function (e) { e.preventDefault(); });

  // Shown when nothing matches
  var empty = document.createElement('div');
  empty.className = 'ucd-faq-empty';
  empty.hidden = true;
  var emptyTitle = document.createElement('h2');
  emptyTitle.textContent = 'No questions match that yet.';
  var emptyText = document.createElement('p');
  emptyText.append('Ask us directly. Call ');
  var tel = document.createElement('a');
  tel.href = 'tel:' + (config.phone || '');
  tel.textContent = config.phone || '';
  emptyText.append(tel, ' and you\'ll talk to the person building your home.');
  var ask = document.createElement('a');
  ask.className = 'btn ghost';
  ask.href = 'mailto:' + (config.email || '');
  ask.textContent = 'Ask a question';
  empty.append(emptyTitle, emptyText, ask);
  (document.querySelector('.ucd-faq-groups') || groups[groups.length - 1].parentNode).appendChild(empty);

  input.addEventListener('input', function () {
    var terms = input.value.toLowerCase().trim().split(/\s+/).filter(Boolean);
    if (terms.length && !openBefore) openBefore = Array.prototype.map.call(questions, function (q) { return q.open; });
    var shown = 0;
    questions.forEach(function (q, i) {
      var text = q.textContent.toLowerCase();
      var match = terms.every(function (t) { return text.indexOf(t) !== -1; });
      q.hidden = !match;
      q.open = terms.length ? match : openBefore ? openBefore[i] : q.open;
      if (match) shown++;
    });
    groups.forEach(function (g) { g.hidden = !g.querySelector('.wp-block-details:not([hidden])'); });
    empty.hidden = shown > 0;
    count.textContent = terms.length ? shown + (shown === 1 ? ' question' : ' questions') + ' match' : '';
    if (!terms.length) openBefore = null;
  });

  function openTarget() {
    var el = location.hash && document.getElementById(decodeURIComponent(location.hash.slice(1)));
    if (el && el.tagName === 'DETAILS') el.open = true;
  }
  window.addEventListener('hashchange', openTarget);
  openTarget();
})();
