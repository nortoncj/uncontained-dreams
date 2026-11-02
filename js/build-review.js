/**
 * Build review (/build/review/)
 * Shows the build from the ?config= URL with edit links back to each step,
 * checks the quote form, and sends both to WordPress, which recalculates the
 * prices, saves the build under Builds and emails it. Markup comes from
 * [ucd_build_review].
 */
(function () {
  var B = window.UCDBuild;
  var cfg = B.fromUrl();
  var data = null;
  var $ = function (id) { return document.getElementById(id); };
  var esc = function (s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; };
  var BLOCKS = [
    { step: 'model', title: 'Model' },
    { step: 'exterior', title: 'Exterior' },
    { step: 'interior', title: 'Interior' },
    { step: 'upgrades', title: 'Upgrades' },
    { step: 'delivery', title: 'Delivery' }
  ];

  function priceCell(it) {
    if (it.price === undefined) return '<span class="is-quoted">Not set</span>';
    if (it.price === null) return '<span class="is-quoted">' + (it.delivery ? 'Quoted separately' : 'Quoted') + '</span>';
    if (it.price === 0) return '<span class="is-included">Included</span>';
    return '<span>' + (it.label.indexOf(', base') !== -1 ? B.money(it.price) : '+' + B.money(it.price)) + '</span>';
  }

  function render() {
    var res = B.lines(data, cfg);
    $('rv-blocks').innerHTML = BLOCKS.map(function (b) {
      var items = res.items.filter(function (it) { return it.step === b.step; });
      var rows = items.length ? items.map(function (it) {
        return '<li><span>' + esc(it.label) + '</span>' + priceCell(it) + '</li>';
      }).join('') : '<li><span class="is-quoted">None added</span></li>';
      if (b.step === 'delivery' && cfg.nosite) rows += '<li><span>No site yet</span><span></span></li>';
      return '<div class="rv-block"><div class="rv-head"><h3>' + b.title + '</h3>' +
        '<a href="' + esc(data.urls.build + location.search) + '#' + b.step + '" aria-label="Edit ' + b.title.toLowerCase() + '">Edit</a></div>' +
        '<ul class="rv-lines">' + rows + '</ul></div>';
    }).join('');
    $('rv-total').textContent = B.money(res.total);
    $('rv-quoted').hidden = !res.quoted;
    $('rv-quoted').textContent = 'Plus ' + res.quoted + ' quoted item' + (res.quoted === 1 ? '' : 's') + ', priced in your written quote.';
    if (cfg.loc && !$('q-city').value) $('q-city').value = cfg.loc;
    return res;
  }

  var form = $('quote-form');
  var checks = [
    { el: form.first_name, ok: function (el) { return el.value.trim() !== ''; } },
    { el: form.last_name, ok: function (el) { return el.value.trim() !== ''; } },
    { el: form.email, ok: function (el) { return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(el.value.trim()); } },
    { el: form.phone, ok: function (el) { return el.value.replace(/\D/g, '').length >= 10; } },
    { el: form.delivery_city_state, ok: function (el) { return el.value.trim() !== ''; } },
    { el: form.contact_consent, ok: function (el) { return el.checked; } }
  ];

  function setError(el, bad) {
    el.setAttribute('aria-invalid', String(bad));
    $(el.id + '-err').hidden = !bad;
  }

  checks.forEach(function (c) {
    c.el.addEventListener(c.el.type === 'checkbox' ? 'change' : 'input', function () {
      if (c.el.getAttribute('aria-invalid') === 'true' && c.ok(c.el)) setError(c.el, false);
    });
  });

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    $('send-error').hidden = true;
    var firstBad = null;
    checks.forEach(function (c) {
      var bad = !c.ok(c.el);
      setError(c.el, bad);
      if (bad && !firstBad) firstBad = c.el;
    });
    if (firstBad) { firstBad.focus(); return; }

    var res = B.lines(data, cfg);
    var body = new FormData(form);
    body.append('build_config', JSON.stringify(cfg));

    var btn = form.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Sending…';
    fetch(data.endpoints.submitBuild, { method: 'POST', body: body, headers: { Accept: 'application/json' } })
      .then(function (r) {
        if (!r.ok) throw new Error(r.status);
        return r.json().catch(function () { return {}; });
      })
      .then(function (json) {
        var number = json.buildNumber;
        // Conversion event with the model and total. Swap for the site's analytics call.
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ event: 'build_submitted', model: res.model.name, total: res.total, quoted_items: res.quoted });
        // Kept out of the URL so the email address doesn't land in logs or analytics.
        try {
          sessionStorage.setItem('ucd-build', JSON.stringify({ number: number, email: form.email.value.trim(), financing: form.financing.checked }));
        } catch (err) { /* confirmation page falls back to generic copy */ }
        location.href = data.urls.confirmation;
      })
      .catch(function () {
        $('send-error').hidden = false;
        $('send-error').scrollIntoView({ block: 'center' });
        btn.disabled = false;
        btn.textContent = 'Send my build';
      });
  });

  B.load().then(function (d) {
    data = d;
    B.normalize(data, cfg);
    if (!cfg.m) { $('no-build').hidden = false; return; }
    render();
    $('review').hidden = false;
  }).catch(function () {
    $('load-error').hidden = false;
  });
})();
