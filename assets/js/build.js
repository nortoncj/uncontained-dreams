/**
 * Build Your Home configurator (/build/)
 * Steps through model, exterior, interior, upgrades and delivery, keeps the
 * price summary current, and keeps the build in the ?config= URL so it can be
 * shared. Markup comes from [ucd_build_configurator]; prices and models from
 * Build Options, via build-config.js.
 */
(function () {
  var B = window.UCDBuild;
  var STEPS = ['model', 'exterior', 'interior', 'upgrades', 'delivery'];
  var data = null;
  var cfg = B.fromUrl();
  var current = 'model';

  var $ = function (id) { return document.getElementById(id); };
  var esc = function (s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; };

  function priceSpan(price) {
    var cls = price === 0 ? ' is-included' : '';
    return '<span class="opt__price' + cls + '">' + esc(B.priceLabel(price)) + '</span>';
  }

  function errorName(g) {
    var name = g.errorName || g.label.toLowerCase();
    return (/^[aeiou]/i.test(name) ? 'an ' : 'a ') + name;
  }

  // ---- Rendering -------------------------------------------------------

  function renderModels() {
    $('model-list').innerHTML = data.models.map(function (m) {
      var picked = cfg.m === m.id;
      return '<article class="model-card' + (picked ? ' is-selected' : '') + '">' +
        '<div class="media filled model-card__img"><img src="' + esc(m.image) + '" alt="' + esc(m.name) + ' exterior" loading="lazy"></div>' +
        '<div class="model-card__body">' +
          (picked ? '<span class="chip" style="background: #2F5D47; color: #FFFFFF; width: max-content;">Selected</span>' : '') +
          '<h3 style="font-size: 26px; line-height: 30px; color: #14324A;">' + esc(m.name) + '</h3>' +
          '<p class="model-card__specs">' + esc([m.sqft, m.beds, m.baths].join(' · ')) + '</p>' +
          '<span class="chip" style="background: #14324A; color: #E9B872; font-size: 15px; padding: 6px 14px; width: max-content;">From ' + B.money(m.basePrice) + '</span>' +
          '<p style="font-size: 16px; line-height: 26px;">' + esc(m.line) + '</p>' +
          '<p class="model-card__links"><a href="' + esc(m.tourUrl) + '">Take the 3D tour</a><a href="' + esc(m.photosUrl) + '">See photos</a></p>' +
          '<button class="btn ' + (picked ? 'ghost' : 'primary') + '" type="button" data-model="' + esc(m.id) + '" style="align-self: flex-start;">' + (picked ? 'Selected: ' : 'Choose ') + esc(m.name) + '</button>' +
        '</div></article>';
    }).join('');
  }

  function renderGroups(step) {
    var model = B.model(data, cfg.m);
    $('groups-' + step).innerHTML = data.steps[step].map(function (g) {
      var choices = g.choices.map(function (c) {
        var ok = B.availableOn(c, cfg.m);
        var checked = cfg.o[g.id] === c.id ? ' checked' : '';
        if (!ok) {
          return '<label class="opt is-off"><input type="radio" name="' + esc(g.id) + '" value="' + esc(c.id) + '" disabled>' +
            '<span class="opt__name">' + esc(c.label) + '</span>' +
            '<span class="opt__off">' + esc(c.label) + ' isn\'t available on ' + esc(model ? model.name : 'this model') + '. <a href="#model" data-goto="model">See models that offer it</a></span></label>';
        }
        return '<label class="opt"><input type="radio" name="' + esc(g.id) + '" value="' + esc(c.id) + '" data-group="' + esc(g.id) + '"' + checked + '>' +
          '<span class="opt__name">' + esc(c.label) + '</span>' + priceSpan(c.price) + '</label>';
      }).join('');
      return '<fieldset class="opt-group" id="grp-' + esc(g.id) + '">' +
        '<legend>' + esc(g.label) + (g.required ? '<span class="req">Required</span>' : '') + '</legend>' +
        (g.help ? '<p class="opt-help">' + esc(g.help) + '</p>' : '') +
        '<p class="cfg-err" id="err-' + esc(g.id) + '" role="alert" hidden>Choose ' + esc(errorName(g)) + ' to keep going.</p>' +
        '<div class="opt-grid">' + choices + '</div></fieldset>';
    }).join('');
  }

  function renderUpgrades() {
    $('upgrade-list').innerHTML = data.upgrades.map(function (u) {
      var ok = B.availableOn(u, cfg.m);
      var checked = cfg.u.indexOf(u.id) !== -1 ? ' checked' : '';
      return '<label class="opt' + (ok ? '' : ' is-off') + '"><input type="checkbox" value="' + esc(u.id) + '" data-upgrade' + checked + (ok ? '' : ' disabled') + '>' +
        '<span class="opt__name">' + esc(u.label) + '</span>' +
        (u.line ? '<span class="opt__line">' + esc(u.line) + '</span>' : '') +
        (ok ? priceSpan(u.price) : '<span class="opt__off">Not available on this model.</span>') + '</label>';
    }).join('');
  }

  function renderDelivery() {
    var intl = !!cfg.intl;
    document.querySelector('input[name="dest"][value="' + (intl ? 'intl' : 'us') + '"]').checked = true;
    $('zip-block').hidden = intl;
    if (cfg.z && $('zip').value !== cfg.z) $('zip').value = cfg.z;
    $('nosite').checked = !!cfg.nosite;

    var box = $('delivery-result');
    box.classList.remove('is-included');
    if (intl) {
      box.textContent = 'International delivery is quoted case by case. We\'ll include it in your quote.';
      box.hidden = false;
    } else if (cfg.z && typeof cfg.mi === 'number') {
      if (cfg.mi <= data.includedMiles) {
        box.textContent = 'Delivery to ' + (cfg.loc || cfg.z) + ' is included in your price.';
        box.classList.add('is-included');
      } else {
        box.textContent = 'About ' + cfg.mi.toLocaleString('en-US') + ' miles from ' + data.shop.label + '. The first ' +
          data.includedMiles.toLocaleString('en-US') + ' are included, and we\'ll quote the rest before you sign.';
      }
      box.hidden = false;
    } else {
      box.hidden = true;
    }
  }

  function renderSummary() {
    var res = B.lines(data, cfg);
    $('sum-empty').hidden = !!res.model;
    $('sum-lines').innerHTML = res.items.map(function (it) {
      var label, cls;
      if (it.price === undefined) { label = 'Add your ZIP'; cls = 'is-unset'; }
      else if (it.price === null) { label = it.delivery ? 'Quoted separately' : 'Quoted'; cls = 'is-quoted'; }
      else if (it.price === 0) { label = 'Included'; cls = 'is-included'; }
      else { label = it.step === 'model' && it.label.indexOf(', base') !== -1 ? B.money(it.price) : '+' + B.money(it.price); cls = ''; }
      return '<li><span>' + esc(it.label) + '</span><span class="' + cls + '">' + esc(label) + '</span></li>';
    }).join('');
    var total = res.model ? B.money(res.total) : '$0';
    $('sum-total').textContent = total;
    $('bar-total').textContent = total;
    $('sum-quoted').hidden = !res.quoted;
    $('sum-quoted').textContent = 'Plus ' + res.quoted + ' quoted item' + (res.quoted === 1 ? '' : 's') + ', priced in your written quote.';
    var last = current === 'delivery';
    $('sum-next').textContent = last ? 'Review my build' : 'Next step';
    $('next-btn').textContent = last ? 'Review my build' : 'Next step';
  }

  function renderStepbar() {
    var idx = STEPS.indexOf(current);
    document.querySelectorAll('.stepbar button').forEach(function (b) {
      var i = STEPS.indexOf(b.dataset.goto);
      if (b.dataset.goto === current) b.setAttribute('aria-current', 'step'); else b.removeAttribute('aria-current');
      b.classList.toggle('is-done', i !== -1 && i < idx && stepValid(b.dataset.goto));
    });
    $('back-btn').style.visibility = idx === 0 ? 'hidden' : 'visible';
  }

  // ---- State -------------------------------------------------------------

  function save() {
    var clean = { m: cfg.m, o: cfg.o, u: cfg.u };
    if (cfg.z) { clean.z = cfg.z; clean.loc = cfg.loc; clean.mi = cfg.mi; }
    if (cfg.intl) clean.intl = true;
    if (cfg.nosite) clean.nosite = true;
    var query = cfg.m ? '?config=' + B.encode(clean) : '';
    history.replaceState(null, '', location.pathname + query + '#' + current);
    $('share-link').value = location.href.split('#')[0];
    $('fin-link').href = data.urls.financing + query;
  }

  function update() {
    save();
    renderSummary();
    renderStepbar();
  }

  function showNotice(id, text) {
    var el = $(id);
    el.textContent = text;
    el.hidden = !text;
  }

  // ---- Steps and validation -------------------------------------------------

  function stepValid(step) {
    if (step === 'model') return !!cfg.m;
    if (data.steps[step]) return B.missing(data, cfg, step).length === 0;
    return true;
  }

  function showErrors(step) {
    if (step === 'model') { $('err-model').hidden = !!cfg.m; return; }
    (data.steps[step] || []).forEach(function (g) {
      var err = $('err-' + g.id);
      if (err) err.hidden = !(g.required && !cfg.o[g.id]);
    });
  }

  function goTo(step, focus) {
    current = step;
    document.querySelectorAll('.cfg-step').forEach(function (s) { s.hidden = s.dataset.step !== step; });
    update();
    if (focus) {
      $('h-' + step).focus({ preventScroll: true });
      $('cfg').scrollIntoView({ block: 'start' });
    }
  }

  // Moving forward checks every step on the way; the first one missing something wins.
  function requestStep(target) {
    var idx = target === 'review' ? STEPS.length : STEPS.indexOf(target);
    for (var i = 0; i < idx; i++) {
      if (!stepValid(STEPS[i])) {
        goTo(STEPS[i], true);
        showErrors(STEPS[i]);
        var firstErr = document.querySelector('.cfg-step:not([hidden]) .cfg-err:not([hidden])');
        if (firstErr) firstErr.scrollIntoView({ block: 'center' });
        return;
      }
    }
    if (target === 'review') {
      location.href = data.urls.review + location.search;
      return;
    }
    goTo(target, true);
  }

  function next() {
    var idx = STEPS.indexOf(current);
    requestStep(idx === STEPS.length - 1 ? 'review' : STEPS[idx + 1]);
  }

  // ---- Events ---------------------------------------------------------------

  function bind() {
    document.addEventListener('click', function (e) {
      var go = e.target.closest('[data-goto]');
      if (go) { e.preventDefault(); requestStep(go.dataset.goto); closeSheet(); return; }

      var pick = e.target.closest('[data-model]');
      if (pick) {
        cfg.m = pick.dataset.model;
        $('err-model').hidden = true;
        var removed = B.normalize(data, cfg);
        renderAll();
        var note = removed.length ? removed.join(', ') + (removed.length === 1 ? ' isn\'t' : ' aren\'t') + ' available on ' + B.model(data, cfg.m).name + ', so we took ' + (removed.length === 1 ? 'it' : 'them') + ' out.' : '';
        showNotice('notice-exterior', note);
        showNotice('notice-interior', note);
        showNotice('notice-upgrades', note);
        requestStep('exterior');
      }
    });

    document.addEventListener('change', function (e) {
      var t = e.target;
      if (t.dataset.group) {
        cfg.o[t.dataset.group] = t.value;
        var err = $('err-' + t.dataset.group);
        if (err) err.hidden = true;
        update();
      } else if (t.hasAttribute('data-upgrade')) {
        var u = B.upgrade(data, t.value);
        if (t.checked) {
          var msg = [];
          (u.conflicts || []).forEach(function (id) {
            var i = cfg.u.indexOf(id);
            if (i !== -1) { cfg.u.splice(i, 1); msg.push(B.upgrade(data, id).label); }
          });
          cfg.u.push(u.id);
          if (msg.length) renderUpgrades();
          var replaced = u.replaces ? [u.replaces] : [];
          replaced = replaced.concat(msg);
          showNotice('notice-upgrades', replaced.length ? 'Adding ' + u.label + ' replaces ' + replaced.join(' and ') + '. Your total is updated.' : '');
        } else {
          cfg.u = cfg.u.filter(function (id) { return id !== u.id; });
          showNotice('notice-upgrades', '');
        }
        update();
      } else if (t.name === 'dest') {
        cfg.intl = t.value === 'intl';
        renderDelivery();
        update();
      } else if (t.id === 'nosite') {
        cfg.nosite = t.checked;
        update();
      }
    });

    $('zip').addEventListener('input', function () {
      this.value = this.value.replace(/\D/g, '').slice(0, 5);
      $('zip-err').hidden = true;
      if (this.value.length === 5 && this.value !== cfg.z) checkZip();
    });
    $('zip-check').addEventListener('click', checkZip);
    $('zip').addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); checkZip(); } });

    $('next-btn').addEventListener('click', next);
    $('sum-next').addEventListener('click', function () { closeSheet(); next(); });
    $('back-btn').addEventListener('click', function () {
      var idx = STEPS.indexOf(current);
      if (idx > 0) goTo(STEPS[idx - 1], true);
    });

    $('bar-open').addEventListener('click', function () {
      $('summary').classList.add('is-open');
      this.setAttribute('aria-expanded', 'true');
      $('sum-close').focus();
    });
    $('sum-close').addEventListener('click', closeSheet);

    $('share-toggle').addEventListener('click', function () {
      var open = $('share-pop').hidden;
      $('share-pop').hidden = !open;
      this.setAttribute('aria-expanded', String(open));
    });
    $('share-copy').addEventListener('click', copyLink);
    $('share-email').addEventListener('submit', emailBuild);
  }

  function closeSheet() {
    $('summary').classList.remove('is-open');
    $('bar-open').setAttribute('aria-expanded', 'false');
  }

  function checkZip() {
    var zip = $('zip').value.trim();
    var err = $('zip-err');
    if (!/^\d{5}$/.test(zip)) {
      err.textContent = 'We couldn\'t find that ZIP. Check the number, or skip it and we\'ll sort out delivery on our call.';
      err.hidden = false;
      return;
    }
    $('zip-check').disabled = true;
    B.lookupZip(zip).then(function (place) {
      cfg.z = zip;
      cfg.loc = place.city + ', ' + place.state;
      cfg.mi = B.milesFromShop(data, place.lat, place.lng);
      err.hidden = true;
    }).catch(function (e) {
      cfg.z = null; cfg.loc = null; cfg.mi = null;
      err.textContent = e.message === 'notfound'
        ? 'We couldn\'t find that ZIP. Check the number, or skip it and we\'ll sort out delivery on our call.'
        : 'We couldn\'t check delivery right now. Keep going, and we\'ll sort out delivery on our call.';
      err.hidden = false;
    }).then(function () {
      $('zip-check').disabled = false;
      renderDelivery();
      update();
    });
  }

  function copyLink() {
    var btn = $('share-copy');
    var input = $('share-link');
    var done = function () {
      btn.textContent = 'Copied';
      setTimeout(function () { btn.textContent = 'Copy link'; }, 2000);
    };
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(input.value).then(done, function () { input.select(); });
    } else {
      input.select();
      try { document.execCommand('copy'); done(); } catch (e) { /* the link stays selected to copy by hand */ }
    }
  }

  function emailBuild(e) {
    e.preventDefault();
    var input = $('share-email-input');
    var msg = $('share-email-msg');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(input.value.trim())) {
      msg.textContent = 'That email doesn\'t look right. Check for typos.';
      input.focus();
      return;
    }
    var body = new FormData();
    body.append('email', input.value.trim());
    body.append('config', JSON.stringify(cfg));
    body.append('website', $('share-website').value);
    msg.textContent = 'Sending…';
    fetch(data.endpoints.emailBuild, { method: 'POST', body: body, headers: { Accept: 'application/json' } })
      .then(function (res) {
        if (res.status === 429) throw new Error('limit');
        if (!res.ok) throw new Error(res.status);
        msg.textContent = 'Sent. Check your inbox.';
      })
      .catch(function (err) {
        msg.textContent = err.message === 'limit'
          ? 'We\'ve sent a few already. Copy the link above instead.'
          : 'That didn\'t send. Copy the link above instead.';
      });
  }

  function renderAll() {
    renderModels();
    renderGroups('exterior');
    renderGroups('interior');
    renderUpgrades();
    renderDelivery();
  }

  // ---- Start ----------------------------------------------------------------

  B.load().then(function (d) {
    data = d;
    B.normalize(data, cfg);
    renderAll();
    bind();
    $('cfg').hidden = false;
    $('sum-bar').hidden = false;
    var start = location.hash.slice(1);
    goTo(cfg.m && STEPS.indexOf(start) > 0 ? start : 'model', false);
  }).catch(function () {
    $('load-error').hidden = false;
  });
})();
