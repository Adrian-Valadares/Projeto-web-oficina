//animacao home
document.addEventListener("DOMContentLoaded", () => {
  const home = document.querySelector(".home");

  // pega todos os elementos que queremos animar dentro da home-content
  const items = document.querySelectorAll(
    ".home-content h3, .home-content h1, .home-content p, .home-img img"
  );

  // estado inicial
  items.forEach((el) => {
    el.style.opacity = "0";
    el.style.transform = "translateY(40px)";
    el.style.transition = "all 0.8s ease";
  });

  const animate = () => {
    items.forEach((el, index) => {
      setTimeout(() => {
        el.style.opacity = "1";
        el.style.transform = "translateY(0)";
      }, index * 200); // efeito cascata suave
    });
  };

  const reset = () => {
    items.forEach((el) => {
      el.style.opacity = "0";
      el.style.transform = "translateY(40px)";
    });
  };

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        animate();
      } else {
        reset(); // reinicia se sair da tela
      }
    });
  }, {
    threshold: 0.3
  });

  observer.observe(home);
});


//animacao servicos
document.addEventListener("DOMContentLoaded", () => {
  const cards = document.querySelectorAll(".card");

  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {

      if (entry.isIntersecting) {

        // animação com leve delay individual
        const index = [...cards].indexOf(entry.target);

        setTimeout(() => {
          entry.target.classList.add("show");
        }, index * 200);

      } else {
        // reseta ao sair da tela
        entry.target.classList.remove("show");
      }

    });
  }, {
    threshold: 0.2
  });

  cards.forEach(card => observer.observe(card));
});

// botao flutuante
window.addEventListener("load", () => {
  const btn = document.querySelector(".whatsapp-container");

  btn.style.opacity = "0";

  setTimeout(() => {
    btn.style.opacity = "1";
    btn.style.animation = "whatsappEntry 0.8s ease-out";
  }, 1950);
});

// menu toggle
const menuIcon = document.getElementById("menu-icon");
const navMenu = document.getElementById("nav-menu");
const navLinks = document.querySelectorAll(".nav a");

// abre/fecha no ícone
menuIcon.addEventListener("click", () => {
  navMenu.classList.toggle("active");
  menuIcon.classList.toggle("active");
});

// fecha ao clicar em qualquer link
navLinks.forEach(link => {
  link.addEventListener("click", () => {
    navMenu.classList.remove("active");
    menuIcon.classList.remove("active");
  });
});