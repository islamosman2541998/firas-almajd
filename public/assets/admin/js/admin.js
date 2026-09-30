'use strict';
/*
 * Dashboard glue: toasts (Notyf), confirmations (SweetAlert2), sidebar.
 * Components talk to it only through events, so Livewire re-renders never
 * fight with this file:
 *   $this->dispatch('toast', type: 'success', message: '...')
 *   <button @click="confirmAction(() => $wire.delete(5))">
 */
(() => {
  const i18n = window.AdminI18n || {};
  const css = name => getComputedStyle(document.documentElement).getPropertyValue(name).trim();

  const notyf = new Notyf({
    duration: 3500,
    dismissible: true,
    ripple: false,
    position: {x: i18n.rtl ? 'left' : 'right', y: 'bottom'},
    types: [
      {type: 'success', background: css('--a-success') || '#3f7a50', icon: false},
      {type: 'error', background: css('--a-danger') || '#b0412e', icon: false},
      {type: 'warning', background: css('--a-warning') || '#b4822a', icon: false},
      {type: 'info', background: css('--a-info') || '#3d6a80', icon: false},
    ],
  });

  window.toast = (message, type = 'success') => notyf.open({type, message});

  addEventListener('toast', event => {
    const detail = Array.isArray(event.detail) ? event.detail[0] : event.detail;
    if (detail?.message) window.toast(detail.message, detail.type || 'success');
  });

  if (window.AdminFlash?.message) {
    addEventListener('DOMContentLoaded', () => window.toast(window.AdminFlash.message, window.AdminFlash.type || 'success'));
  }

  /* SweetAlert2 confirmation. Returns a promise; runs callback when confirmed. */
  window.confirmAction = (callback, options = {}) => Swal.fire({
    title: options.title || i18n.confirm_title,
    text: options.text || i18n.confirm_text,
    icon: options.icon || 'warning',
    showCancelButton: true,
    confirmButtonText: options.confirm || i18n.confirm_yes,
    cancelButtonText: i18n.confirm_cancel,
    confirmButtonColor: options.icon === 'question' ? css('--a-primary') : css('--a-danger'),
    cancelButtonColor: 'transparent',
    reverseButtons: !i18n.rtl,
    focusCancel: true,
    customClass: {cancelButton: 'a-btn'},
  }).then(result => { if (result.isConfirmed && typeof callback === 'function') callback(); return result.isConfirmed; });

  addEventListener('alert', event => {
    const d = Array.isArray(event.detail) ? event.detail[0] : event.detail;
    Swal.fire({title: d.title || '', text: d.text || '', icon: d.icon || 'info', confirmButtonColor: css('--a-primary')});
  });

  /* Mobile sidebar */
  document.addEventListener('click', event => {
    if (event.target.closest('[data-sidebar-toggle]')) document.documentElement.classList.toggle('a-sidebar-open');
    else if (event.target.closest('[data-sidebar-close]')) document.documentElement.classList.remove('a-sidebar-open');
  });
  document.addEventListener('keydown', event => { if (event.key === 'Escape') document.documentElement.classList.remove('a-sidebar-open'); });

  /* Keep validation errors visible: scroll to the first one after a failed save. */
  document.addEventListener('livewire:init', () => {
    Livewire.hook('commit', ({succeed}) => {
      succeed(() => requestAnimationFrame(() => {
        const invalid = document.querySelector('.a-content .is-invalid, .a-drawer .is-invalid');
        if (invalid && !invalid.dataset.seen) {
          invalid.dataset.seen = '1';
          invalid.scrollIntoView({behavior: 'smooth', block: 'center'});
        }
      }));
    });
  });
})();
