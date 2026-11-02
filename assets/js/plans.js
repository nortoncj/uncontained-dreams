/**
 * Plans list form ([ucd_plans_form])
 * - Checks the required fields and shows the message under each one that
 *   needs fixing; it clears as soon as the field is fixed.
 * - Sends the signup in the background, then goes to the thank-you page.
 *   Without this script the form posts normally with the same result.
 * - "Join the list" links in the course section tick the course box on the way.
 */
(function () {
  'use strict';

  var form = document.getElementById('plans-form');
  if (!form) return;

  var $ = function (id) { return document.getElementById(id); };
  var submit = form.querySelector('button[type="submit"]');
  var label = submit.textContent;

  var checks = [
    { el: form.first_name, ok: function (v) { return v.trim() !== ''; } },
    { el: form.email, ok: function (v) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(v.trim()); } },
    { el: form.state, ok: function (v) { return v !== ''; } },
    { el: form.project, ok: function (v) { return v !== ''; } }
  ];

  function setError(el, bad) {
    el.setAttribute('aria-invalid', String(bad));
    $(el.id + '-err').hidden = !bad;
  }

  checks.forEach(function (c) {
    c.el.addEventListener(c.el.tagName === 'SELECT' ? 'change' : 'input', function () {
      if (c.el.getAttribute('aria-invalid') === 'true' && c.ok(c.el.value)) setError(c.el, false);
    });
  });

  function reset() {
    submit.disabled = false;
    submit.textContent = label;
  }

  function failed() {
    $('plans-alert').hidden = false;
    $('plans-alert').scrollIntoView({ block: 'center' });
    reset();
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    $('plans-alert').hidden = true;
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
        if (json.success) {
          location.href = json.data.redirect;
          return;
        }
        // The server caught a field the checks above let through.
        var fields = (json.data && json.data.fields) || [];
        var bad = checks.filter(function (c) { return fields.indexOf(c.el.name) !== -1; });
        bad.forEach(function (c) { setError(c.el, true); });
        if (bad.length) {
          reset();
          bad[0].el.focus();
        } else {
          failed();
        }
      })
      .catch(failed);
  });

  Array.prototype.forEach.call(document.querySelectorAll('#course a[href$="#plans-list"]'), function (a) {
    a.addEventListener('click', function () { form.course.checked = true; });
  });
})();
