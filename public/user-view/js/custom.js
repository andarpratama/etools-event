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

  const handleNavbarScroll = () => {
    if (mainNav.classList.contains('navbar-shrink')) {
      const lightLogo = mainNav.querySelector('.navbar-logo-light');
      const darkLogo = mainNav.querySelector('.navbar-logo-dark');
      if (lightLogo) lightLogo.style.display = 'none';
      if (darkLogo) darkLogo.style.display = 'inline-block';
    } else {
      const lightLogo = mainNav.querySelector('.navbar-logo-light');
      const darkLogo = mainNav.querySelector('.navbar-logo-dark');
      if (lightLogo) lightLogo.style.display = 'inline-block';
      if (darkLogo) darkLogo.style.display = 'none';
    }
  };

  const observer = new MutationObserver(handleNavbarScroll);
  observer.observe(mainNav, { attributes: true, attributeFilter: ['class'] });
  handleNavbarScroll();

  loadTools();
});

async function loadTools() {
  const container = document.getElementById('tools-container');
  if (!container) return;

  try {
    const response = await fetch('/api/tools');
    const tools = await response.json();

    container.innerHTML = '';

    if (tools.length === 0) {
      container.innerHTML = '<div class="col-12 text-center"><p class="text-muted">Tidak ada alat sewa tersedia.</p></div>';
      return;
    }

    tools.forEach(tool => {
      const price = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0
      }).format(tool.price);

      const badgeClass = getBadgeClass(tool.badge_color);
      
      let imageHtml = '';
      if (tool.images && tool.images.length > 0) {
        imageHtml = `<div id="carousel-${tool.id}" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">`;
        tool.images.forEach((img, idx) => {
          const mediaType = img.type || 'image';
          if (mediaType === 'video') {
            imageHtml += `<div class="carousel-item ${idx === 0 ? 'active' : ''}">
              <video src="${img.image_url}" class="d-block w-100 card-img-top" style="height: 200px; object-fit: cover;" controls></video>
            </div>`;
          } else {
            imageHtml += `<div class="carousel-item ${idx === 0 ? 'active' : ''}">
              <img src="${img.image_url}" class="d-block w-100 card-img-top" alt="${tool.name}" style="height: 200px; object-fit: cover;">
            </div>`;
          }
        });
        imageHtml += `</div>`;
        if (tool.images.length > 1) {
          imageHtml += `<button class="carousel-control-prev" type="button" data-bs-target="#carousel-${tool.id}" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carousel-${tool.id}" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>`;
        }
        imageHtml += `</div>`;
      } else {
        imageHtml = `<img src="${tool.image_url || 'https://via.placeholder.com/300'}" class="card-img-top" alt="${tool.name}" style="height: 200px; object-fit: cover;">`;
      }
      
      const toolCard = `
        <div class="col-lg-3 col-md-6 mb-4">
          <div class="card h-100 pb-3">
            ${imageHtml}
            <div class="card-body">
              <span class="badge ${badgeClass} mb-2">${tool.category}</span>
              <h6 class="card-title mt-2">${tool.name}</h6>
              <p class="text-muted small mb-1">${tool.description || ''}</p>
              <h5 class="text-primary fw-bold">${price}</h5>
            </div>
            <div class="card-footer bg-transparent border-0 text-center">
              <a href="#" class="btn btn-outline-primary btn-sm">Sewa Sekarang</a>
            </div>
          </div>
        </div>
      `;
      
      container.innerHTML += toolCard;
    });
  } catch (error) {
    console.error('Error loading tools:', error);
    container.innerHTML = '<div class="col-12 text-center"><p class="text-danger">Gagal memuat data alat sewa.</p></div>';
  }
}

function getBadgeClass(color) {
  const colorMap = {
    'primary': 'bg-primary',
    'warning': 'bg-warning text-dark',
    'success': 'bg-success',
    'danger': 'bg-danger',
    'info': 'bg-info',
    'secondary': 'bg-secondary'
  };
  return colorMap[color] || 'bg-primary';
}
