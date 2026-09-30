'use strict';
/*
 * Firas Al Majd — public site behaviour.
 * Vanilla JS only (Bootstrap bundle for collapse). Livewire owns the two forms;
 * this file never touches elements inside a Livewire component except
 * through native events (change / custom "fam-lead").
 */
(() => {
  const html = document.documentElement;
  const isRtl = () => html.dir === 'rtl';
  const reducedMotion = matchMedia('(prefers-reduced-motion: reduce)');
  const onReady = fn => document.readyState === 'loading' ? document.addEventListener('DOMContentLoaded', fn, {once: true}) : fn();

  /* ---------- Loader (home only) ---------- */
  const siteLoader = document.getElementById('siteLoader');
  if (siteLoader) {
    const hide = () => {
      if (siteLoader.classList.contains('is-hidden')) return;
      siteLoader.classList.add('is-hidden');
      setTimeout(() => siteLoader.remove(), 500);
    };
    if (document.readyState === 'complete') setTimeout(hide, 700);
    else addEventListener('load', () => setTimeout(hide, 700), {once: true});
    setTimeout(hide, 3000);
  }

  onReady(() => {
    initNavigation();
    initDropdowns();
    initReveal();
    initProgress();
    initHeroSlider();
    initPartners();
    initGallery();
    initCertificates();
    initPdfViewer();
    initCareers();
  });

  /* ---------- Mobile navigation ---------- */
  function initNavigation() {
    const mobileNav = document.getElementById('mobileNav');
    const toggle = document.querySelector('.menu-toggle');
    if (!mobileNav || !toggle || !window.bootstrap) return;
    const collapse = () => bootstrap.Collapse.getOrCreateInstance(mobileNav, {toggle: false});
    mobileNav.querySelectorAll('a').forEach(link => link.addEventListener('click', () => collapse().hide()));
    document.addEventListener('keydown', event => {
      if (event.key !== 'Escape' || toggle.getAttribute('aria-expanded') !== 'true') return;
      const close = () => { collapse().hide(); toggle.focus(); };
      if (mobileNav.classList.contains('collapsing')) mobileNav.addEventListener('shown.bs.collapse', close, {once: true});
      else close();
    });
    matchMedia('(min-width: 992px)').addEventListener('change', event => { if (event.matches) collapse().hide(); });
  }

  /* ---------- Desktop dropdowns (hover via CSS, click + keyboard here) ---------- */
  function initDropdowns() {
    const dropdowns = [...document.querySelectorAll('.nav-dropdown')];
    if (!dropdowns.length) return;
    const closeAll = except => dropdowns.forEach(d => { if (d !== except) setOpen(d, false); });
    const setOpen = (dropdown, open) => {
      dropdown.classList.toggle('is-open', open);
      dropdown.querySelector('.nav-dropdown-toggle')?.setAttribute('aria-expanded', String(open));
    };
    dropdowns.forEach(dropdown => {
      const toggle = dropdown.querySelector('.nav-dropdown-toggle');
      dropdown.addEventListener('mouseenter', () => setOpen(dropdown, true));
      dropdown.addEventListener('mouseleave', () => setOpen(dropdown, false));
      toggle.addEventListener('click', event => {
        // Touch devices: first tap opens the menu, second tap follows the link.
        if (toggle.tagName === 'BUTTON' || (matchMedia('(hover: none)').matches && !dropdown.classList.contains('is-open'))) {
          event.preventDefault();
          const open = !dropdown.classList.contains('is-open');
          closeAll(dropdown);
          setOpen(dropdown, open);
        }
      });
      toggle.addEventListener('keydown', event => {
        if (event.key === 'ArrowDown') {
          event.preventDefault();
          setOpen(dropdown, true);
          dropdown.querySelector('.nav-dropdown-menu a')?.focus();
        }
      });
      dropdown.addEventListener('focusout', () => setTimeout(() => { if (!dropdown.contains(document.activeElement)) setOpen(dropdown, false); }));
    });
    document.addEventListener('click', event => { if (!event.target.closest('.nav-dropdown')) closeAll(); });
    document.addEventListener('keydown', event => {
      if (event.key !== 'Escape') return;
      const open = dropdowns.find(d => d.classList.contains('is-open'));
      if (open) { setOpen(open, false); open.querySelector('.nav-dropdown-toggle').focus(); }
    });
  }

  /* ---------- Reveal on scroll ---------- */
  function initReveal() {
    if (!('IntersectionObserver' in window) || reducedMotion.matches) return;
    document.body.classList.add('js-motion');
    const observer = new IntersectionObserver(entries => entries.forEach(entry => {
      if (entry.isIntersecting) { entry.target.classList.add('visible'); observer.unobserve(entry.target); }
    }), {threshold: .08});
    document.querySelectorAll('main section > .container-wide > .row > div, .charter-grid > article, .method-row, .office-layout > div, .detail-grid > *, .section-heading, .partners-heading').forEach(el => el.classList.add('reveal'));
    document.querySelectorAll('.service-card-grid, .certificates-grid, .charter-grid').forEach(grid => [...grid.children].forEach((el, index) => el.style.setProperty('--reveal-delay', `${(index % 4) * 80}ms`)));
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
  }

  /* ---------- Reading progress line ---------- */
  function initProgress() {
    const header = document.getElementById('header');
    if (!header) return;
    const progress = document.createElement('div');
    progress.className = 'reading-progress';
    progress.setAttribute('aria-hidden', 'true');
    header.append(progress);
    let queued = false;
    const update = () => {
      const distance = document.documentElement.scrollHeight - innerHeight;
      progress.style.transform = `scaleX(${distance > 0 ? Math.min(1, scrollY / distance) : 0})`;
      queued = false;
    };
    addEventListener('scroll', () => { if (!queued) { queued = true; requestAnimationFrame(update); } }, {passive: true});
    addEventListener('resize', update);
    update();
  }

  /* ---------- Hero slider (Swiper: autoplay, drag, arrows) ---------- */
  function initHeroSlider() {
    const hero = document.querySelector('[data-hero-slider]');
    if (!hero || !window.Swiper) return;
    const container = hero.querySelector('.hero-swiper');
    container.setAttribute('dir', isRtl() ? 'rtl' : 'ltr');
    const autoplay = hero.dataset.autoplay === '1' && !reducedMotion.matches;
    const swiper = new Swiper(container, {
      effect: hero.dataset.effect === 'slide' ? 'slide' : 'fade',
      fadeEffect: {crossFade: true},
      loop: hero.dataset.loop === '1',
      speed: 900,
      grabCursor: false,
      autoplay: autoplay ? {delay: Number(hero.dataset.delay) || 6000, disableOnInteraction: false, pauseOnMouseEnter: true} : false,
      keyboard: {enabled: true, onlyInViewport: true},
      a11y: {enabled: true},
      navigation: {nextEl: hero.querySelector('.hero-next'), prevEl: hero.querySelector('.hero-prev')},
      pagination: {el: hero.querySelector('.hero-pagination'), clickable: true, bulletClass: 'hero-dot', bulletActiveClass: 'is-active'},
      on: {
        slideChangeTransitionStart(s) {
          s.slides.forEach(slide => slide.querySelector('video')?.pause());
          s.slides[s.activeIndex]?.querySelector('video')?.play().catch(() => {});
        },
      },
    });
    document.addEventListener('visibilitychange', () => {
      if (!swiper.autoplay) return;
      document.hidden ? swiper.autoplay.stop() : autoplay && swiper.autoplay.start();
    });
  }

  /* ---------- Partners carousel (RTL-aware, drag, autoplay) ---------- */
  function initPartners() {
    const slider = document.querySelector('.partners-slider');
    if (!slider || !slider.firstElementChild) return;
    const section = slider.closest('.partners');
    const pauseButton = section.querySelector('.partner-pause');
    const dots = section.querySelector('.partner-dots');
    const delay = Number(slider.dataset.delay) || 3500;
    let paused = reducedMotion.matches || slider.dataset.autoplay !== '1';
    let lastInteraction = 0;
    let inView = false;
    let drag = null;
    let positions = [];
    const interacted = () => { lastInteraction = performance.now(); };
    const direction = () => isRtl() ? -1 : 1;
    const maxScroll = () => slider.scrollWidth - slider.clientWidth;
    const step = () => slider.firstElementChild.getBoundingClientRect().width + parseFloat(getComputedStyle(slider).gap || 0);
    const behavior = () => reducedMotion.matches ? 'instant' : 'smooth';
    const move = delta => {
      const max = maxScroll();
      const current = Math.abs(slider.scrollLeft);
      let target = current + delta * step();
      if (delta > 0 && current >= max - 2) target = 0;
      else if (delta < 0 && current <= 2) target = max;
      slider.scrollTo({left: direction() * Math.max(0, Math.min(max, target)), behavior: behavior()});
    };
    const updateDots = () => {
      const max = maxScroll();
      const count = Math.max(1, Math.ceil((max - 1) / step()) + 1);
      positions = Array.from({length: count}, (_, i) => Math.min(max, i * step()));
      if (dots.children.length !== count) {
        dots.replaceChildren(...positions.map((_, index) => {
          const button = document.createElement('button');
          button.type = 'button';
          button.className = 'partner-dot';
          button.setAttribute('aria-label', (dots.dataset.groupLabel || 'Group :n').replace(':n', index + 1));
          button.addEventListener('click', () => slider.scrollTo({left: direction() * positions[index], behavior: behavior()}));
          return button;
        }));
      }
      const current = Math.abs(slider.scrollLeft);
      const active = positions.reduce((best, position, i) => Math.abs(position - current) < Math.abs(positions[best] - current) ? i : best, 0);
      [...dots.children].forEach((dot, index) => dot.setAttribute('aria-current', String(index === active)));
    };
    const updatePause = () => {
      pauseButton.textContent = paused ? '▶' : 'Ⅱ';
      pauseButton.setAttribute('aria-pressed', String(paused));
      pauseButton.setAttribute('aria-label', paused ? pauseButton.dataset.labelPlay : pauseButton.dataset.labelPause);
    };
    section.querySelector('.partner-next').addEventListener('click', () => move(1));
    section.querySelector('.partner-prev').addEventListener('click', () => move(-1));
    pauseButton.addEventListener('click', () => { paused = !paused; updatePause(); });
    section.addEventListener('click', interacted);
    section.addEventListener('keydown', interacted);
    slider.addEventListener('pointerdown', interacted, {passive: true});
    slider.addEventListener('pointerup', interacted, {passive: true});
    slider.addEventListener('scroll', updateDots, {passive: true});
    slider.addEventListener('keydown', event => {
      if (!['ArrowLeft', 'ArrowRight'].includes(event.key)) return;
      event.preventDefault();
      move((event.key === 'ArrowRight' ? 1 : -1) * direction());
    });
    slider.addEventListener('pointerdown', event => {
      if (event.pointerType !== 'mouse' || event.button !== 0) return;
      drag = {x: event.clientX, scroll: slider.scrollLeft, moved: false};
      slider.setPointerCapture(event.pointerId);
      slider.classList.add('is-dragging');
    });
    slider.addEventListener('pointermove', event => {
      if (!drag) return;
      if (Math.abs(event.clientX - drag.x) > 4) drag.moved = true;
      slider.scrollLeft = drag.scroll - (event.clientX - drag.x);
    });
    slider.addEventListener('click', event => { if (slider.dataset.dragged === '1') { event.preventDefault(); slider.dataset.dragged = ''; } }, true);
    const endDrag = () => {
      if (drag?.moved) slider.dataset.dragged = '1';
      drag = null;
      slider.classList.remove('is-dragging');
    };
    slider.addEventListener('pointerup', endDrag);
    slider.addEventListener('pointercancel', endDrag);
    slider.addEventListener('lostpointercapture', endDrag);
    if ('IntersectionObserver' in window) new IntersectionObserver(entries => { inView = entries[0].isIntersecting; }, {threshold: .25}).observe(slider);
    else inView = true;
    setInterval(() => { if (!paused && !drag && inView && !document.hidden && performance.now() - lastInteraction >= delay) move(1); }, delay);
    reducedMotion.addEventListener('change', event => { paused = event.matches; updatePause(); });
    if ('ResizeObserver' in window) new ResizeObserver(updateDots).observe(slider);
    updatePause();
    updateDots();
  }

  /* ---------- Gallery lightbox (images + videos) ---------- */
  function initGallery() {
    const dialog = document.getElementById('galleryLightbox');
    if (!dialog) return;
    const image = document.getElementById('galleryLightboxImage');
    const videoBox = document.getElementById('galleryLightboxVideo');
    const reset = () => { if (videoBox) videoBox.replaceChildren(); image.hidden = false; };
    document.querySelectorAll('[data-gallery-src]').forEach(button => button.addEventListener('click', () => {
      reset();
      image.src = button.dataset.gallerySrc;
      image.alt = button.dataset.galleryAlt || '';
      dialog.showModal();
    }));
    document.querySelectorAll('[data-video-src]').forEach(button => button.addEventListener('click', () => {
      if (!videoBox) return;
      reset();
      image.hidden = true;
      let media;
      if (button.dataset.videoType === 'video') {
        media = document.createElement('video');
        Object.assign(media, {src: button.dataset.videoSrc, controls: true, autoplay: true, playsInline: true});
      } else {
        media = document.createElement('iframe');
        media.src = button.dataset.videoSrc + (button.dataset.videoSrc.includes('?') ? '&' : '?') + 'autoplay=1';
        media.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
        media.title = 'Video';
      }
      videoBox.append(media);
      dialog.showModal();
    }));
    dialog.querySelector('.gallery-close')?.addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', event => { if (event.target === dialog) dialog.close(); });
    dialog.addEventListener('close', reset);
  }

  /* ---------- Certificates dialog ---------- */
  function initCertificates() {
    const dialog = document.querySelector('.certificate-dialog:not(.pdf-dialog)');
    if (!dialog) return;
    document.querySelectorAll('[data-certificate]').forEach(card => card.addEventListener('click', () => {
      dialog.querySelector('img').src = card.dataset.certificate;
      dialog.showModal();
    }));
    bindDialogClose(dialog);
  }

  /* ---------- PDF viewer (custom pages) ---------- */
  function initPdfViewer() {
    const dialog = document.querySelector('.pdf-dialog');
    if (!dialog) return;
    const frame = dialog.querySelector('iframe');
    document.querySelectorAll('[data-pdf-src]').forEach(button => button.addEventListener('click', () => {
      frame.src = button.dataset.pdfSrc;
      frame.title = button.dataset.pdfTitle || 'PDF';
      dialog.showModal();
    }));
    dialog.addEventListener('close', () => { frame.src = 'about:blank'; });
    bindDialogClose(dialog);
  }

  function bindDialogClose(dialog) {
    dialog.querySelector('.certificate-close').addEventListener('click', () => dialog.close());
    dialog.addEventListener('click', event => {
      if (event.target !== dialog) return;
      const r = dialog.getBoundingClientRect();
      if (event.clientX < r.left || event.clientX > r.right || event.clientY < r.top || event.clientY > r.bottom) dialog.close();
    });
  }

  /* ---------- Careers: reveal the application form ---------- */
  function initCareers() {
    const section = document.getElementById('applicationSection');
    if (!section) return;
    document.querySelectorAll('.career-apply').forEach(button => button.addEventListener('click', () => {
      section.hidden = false;
      const select = document.getElementById('careerJob');
      if (select) {
        select.value = button.dataset.job;
        select.dispatchEvent(new Event('change', {bubbles: true}));
      }
      section.scrollIntoView({behavior: reducedMotion.matches ? 'auto' : 'smooth'});
      setTimeout(() => section.querySelector('input')?.focus({preventScroll: true}), 450);
    }));
  }

  /* ---------- Lead submitted (dispatched by Livewire forms) ---------- */
  addEventListener('fam-lead', event => {
    const detail = event.detail || {};
    if (typeof window.famTrack === 'function') window.famTrack(detail.type || 'contact');
    if (detail.open && detail.url) window.open(detail.url, '_blank', 'noopener,noreferrer');
  });
})();
