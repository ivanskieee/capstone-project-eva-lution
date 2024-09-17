document.addEventListener("DOMContentLoaded", function () {
    let lockIcon = document.getElementById("lock-icon");
    let password = document.getElementById("password");

    lockIcon.addEventListener("click", function () {
        if (password.type === "password") {
            password.type = "text";
            lockIcon.classList.remove("fa-lock");
            lockIcon.classList.add("fa-lock-open");
        } else {
            password.type = "password";
            lockIcon.classList.remove("fa-lock-open");
            lockIcon.classList.add("fa-lock");
        }
    });
});

document.getElementById('login-form').addEventListener('submit', function (event) {
    event.preventDefault(); 
    document.getElementById('loading-spinner').style.display = 'block'; 

    setTimeout(() => {
        event.target.submit();
    }, 1000); 
});

document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.querySelector('.flash-message');
    if (flashMessage) {
        setTimeout(() => {
            flashMessage.style.transition = 'opacity 0.5s ease';
            flashMessage.style.opacity = '0';
            setTimeout(() => flashMessage.remove(), 500);
        }, 2000);
    }
});

document.addEventListener('scroll', function () {
    const parallax = document.querySelector('.parallax-image');
    const scrollPosition = window.scrollY;
    parallax.style.transform = 'translateY(' + scrollPosition * 0.3 + 'px)';
});

document.addEventListener('scroll', function () {
    const fadeElements = document.querySelectorAll('.fade-in');
    const windowHeight = window.innerHeight;

    fadeElements.forEach(function (element) {
        const elementTop = element.getBoundingClientRect().top;

        if (elementTop < windowHeight * 0.75) { // Adjust the threshold as needed
            element.classList.add('visible');
        }
    });
});



