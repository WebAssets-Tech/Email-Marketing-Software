/**
 * Maildoll Next-Gen Landing Page
 * Interactive Logic: Billing Switcher, Feature Tabs, FAQ Accordion, Navbar Scroll
 */

document.addEventListener('DOMContentLoaded', () => {
  // 1. Sticky Navbar on Scroll
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 40) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });

  // 2. Mobile Menu Toggle
  const mobileToggle = document.getElementById('mobile-toggle');
  const hamburgerIcon = mobileToggle ? mobileToggle.querySelector('.hamburger-icon') : null;
  const closeIcon = mobileToggle ? mobileToggle.querySelector('.close-icon') : null;

  if (mobileToggle) {
    mobileToggle.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = navbar.classList.toggle('mobile-open');
      mobileToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
      if (hamburgerIcon && closeIcon) {
        hamburgerIcon.style.display = isOpen ? 'none' : 'block';
        closeIcon.style.display = isOpen ? 'block' : 'none';
      }
    });
  }

  // Close mobile drawer on any nav link click
  document.querySelectorAll('.nav-link, .mobile-nav-actions a').forEach(link => {
    link.addEventListener('click', () => {
      if (navbar.classList.contains('mobile-open')) {
        navbar.classList.remove('mobile-open');
        if (mobileToggle) mobileToggle.setAttribute('aria-expanded', 'false');
        if (hamburgerIcon && closeIcon) {
          hamburgerIcon.style.display = 'block';
          closeIcon.style.display = 'none';
        }
      }
    });
  });

  // Close on outside click
  document.addEventListener('click', (e) => {
    if (navbar && navbar.classList.contains('mobile-open') && !navbar.contains(e.target)) {
      navbar.classList.remove('mobile-open');
      if (mobileToggle) mobileToggle.setAttribute('aria-expanded', 'false');
      if (hamburgerIcon && closeIcon) {
        hamburgerIcon.style.display = 'block';
        closeIcon.style.display = 'none';
      }
    }
  });

  // 3. Billing Switcher (Monthly vs Annual with 20% discount)
  const billingToggle = document.getElementById('billing-toggle');
  const labelMonthly = document.getElementById('label-monthly');
  const labelAnnual = document.getElementById('label-annual');
  const priceStarter = document.getElementById('price-starter');
  const pricePro = document.getElementById('price-pro');
  const priceEnterprise = document.getElementById('price-enterprise');
  const periodEls = document.querySelectorAll('.price-period');

  let isAnnual = false;

  const updatePricing = () => {
    if (isAnnual) {
      billingToggle.classList.add('annual');
      labelAnnual.classList.add('active');
      labelMonthly.classList.remove('active');
      if (priceStarter) priceStarter.textContent = '15';
      if (pricePro) pricePro.textContent = '39';
      if (priceEnterprise) priceEnterprise.textContent = '119';
      periodEls.forEach(el => el.textContent = '/mo (billed annually)');
    } else {
      billingToggle.classList.remove('annual');
      labelMonthly.classList.add('active');
      labelAnnual.classList.remove('active');
      if (priceStarter) priceStarter.textContent = '19';
      if (pricePro) pricePro.textContent = '49';
      if (priceEnterprise) priceEnterprise.textContent = '149';
      periodEls.forEach(el => el.textContent = '/month');
    }
  };

  if (billingToggle) {
    billingToggle.addEventListener('click', () => {
      isAnnual = !isAnnual;
      updatePricing();
    });
  }
  if (labelMonthly) {
    labelMonthly.addEventListener('click', () => {
      isAnnual = false;
      updatePricing();
    });
  }
  if (labelAnnual) {
    labelAnnual.addEventListener('click', () => {
      isAnnual = true;
      updatePricing();
    });
  }

  // 4. Feature Showcase Tab Switcher
  const tabButtons = document.querySelectorAll('.tab-btn');
  const tabContents = document.querySelectorAll('.tab-content');

  tabButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const targetId = btn.getAttribute('data-tab');

      // Update active button
      tabButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      // Update active content
      tabContents.forEach(content => {
        if (content.id === targetId) {
          content.classList.add('active');
        } else {
          content.classList.remove('active');
        }
      });
    });
  });

  // 5. Interactive FAQ Accordion
  const faqCards = document.querySelectorAll('.faq-item, .faq-card');

  faqCards.forEach(card => {
    const question = card.querySelector('.faq-question');
    question.addEventListener('click', () => {
      const isOpen = card.classList.contains('open');

      // Close all cards first (accordion style)
      faqCards.forEach(c => c.classList.remove('open'));

      // If it wasn't open, open it
      if (!isOpen) {
        card.classList.add('open');
      }
    });
  });

  // 6. Smooth Scroll for Anchor Links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
      const targetId = this.getAttribute('href');
      if (targetId === '#') return;
      
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        e.preventDefault();
        navbar.classList.remove('mobile-open');
        targetElement.scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }
    });
  });
});
