/**
 * Service Areas ZIP check ([ucd_zip_check])
 * Looks up the ZIP and estimates road miles from the shop with the same math
 * as the builder's delivery step, then says whether delivery is included.
 * States with a live page (data-states on the form) get a link to it.
 */
(function () {
  'use strict';

  var form = document.getElementById('zip-form');
  if (!form) return;

  var B = window.UCDBuild;
  var input = document.getElementById('zip');
  var err = document.getElementById('zip-err');
  var out = document.getElementById('zip-result');
  var states = JSON.parse(form.getAttribute('data-states') || '{}');
  var esc = function (s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; };

  function fail(msg) {
    err.textContent = msg;
    err.hidden = false;
    input.setAttribute('aria-invalid', 'true');
    out.hidden = true;
  }

  form.addEventListener('submit', function (e) {
    e.preventDefault();
    var zip = input.value.trim();
    err.hidden = true;
    input.removeAttribute('aria-invalid');
    if (!/^\d{5}$/.test(zip)) { fail('Enter a 5-digit ZIP code.'); input.focus(); return; }
    out.hidden = false;
    out.className = 'ucd-zip-result';
    out.textContent = 'Checking ' + zip + '…';
    Promise.all([B.load(''), B.lookupZip(zip)]).then(function (r) {
      var data = r[0];
      var place = r[1];
      var miles = B.milesFromShop(data, place.lat, place.lng);
      var included = miles <= data.includedMiles;
      var state = states[place.state];
      var distance = miles < 50 ? ' is close to our shop.' : ' is about ' + miles.toLocaleString('en-US') + ' road miles from our shop.';
      out.className = 'ucd-zip-result ' + (included ? 'is-included' : 'is-quoted');
      out.innerHTML = '<strong>' + esc(place.city + ', ' + place.state) + distance + '</strong>' +
        '<span>' + (included
          ? 'That\'s inside the first 1,000 miles, so delivery is included in your price.'
          : 'The first 1,000 miles are included. The rest is quoted before you sign.') + '</span>' +
        '<span class="ucd-zip-result__note">An estimate from the map, not a quote. Your written quote has the final number.</span>' +
        (state ? '<a href="' + esc(state.url) + '">See ' + esc(state.name) + ' details</a>' : '');
    }).catch(function (e2) {
      if (e2 && e2.message === 'notfound') fail('We couldn\'t find that ZIP. Check it and try again.');
      else fail('We couldn\'t check that right now. Try again, or call us at [PHONE].');
    });
  });

  // "Check delivery to your ZIP" links jump back up to the field.
  Array.prototype.forEach.call(document.querySelectorAll('a[href="#zip"]'), function (a) {
    a.addEventListener('click', function (e) {
      e.preventDefault();
      form.scrollIntoView({ behavior: 'smooth', block: 'center' });
      input.focus({ preventScroll: true });
    });
  });
})();
