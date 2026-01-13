window.addEventListener('DOMContentLoaded', () => {
    console.log('DOMContentLoaded');
  const mainNav = document.querySelector('#mainNav');
  const navbarResponsive = document.querySelector('#navbarResponsive');
  const navbarToggler = document.querySelector('.navbar-toggler');

  if (!mainNav || !navbarResponsive) return;

  const isMobileTogglerVisible = () => {
    if (!navbarToggler) return false;
    return window.getComputedStyle(navbarToggler).display !== 'none';
  };

  navbarResponsive.addEventListener('show.bs.collapse', () => {
    if (isMobileTogglerVisible()) mainNav.classList.add('navbar-expanded');
  });

  navbarResponsive.addEventListener('hidden.bs.collapse', () => {
    mainNav.classList.remove('navbar-expanded');
  });
});
