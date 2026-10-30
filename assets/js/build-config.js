/* Shared by /build/ and /build/review/: loads prices, reads and writes the
   ?config= build state, and works out line items and totals. */
(function () {
  var DATA_PATH = 'assets/data/build-options.json';
  var MODELS_PATH = 'assets/data/models.json';
  // Free ZIP lookup (city, state, coordinates). Swap for another service if needed.
  var ZIP_API = 'https://api.zippopotam.us/us/';

  function getJson(url) {
    return fetch(url, { cache: 'no-cache' }).then(function (res) {
      if (!res.ok) throw new Error(url + ' ' + res.status);
      return res.json();
    });
  }

  // Options and prices from build-options.json, models from models.json.
  function load(root) {
    return Promise.all([getJson(root + DATA_PATH), getJson(root + MODELS_PATH)]).then(function (r) {
      var data = r[0];
      data.models = r[1].models;
      return data;
    });
  }

  // Build state: { m: model id, o: { group id: choice id }, u: [upgrade ids],
  //   z: ZIP, loc: "City, ST", mi: miles from shop, intl: bool, nosite: bool }
  function encode(cfg) {
    var json = JSON.stringify(cfg);
    return btoa(unescape(encodeURIComponent(json))).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
  }

  function decode(str) {
    if (!str) return null;
    try {
      var b64 = str.replace(/-/g, '+').replace(/_/g, '/');
      while (b64.length % 4) b64 += '=';
      var cfg = JSON.parse(decodeURIComponent(escape(atob(b64))));
      return cfg && typeof cfg === 'object' ? cfg : null;
    } catch (e) {
      return null;
    }
  }

  function fromUrl() {
    var cfg = decode(new URLSearchParams(location.search).get('config')) || {};
    cfg.o = cfg.o || {};
    cfg.u = cfg.u || [];
    return cfg;
  }

  function model(data, id) {
    return data.models.filter(function (m) { return m.id === id; })[0] || null;
  }

  function groups(data) {
    var out = [];
    Object.keys(data.steps).forEach(function (step) {
      data.steps[step].forEach(function (g) { out.push({ step: step, group: g }); });
    });
    return out;
  }

  function choice(group, id) {
    return group.choices.filter(function (c) { return c.id === id; })[0] || null;
  }

  function upgrade(data, id) {
    return data.upgrades.filter(function (u) { return u.id === id; })[0] || null;
  }

  function availableOn(item, modelId) {
    return !modelId || (item.excludeModels || []).indexOf(modelId) === -1;
  }

  // Fills defaults and drops anything the current model can't have.
  // Returns the labels of anything removed so the page can say so.
  function normalize(data, cfg) {
    var removed = [];
    if (cfg.m && !model(data, cfg.m)) cfg.m = null;
    groups(data).forEach(function (entry) {
      var g = entry.group;
      var picked = choice(g, cfg.o[g.id]);
      if (picked && !availableOn(picked, cfg.m)) {
        removed.push(picked.label);
        picked = null;
      }
      if (!picked) {
        delete cfg.o[g.id];
        if (g.default) cfg.o[g.id] = g.default;
      }
    });
    cfg.u = cfg.u.filter(function (id) {
      var u = upgrade(data, id);
      if (u && !availableOn(u, cfg.m)) removed.push(u.label);
      return u && availableOn(u, cfg.m);
    });
    return removed;
  }

  function missing(data, cfg, step) {
    return (data.steps[step] || []).filter(function (g) { return g.required && !cfg.o[g.id]; });
  }

  function money(n) {
    return '$' + Math.round(n).toLocaleString('en-US');
  }

  function priceLabel(price) {
    if (price === null || price === undefined) return 'Quoted';
    if (price === 0) return 'Included';
    return '+' + money(price);
  }

  function deliveryStatus(data, cfg) {
    if (cfg.intl) return { label: 'International delivery', price: null };
    if (!cfg.z || cfg.mi === undefined || cfg.mi === null) return { label: 'Delivery', price: undefined };
    var where = cfg.loc ? cfg.loc : cfg.z;
    if (cfg.mi <= data.includedMiles) return { label: 'Delivery to ' + where, price: 0 };
    return { label: 'Delivery to ' + where, price: null };
  }

  // Line items for the summary. price: number, 0 (included), null (quoted), undefined (not set yet).
  function lines(data, cfg) {
    var m = model(data, cfg.m);
    var items = [];
    if (!m) return { items: items, total: 0, quoted: 0, model: null };
    items.push({ step: 'model', label: m.name + ', base', price: m.basePrice });
    groups(data).forEach(function (entry) {
      var c = choice(entry.group, cfg.o[entry.group.id]);
      if (c) items.push({ step: entry.step, label: entry.group.label + ': ' + c.label, price: c.price });
    });
    cfg.u.forEach(function (id) {
      var u = upgrade(data, id);
      if (u) items.push({ step: 'upgrades', label: u.label, price: u.price });
    });
    items.push({ step: 'model', label: 'Custom furniture', price: 0 });
    var d = deliveryStatus(data, cfg);
    items.push({ step: 'delivery', label: d.label, price: d.price, delivery: true });

    var total = 0;
    var quoted = 0;
    items.forEach(function (it) {
      if (typeof it.price === 'number') total += it.price;
      else if (it.price === null) quoted++;
    });
    return { items: items, total: total, quoted: quoted, model: m };
  }

  function lookupZip(zip) {
    return fetch(ZIP_API + encodeURIComponent(zip)).then(function (res) {
      if (res.status === 404) throw new Error('notfound');
      if (!res.ok) throw new Error('lookup');
      return res.json();
    }).then(function (json) {
      var place = json.places && json.places[0];
      if (!place) throw new Error('notfound');
      return {
        city: place['place name'],
        state: place['state abbreviation'],
        lat: parseFloat(place.latitude),
        lng: parseFloat(place.longitude)
      };
    });
  }

  // Straight-line distance times a road factor, rounded to 10 miles. An estimate, not a quote.
  function milesFromShop(data, lat, lng) {
    var R = 3958.8;
    var toRad = Math.PI / 180;
    var dLat = (lat - data.shop.lat) * toRad;
    var dLng = (lng - data.shop.lng) * toRad;
    var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
      Math.cos(data.shop.lat * toRad) * Math.cos(lat * toRad) * Math.sin(dLng / 2) * Math.sin(dLng / 2);
    var straight = 2 * R * Math.asin(Math.sqrt(a));
    return Math.round(straight * data.roadFactor / 10) * 10;
  }

  // Plain-text summary for the submitted build and the emailed copy.
  function summaryText(data, cfg) {
    var res = lines(data, cfg);
    var rows = res.items.map(function (it) {
      return '- ' + it.label + ': ' + (it.price === undefined ? 'Not set' : priceLabel(it.price));
    });
    rows.push('Estimated total: ' + money(res.total) + (res.quoted ? ' plus ' + res.quoted + ' quoted item' + (res.quoted === 1 ? '' : 's') : ''));
    if (cfg.nosite) rows.push('No site yet');
    return rows.join('\n');
  }

  window.UCDBuild = {
    load: load,
    encode: encode,
    decode: decode,
    fromUrl: fromUrl,
    model: model,
    groups: groups,
    choice: choice,
    upgrade: upgrade,
    availableOn: availableOn,
    normalize: normalize,
    missing: missing,
    money: money,
    priceLabel: priceLabel,
    lines: lines,
    lookupZip: lookupZip,
    milesFromShop: milesFromShop,
    summaryText: summaryText
  };
})();
