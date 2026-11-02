/**
 * Build confirmation (/build/confirmation/)
 * Fills in [ucd_build_receipt] (build number and email) and shows
 * [ucd_if_financing] from what the review page saved in this browser tab.
 * The email stays out of the URL so it doesn't land in logs or analytics.
 */
(function () {
  'use strict';

  var saved = null;
  try { saved = JSON.parse(sessionStorage.getItem('ucd-build') || 'null'); } catch (e) { /* show the generic version */ }
  if (!saved) return;

  document.querySelectorAll('.ucd-build-receipt').forEach(function (receipt) {
    receipt.querySelector('[data-build-number]').textContent = saved.number;
    receipt.querySelector('[data-build-email]').textContent = saved.email;
    receipt.hidden = false;
  });
  document.querySelectorAll('.ucd-financing-note').forEach(function (note) {
    note.hidden = !saved.financing;
  });
})();
