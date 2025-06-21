document.addEventListener('DOMContentLoaded', function() {
    // Funcionalidad para el menú móvil
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const body = document.body;

    // No mostar el envio de formulario
    history.replaceState(null, null, location.pathname);
    
    if (mobileMenuBtn) {
      mobileMenuBtn.addEventListener('click', function() {
        // Crear el menú móvil si no existe
        let mobileMenu = document.querySelector('.mobile-menu');
        
        if (!mobileMenu) {
          mobileMenu = document.createElement('div');
          mobileMenu.className = 'mobile-menu';
          
          // Botón para cerrar
          const closeBtn = document.createElement('button');
          closeBtn.className = 'mobile-menu-close';
          closeBtn.innerHTML = '<i class="fas fa-times"></i>';
          closeBtn.addEventListener('click', function() {
            mobileMenu.classList.remove('active');
            body.style.overflow = '';
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
    const revealOnScroll = function() {
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
  });  