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

$(document).ready(function(){
    if(s!='')
      page = page+'_'+s;
        if($('.nav-link.nav-'+page).length > 0){
           $('.nav-link.nav-'+page).addClass('active')
            if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
          $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
                $('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
            }
      if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
        $('.nav-link.nav-'+page).parent().addClass('menu-open')
      }

        }
   
    });

document.addEventListener('DOMContentLoaded', () => {
  const sidebar = document.querySelector('.main-sidebar');
  let sidebarTimer;

  // Function to hide sidebar
  function hideSidebar() {
      sidebar.classList.add('hidden');
  }

  // Function to show sidebar
  function showSidebar() {
      sidebar.classList.remove('hidden');
  }

  // Show sidebar when mouse enters
  sidebar.addEventListener('mouseenter', showSidebar);

  // Hide sidebar when mouse leaves
  sidebar.addEventListener('mouseleave', () => {
      // Set a timer to hide sidebar after a short delay
      sidebarTimer = setTimeout(hideSidebar, 300); // Adjust the delay if needed
  });

  // Prevent hiding when mouse is inside the sidebar
  sidebar.addEventListener('mousemove', () => {
      clearTimeout(sidebarTimer); // Clear the timer
  });
});

document.getElementById('toggle-sidebar').addEventListener('click', function () {
  var sidebar = document.getElementById('sidebar');
  var contentWrapper = document.querySelector('.content-wrapper');
  var navbar = document.querySelector('.navbar');

  if (sidebar.classList.contains('sidebar-hidden')) {
      sidebar.classList.remove('sidebar-hidden');
      sidebar.classList.add('sidebar-visible');
      contentWrapper.classList.add('content-shifted');
      navbar.classList.add('navbar-shifted');  // Shift navbar to the right
  } else {
      sidebar.classList.remove('sidebar-visible');
      sidebar.classList.add('sidebar-hidden');
      contentWrapper.classList.remove('content-shifted');
      navbar.classList.remove('navbar-shifted');  // Move navbar back to its original position
  }
});



