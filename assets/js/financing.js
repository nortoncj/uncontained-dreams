/**
 * Financing form ([ucd_financing_form])
 * - Checks the required fields and shows the message under each one that
 *   needs fixing; it clears as soon as the field is fixed.
 * - Sends the request in the background and shows the confirmation in place.
 *   Without this script the form posts normally and the page reloads with
 *   the same result.
 */
(function () {
  'use strict';

  var form = document.getElementById('fin-form');
  if (!form) return;

  var $ = function (id) { return document.getElementById(id); };
  var submit = form.querySelector('button[type="submit"]');

  var checks = [
    { el: form.first_name, ok: function (v) { return v.trim() !== ''; } },
    { el: form.last_name, ok: function (v) { return v.trim() !== ''; } },
    { el: form.email, ok: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim()); } },
    { el: form.phone, ok: function (v) { return v.replace(/\D/g, '').length >= 10; } },
    { el: form.zip, ok: function (v) { return /^\d{5}$/.test(v.trim()); } }
  ];

  function setError(el, bad) {
    el.setAttribute('aria-invalid', String(bad));
    $(el.id + '-err').hidden = !bad;
  }

  checks.forEach(function (c) {
    c.el.addEventListener('input', function () {
      if (c.el.getAttribute('aria-invalid') === 'true' && c.ok(c.el.value)) setError(c.el, false);
    });
  });

  function failed() {
    $('send-error').hidden = false;
    $('send-error').scrollIntoView({ block: 'center' });
    submit.disabled = false;
    submit.textContent = 'Apply for financing';
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    $('send-error').hidden = true;
    var firstBad = null;
    checks.forEach(function (c) {
      var bad = !c.ok(c.el.value);
      setError(c.el, bad);
      if (bad && !firstBad) firstBad = c.el;
    });
    if (firstBad) { firstBad.focus(); return; }

    submit.disabled = true;
    submit.textContent = 'Sending…';
    fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } })
      .then(function (res) { return res.json(); })
      .then(function (json) {
        if (!json.success) {
          // The server caught a field the checks above let through.
          var fields = (json.data && json.data.fields) || [];
          checks.forEach(function (c) { if (fields.indexOf(c.el.name) !== -1) setError(c.el, true); });
          var bad = checks.filter(function (c) { return fields.indexOf(c.el.name) !== -1; })[0];
          if (bad) {
            submit.disabled = false;
            submit.textContent = 'Apply for financing';
            bad.el.focus();
          } else {
            failed();
          }
          return;
        }
        // Conversion event. Swap for the site's analytics call.
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ event: 'financing_submitted', home: form.home.value, from_build: form.build_config.value !== '' });
        form.hidden = true;
        $('fin-success').hidden = false;
        $('fin-success').focus();
      })
      .catch(failed);
  });
})();
