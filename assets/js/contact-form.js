/**
 * Contact form
 * - Checks the required fields before sending and shows the message under
 *   each field that needs fixing; it clears as soon as the field is fixed.
 * - The form then posts normally. WordPress saves and emails it, and sends
 *   the visitor to the thank-you page (or back here if something failed).
 */
(function () {
  'use strict';

  var form = document.getElementById('contact-form');
  if (!form) return;

  var submit = form.querySelector('button[type="submit"]');

  var checks = [
    { el: form.first_name, ok: function (v) { return v.trim() !== ''; } },
    { el: form.last_name, ok: function (v) { return v.trim() !== ''; } },
    { el: form.email, ok: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim()); } },
    { el: form.topic, ok: function (v) { return v !== ''; } }
  ];

  function setError(el, bad) {
    el.setAttribute('aria-invalid', String(bad));
    document.getElementById(el.id + '-err').hidden = !bad;
  }

  function validate() {
    var firstBad = null;
    checks.forEach(function (c) {
      var bad = !c.ok(c.el.value);
      setError(c.el, bad);
      if (bad && !firstBad) firstBad = c.el;
    });
    if (firstBad) firstBad.focus();
    return !firstBad;
  }

  // Clear an error as soon as the field is fixed.
  checks.forEach(function (c) {
    c.el.addEventListener(c.el.tagName === 'SELECT' ? 'change' : 'input', function () {
      if (c.el.getAttribute('aria-invalid') === 'true' && c.ok(c.el.value)) setError(c.el, false);
    });
  });

  form.addEventListener('submit', function (e) {
    document.getElementById('form-alert').hidden = true;
    if (!validate()) {
      e.preventDefault();
      return;
    }
    submit.disabled = true;
    submit.textContent = 'Sending…';
  });

  // Coming back with the browser's Back button restores the page as it was left; make the button usable again.
  window.addEventListener('pageshow', function (e) {
    if (!e.persisted) return;
    submit.disabled = false;
    submit.textContent = 'Send message';
  });
})();
