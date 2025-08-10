(function(){
  const nav = document.getElementById('mainNav');
  const onScroll = () => {
    if (!nav) return;
    if (window.scrollY > 24) nav.classList.add('scrolled');
    else nav.classList.remove('scrolled');
  };
  document.addEventListener('scroll', onScroll, { passive: true });
  onScroll();

  if (window.AOS) { AOS.init({ once: true, duration: 700, easing: 'ease-out-cubic' }); }

  if (window.gsap && window.ScrollTrigger) {
    const bg = document.querySelector('.hero .hero-bg');
    if (bg) {
      gsap.to(bg, { yPercent: 12, ease: 'none', scrollTrigger: { trigger: '.hero', start: 'top top', end: 'bottom top', scrub: true } });
    }
  }

  const counters = document.querySelectorAll('[data-counter]');
  counters.forEach(el => {
    const target = parseInt(el.getAttribute('data-counter') || '0', 10);
    let current = 0; const step = Math.max(1, Math.round(target / 120));
    const tick = () => {
      current += step; if (current >= target) { current = target; }
      el.textContent = current.toString();
      if (current < target) requestAnimationFrame(tick);
    };
    const io = new IntersectionObserver((entries) => {
      entries.forEach(e => { if (e.isIntersecting) { tick(); io.disconnect(); } });
    }, { threshold: 0.4 });
    io.observe(el);
  });

  const stars = document.querySelectorAll('[data-stars]');
  stars.forEach(el => {
    const n = parseInt(el.getAttribute('data-stars') || '0', 10);
    el.innerHTML = '★★★★★'.slice(0, n).split('').map(s => `<span class="star">${s}</span>`).join('');
  });
})();
