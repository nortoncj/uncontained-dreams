/**
 * Build Options screen (wp-admin)
 * - Add buttons copy a blank row from the page's <template>s, giving it a new
 *   key so its fields post as their own row.
 * - Rows move up and down or are removed; the saved order is the page order.
 * - A new row's ID fills itself from its name until the ID is typed by hand.
 * - "Choose photo" opens the Media Library.
 */
(function () {
  'use strict';

  var screen = document.querySelector('.ucd-build-admin');
  if (!screen) return;

  var counter = 0;

  function newKey() {
    counter += 1;
    return 'n' + Date.now().toString(36) + counter;
  }

  function slug(text) {
    return text.toLowerCase().normalize('NFKD').replace(/[̀-ͯ]/g, '')
      .replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
  }

  function fromTemplate(id, values) {
    var html = document.getElementById(id).innerHTML;
    Object.keys(values).forEach(function (token) {
      html = html.split(token).join(values[token]);
    });
    var holder = document.createElement('div');
    holder.innerHTML = html.trim();
    return holder.firstElementChild;
  }

  screen.addEventListener('click', function (e) {
    var add = e.target.closest('[data-add]');
    if (add) {
      var type = add.dataset.add;
      var row;
      var list;
      if (type === 'choice') {
        list = add.closest('.ucd-row__body').querySelector('[data-repeater="choice"]');
        row = fromTemplate('ucd-tpl-choice', { __step__: list.dataset.step, __group__: list.dataset.group, __key__: newKey() });
      } else if (type === 'group') {
        var group = newKey();
        list = screen.querySelector('[data-repeater="group"][data-step="' + add.dataset.step + '"]');
        row = fromTemplate('ucd-tpl-group', { __step__: add.dataset.step, __group__: group });
        row.querySelector('[data-repeater="choice"]').dataset.group = group;
      } else {
        list = screen.querySelector('[data-repeater="' + type + '"]');
        row = fromTemplate('ucd-tpl-' + type, { __key__: newKey() });
      }
      list.appendChild(row);
      var first = row.querySelector('input[type="text"]');
      if (first) first.focus();
      return;
    }

    var move = e.target.closest('[data-move]');
    if (move) {
      var item = move.closest('[data-row]');
      if (move.dataset.move === '-1' && item.previousElementSibling) item.parentNode.insertBefore(item, item.previousElementSibling);
      if (move.dataset.move === '1' && item.nextElementSibling) item.parentNode.insertBefore(item.nextElementSibling, item);
      move.focus();
      return;
    }

    var remove = e.target.closest('[data-remove]');
    if (remove) {
      var gone = remove.closest('[data-row]');
      var title = gone.querySelector('[data-title-field]');
      var name = title && title.value ? '"' + title.value + '"' : 'this item';
      if (window.confirm('Remove ' + name + '? It goes away when you save.')) gone.remove();
      return;
    }

    var choose = e.target.closest('[data-choose-image]');
    if (choose && window.wp && wp.media) {
      var box = choose.closest('.ucd-image');
      var frame = wp.media({ title: 'Choose a card photo', library: { type: 'image' }, multiple: false, button: { text: 'Use this photo' } });
      frame.on('select', function () {
        var file = frame.state().get('selection').first().toJSON();
        var url = file.sizes && file.sizes.large ? file.sizes.large.url : file.url;
        box.querySelector('[data-image-url]').value = url;
        var preview = box.querySelector('[data-image-preview]');
        preview.src = url;
        preview.hidden = false;
      });
      frame.open();
    }
  });

  screen.addEventListener('input', function (e) {
    var t = e.target;
    // The row heading follows its name field.
    if (t.matches('[data-title-field]')) {
      var row = t.closest('[data-row]');
      var heading = row.querySelector('[data-row-title]');
      if (heading) heading.textContent = t.value || 'Untitled';
      var id = row.querySelector('[data-id-field]');
      if (id && !id.readOnly && !id.dataset.typed) id.value = slug(t.value);
    }
    if (t.matches('[data-id-field]')) t.dataset.typed = t.value ? '1' : '';
    if (t.matches('[data-image-url]')) {
      var preview = t.closest('.ucd-image').querySelector('[data-image-preview]');
      preview.src = t.value;
      preview.hidden = !t.value;
    }
  });
})();
