// Scroll animation activation
window.addEventListener('scroll', () => {
  const elements = document.querySelectorAll('.fade-in, .fade-in-left, .fade-in-right, .fade-in-up');
  elements.forEach(el => {
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight - 100) {
      el.style.animationPlayState = 'running';
    }
  });
});

// Active link highlighting
const navLinks = document.querySelectorAll('.nav-link');
window.addEventListener('scroll', () => {
  let fromTop = window.scrollY + 200;
  navLinks.forEach(link => {
    const section = document.querySelector(link.getAttribute('href'));
    if (section.offsetTop <= fromTop && section.offsetTop + section.offsetHeight > fromTop) {
      link.classList.add('active');
    } else {
      link.classList.remove('active');
    }
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const fadeEls = document.querySelectorAll(".fade-in, .fade-in-left, .fade-in-right");

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.animationPlayState = "running";
        entry.target.classList.add("visible");
      }
    });
  }, { threshold: 0.1 });

  fadeEls.forEach(el => {
    el.style.animationPlayState = "paused";
    observer.observe(el);
  });
});
