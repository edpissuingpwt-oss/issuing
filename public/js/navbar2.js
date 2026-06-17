$(function () {

  var hoverTimer;

  /* =========================
     LEVEL 1 - MAIN DROPDOWN
  ========================= */
  $(document).on('mouseenter', '.navbar-modern .nav-item.dropdown', function () {
    clearTimeout(hoverTimer);

    var $this = $(this);

    $('.navbar-modern .nav-item.dropdown.show').not($this)
      .removeClass('show')
      .find('.dropdown-menu').removeClass('show');

    $this.addClass('show');
    $this.children('.dropdown-menu').addClass('show');
    $this.find('[data-toggle="dropdown"]').attr('aria-expanded', 'true');
  });

  $(document).on('mouseleave', '.navbar-modern .nav-item.dropdown', function () {
    var $this = $(this);

    hoverTimer = setTimeout(function () {
      $this.removeClass('show');
      $this.find('.dropdown-menu').removeClass('show');
      $this.find('[data-toggle="dropdown"]').attr('aria-expanded', 'false');
    }, 150);
  });

  /* =========================
     LEVEL 2 - SUB DROPDOWN
  ========================= */
  $(document).on('mouseenter', '.dropdown-submenu', function () {
    $(this).children('.dropdown-menu').addClass('show');
  });

  $(document).on('mouseleave', '.dropdown-submenu', function () {
    $(this).children('.dropdown-menu').removeClass('show');
  });

  /* ========================= */
  $(document).on('mouseenter', '.navbar-modern .dropdown-menu', function () {
    clearTimeout(hoverTimer);
  });

  /* =========================
     CLICK SUPPORT
  ========================= */
  $(document).on('click', '.navbar-modern [data-toggle="dropdown"]', function (e) {
    e.preventDefault();
    e.stopPropagation();

    var $parent = $(this).closest('.nav-item.dropdown');
    var isOpen  = $parent.hasClass('show');

    $('.navbar-modern .nav-item.dropdown.show')
      .removeClass('show')
      .find('.dropdown-menu').removeClass('show');

    if (!isOpen) {
      $parent.addClass('show');
      $parent.children('.dropdown-menu').addClass('show');
    }
  });

  /* =========================
     CLICK SUBMENU
  ========================= */
  $(document).on('click', '.dropdown-submenu > a', function (e) {
    e.preventDefault();
    e.stopPropagation();

    let submenu = $(this).next('.dropdown-menu');

    $('.dropdown-submenu .dropdown-menu').not(submenu).removeClass('show');
    submenu.toggleClass('show');
  });

  /* =========================
     CLICK OUTSIDE
  ========================= */
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.navbar-modern .dropdown').length) {
      $('.navbar-modern .dropdown.show')
        .removeClass('show')
        .find('.dropdown-menu').removeClass('show');
    }
  });

  /* =========================
     SCROLL
  ========================= */
  var $nav = $('#mainNavbarModern');
  $(window).on('scroll', function () {
    $nav.toggleClass('scrolled', $(this).scrollTop() > 20);
  });

  /* =========================
     ACTIVE LINK
  ========================= */
  var path = window.location.pathname;
  $('.navbar-modern .nav-link[href]').each(function () {
    var href = $(this).attr('href');
    if (!href || href === '#') return;
    try {
      var lp = new URL(href, location.origin).pathname;
      if (lp.length > 1 && path.startsWith(lp)) $(this).addClass('active');
    } catch (_) {}
  });

  /* =========================
     TEMA
  ========================= */
  function setTheme(t) {
    document.documentElement.setAttribute('data-theme', t);
    localStorage.setItem('theme', t);

    var icon = document.getElementById('themeIcon');
    if (!icon) return;

    icon.style.transform = 'rotate(180deg)';
    setTimeout(function () {
      icon.src = t === 'dark'
        ? '/sun.png'
        : '/moon.png';
      icon.style.transform = 'rotate(0deg)';
    }, 150);
  }

  var saved = localStorage.getItem('theme');
  setTheme(saved || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'));

  $('#themeToggle').on('click', function (e) {
    e.preventDefault();
    setTheme(document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
  });

});