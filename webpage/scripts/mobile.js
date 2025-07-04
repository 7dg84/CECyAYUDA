document.addEventListener('DOMContentLoaded', function () {
  // Funcionalidad para el menú móvil
  const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
  const body = document.body;

  // No mostar el envio de formulario
  history.replaceState(null, null, location.pathname);

  if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', function () {
      // Crear el menú móvil si no existe
      let mobileMenu = document.querySelector('.mobile-menu');

      if (!mobileMenu) {
        mobileMenu = document.createElement('div');
        mobileMenu.className = 'mobile-menu';

        // Botón para cerrar
        const closeBtn = document.createElement('button');
        closeBtn.className = 'mobile-menu-close';
        closeBtn.innerHTML = '<i class="fas fa-times"></i>';
        closeBtn.addEventListener('click', function () {
          mobileMenu.classList.remove('active');
          body.style.overflow = '';
        });

        // Boton para tema
        const themeBtn = document.createElement('div');
        themeBtn.className = "toggle-theme";
        themeBtn.innerHTML = '<i class="fa-solid fa-moon"></i><p>Tema</p>';
        themeBtn.style.display = 'flex';
        themeBtn.style.margin = '1rem';
        themeBtn.style.alignItems = 'center';
        themeBtn.children[0].style.marginRight = '2rem';
    // Cargar el logo correcto del tema
    if (document.body.classList.contains('dark-mode')) {
      localStorage.setItem('theme', 'dark');
      themeBtn.children[0].className = "fa-solid fa-sun";
      themeBtn.children[1].textContent = "Tema Claro";
    } else {
      localStorage.setItem('theme', 'light');
      themeBtn.children[0].className = "fa-solid fa-moon";
      themeBtn.children[1].textContent = "Tema Oscuro";
    }
    themeBtn.addEventListener('click', function () {
      document.body.classList.toggle('dark-mode');
      if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('theme', 'dark');
        themeBtn.children[0].className = "fa-solid fa-sun";
        themeBtn.children[1].textContent = "Tema Claro";
      } else {
        localStorage.setItem('theme', 'light');
        themeBtn.children[0].className = "fa-solid fa-moon";
        themeBtn.children[1].textContent = "Tema Oscuro";
      }
    });

    // Agregar enlaces
    const links = [
      { href: 'index.html', text: 'Inicio', active: true },
      { href: 'info.html', text: 'Información' },
      { href: 'reportar.html', text: 'Reportar' },
      { href: 'consultar.php', text: 'Consultar reportes' },
      { href: 'recursos.html', text: 'Recursos' },
      { href: 'sobre-nosotros.html', text: 'Sobre Nosotros' },
    ];

    mobileMenu.appendChild(closeBtn);

    links.forEach(link => {
      const a = document.createElement('a');
      a.href = link.href;
      a.textContent = link.text;
      if (link.active) a.classList.add('active');
      if (link.className) a.classList.add(link.className);
      mobileMenu.appendChild(a);
    });

    mobileMenu.appendChild(themeBtn);
    document.body.appendChild(mobileMenu);
  }

  // Mostrar/ocultar el menú
  mobileMenu.classList.toggle('active');

  // Evitar scroll en el body cuando el menú está abierto
  if (mobileMenu.classList.contains('active')) {
    body.style.overflow = 'hidden';
  } else {
    body.style.overflow = '';
  }
});
  }

// Añadir animación de entrada a los elementos
const animateElements = document.querySelectorAll('.hero-title, .hero-text, .hero-buttons');
animateElements.forEach((element, index) => {
  setTimeout(() => {
    element.style.opacity = '1';
    element.style.transform = 'translateY(0)';
  }, index * 200);
});

// Agregar efectos de scroll
const revealOnScroll = function () {
  const cardElements = document.querySelectorAll('.service-card');

  cardElements.forEach(card => {
    const cardTop = card.getBoundingClientRect().top;
    const windowHeight = window.innerHeight;

    if (cardTop < windowHeight - 100) {
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }
  });
};

// Inicializar las cards con opacidad 0
const cards = document.querySelectorAll('.service-card');
cards.forEach(card => {
  card.style.opacity = '0';
  card.style.transform = 'translateY(20px)';
  card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
});

// Comprobar posición inicial y luego en scroll
revealOnScroll();
window.addEventListener('scroll', revealOnScroll);

// animacion de entrada de los elementos del formulario
const formElements = document.querySelectorAll('form');

formElements.forEach((element, index) => {
  element.style.opacity = '0';
  element.style.transform = 'translateY(20px)';
  element.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

  setTimeout(() => {
    element.style.opacity = '1';
    element.style.transform = 'translateY(0)';
  }, index * 200);
});

// Animación de entrada para el footer
const footer = document.querySelector('footer');
footer.style.opacity = '0';
footer.style.transform = 'translateY(20px)';
footer.style.transition = 'opacity 0.5s ease, transform 0.5s ease';

setTimeout(() => {
  footer.style.opacity = '1';
  footer.style.transform = 'translateY(0)';
}, 500);

// Cargar el logo correcto del tema
if (document.body.classList.contains('dark-mode')) {
  localStorage.setItem('theme', 'dark');
  document.getElementById('toggle-theme').children[0].className = "fa-solid fa-sun";
} else {
  localStorage.setItem('theme', 'light');
  document.getElementById('toggle-theme').children[0].className = "fa-solid fa-moon";
}

});

// Animacion para el logo de la navbar
const text = document.getElementsByClassName('navbar-logo')[0];
let visible = false;

setInterval(() => {
  visible = !visible;
  text.style.color = visible ? '#E5DEFF' : '#9b87f5';
  text.style.transition = 'opacity 0.5s ease, color 0.5s ease';
}, 5000);

// Funcion para Modo Oscuro
document.getElementById('toggle-theme').onclick = function () {
  document.body.classList.toggle('dark-mode');
  // Opcional: guarda la preferencia en localStorage
  if (document.body.classList.contains('dark-mode')) {
    localStorage.setItem('theme', 'dark');
    document.getElementById('toggle-theme').children[0].className = "fa-solid fa-sun";
  } else {
    localStorage.setItem('theme', 'light');
    document.getElementById('toggle-theme').children[0].className = "fa-solid fa-moon";
  }
};
// Al cargar la página, aplica la preferencia guardada
if (localStorage.getItem('theme') === 'dark') {
  document.body.classList.add('dark-mode');
}