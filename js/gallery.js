/**
 * Gallery page, and the photos section of a model page
 * - Filter bar: one button per gallery section (Groups with the
 *   ucd-gallery-section class), labeled with the section's heading, so a
 *   section added in the editor gets a button automatically. Full width on
 *   the Gallery page; inside the section when the sections sit in a
 *   ucd-gallery-body--inline group.
 * - Filtering shows one section, hides the intros, and adds a "coming soon"
 *   note to a section with nothing in it yet.
 * - Lightbox: photos in a Gallery block open full size and step through the
 *   ones currently visible.
 */
(function () {
  'use strict';

  var sections = Array.prototype.slice.call(document.querySelectorAll('.ucd-gallery-section'));
  if (!sections.length) return;

  var config = window.ucdGallery || {};

  function slug(text) {
    return text.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
  }

  sections.forEach(function (section) {
    var heading = section.querySelector('h2, h3');
    section.dataset.label = heading ? heading.textContent.trim() : '';
    if (!section.id) section.id = slug(section.dataset.label);
  });

  var body = sections[0].closest('.ucd-gallery-body') || sections[0];
  var inline = body.classList.contains('ucd-gallery-body--inline');

  // Filter bar, inserted above the gallery sections
  var bar = document.createElement('nav');
  bar.className = inline ? 'ucd-filters ucd-filters--inline' : 'ucd-filters alignfull';
  bar.setAttribute('aria-label', inline ? 'Filter the photos' : 'Filter the gallery');
  var row = document.createElement('div');
  row.className = inline ? 'ucd-filters__row' : 'wrap ucd-filters__row';
  bar.appendChild(row);

  var filters = [{ id: 'all', label: 'All' }].concat(sections.map(function (section) {
    return { id: section.id, label: section.dataset.label };
  })).map(function (filter) {
    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'ucd-filter';
    button.dataset.filter = filter.id;
    button.textContent = filter.label;
    button.setAttribute('aria-pressed', String(filter.id === 'all'));
    row.appendChild(button);
    return button;
  });

  body.parentNode.insertBefore(bar, body);

  function emptyNote(label) {
    var note = document.createElement('div');
    note.className = 'ucd-gallery-empty';
    var text = document.createElement('p');
    text.textContent = 'More ' + label + ' photos coming soon. Want to see a specific detail? Ask us and we\'ll send it.';
    var link = document.createElement('a');
    link.className = 'btn ghost';
    link.href = config.photoRequestUrl || '#contact';
    link.textContent = 'Ask for photos';
    note.appendChild(text);
    note.appendChild(link);
    return note;
  }

  function applyFilter(name) {
    filters.forEach(function (button) {
      button.setAttribute('aria-pressed', String(button.dataset.filter === name));
    });
    sections.forEach(function (section) {
      var show = name === 'all' || section.id === name;
      section.hidden = !show;
      section.querySelectorAll('.ucd-gallery-head p').forEach(function (intro) {
        intro.hidden = name !== 'all';
      });
      // Empty note only when this section is filtered on and has nothing in it.
      var empty = section.querySelector('.ucd-gallery-empty');
      var hasItems = section.querySelector('.wp-block-image img, .wp-block-video video');
      if (show && name !== 'all' && !hasItems && !empty) {
        section.appendChild(emptyNote(section.dataset.label));
      } else if (empty) {
        empty.hidden = name === 'all' || !!hasItems;
      }
    });
  }

  filters.forEach(function (button) {
    button.addEventListener('click', function () {
      applyFilter(button.dataset.filter);
      history.replaceState(null, '', button.dataset.filter === 'all' ? location.pathname : '#' + button.dataset.filter);
    });
  });

  var start = location.hash.slice(1);
  if (start && sections.some(function (section) { return section.id === start; })) applyFilter(start);

  // Lightbox
  var lb = document.createElement('dialog');
  lb.className = 'ucd-lb';
  lb.setAttribute('aria-label', 'Photo viewer');
  lb.innerHTML =
    '<figure><img src="" alt=""><figcaption></figcaption></figure>' +
    '<button class="ucd-lb__btn ucd-lb__close" type="button" aria-label="Close"><svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="square"><path d="M5 5l14 14M19 5L5 19"/></svg></button>' +
    '<div class="ucd-lb__nav">' +
    '<button class="ucd-lb__btn ucd-lb__prev" type="button" aria-label="Previous photo"><svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="square"><path d="M15 5l-7 7 7 7"/></svg></button>' +
    '<button class="ucd-lb__btn ucd-lb__next" type="button" aria-label="Next photo"><svg aria-hidden="true" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="square"><path d="M9 5l7 7-7 7"/></svg></button>' +
    '</div>';
  document.body.appendChild(lb);

  var lbImg = lb.querySelector('img');
  var lbCap = lb.querySelector('figcaption');
  var PHOTO = '.ucd-gallery-section .wp-block-gallery .wp-block-image';
  var list = [];
  var index = 0;

  // Full-size URL: the link when the image links to its media file, else the image itself.
  function fullSize(figure) {
    var link = figure.querySelector(':scope > a');
    var img = figure.querySelector('img');
    if (link) return /\.(jpe?g|png|gif|webp|avif)(\?|$)/i.test(link.href) ? link.href : null;
    return img ? img.currentSrc || img.src : null;
  }

  // Images without a link still need to be reachable by keyboard.
  document.querySelectorAll(PHOTO + ' > img').forEach(function (img) {
    img.tabIndex = 0;
    img.setAttribute('role', 'button');
  });

  function show(i) {
    index = (i + list.length) % list.length;
    var figure = list[index];
    var img = figure.querySelector('img');
    var caption = figure.querySelector('figcaption');
    lbImg.src = fullSize(figure);
    lbImg.alt = img.alt;
    lbCap.textContent = caption ? caption.textContent : '';
  }

  function open(figure) {
    list = Array.prototype.filter.call(document.querySelectorAll(PHOTO), function (item) {
      return !item.closest('[hidden]') && fullSize(item);
    });
    show(list.indexOf(figure));
    lb.showModal();
  }

  document.addEventListener('click', function (e) {
    var target = e.target.closest(PHOTO + ' > a, ' + PHOTO + ' > img');
    if (!target) return;
    var figure = target.closest('.wp-block-image');
    if (!fullSize(figure)) return;
    e.preventDefault();
    open(figure);
  });

  document.addEventListener('keydown', function (e) {
    if ((e.key === 'Enter' || e.key === ' ') && e.target.matches(PHOTO + ' > img')) {
      e.preventDefault();
      open(e.target.closest('.wp-block-image'));
    }
  });

  lb.querySelector('.ucd-lb__close').addEventListener('click', function () { lb.close(); });
  lb.querySelector('.ucd-lb__prev').addEventListener('click', function () { show(index - 1); });
  lb.querySelector('.ucd-lb__next').addEventListener('click', function () { show(index + 1); });
  lb.addEventListener('click', function (e) { if (e.target === lb) lb.close(); });
  lb.addEventListener('keydown', function (e) {
    if (e.key === 'ArrowLeft') show(index - 1);
    if (e.key === 'ArrowRight') show(index + 1);
  });
})();
