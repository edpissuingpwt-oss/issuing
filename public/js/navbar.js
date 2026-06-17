$(function () {

  var hoverTimer;

  /* =========================
     LEVEL 1 (HOVER OK)
  ========================= */
  $(document).on('mouseenter', '.navbar-modern .nav-item.dropdown', function () {
    clearTimeout(hoverTimer);

    let $this = $(this);

    $('.navbar-modern .nav-item.dropdown.show').not($this)
      .removeClass('show')
      .find('.dropdown-menu').removeClass('show');

    $this.addClass('show');
    $this.children('.dropdown-menu').addClass('show');
  });

  $(document).on('mouseleave', '.navbar-modern .nav-item.dropdown', function () {
    let $this = $(this);

    hoverTimer = setTimeout(function () {
      $this.removeClass('show');
      $this.find('.dropdown-menu').removeClass('show');
    }, 150);
  });

  /* =========================
     SUBMENU (CLICK ONLY)
  ========================= */
  $(document).on('click', '.dropdown-submenu > a', function (e) {
    e.preventDefault();
    e.stopPropagation();

    let $submenu = $(this).next('.dropdown-menu');

    // Tutup submenu lain
    $('.dropdown-submenu .dropdown-menu').not($submenu).removeClass('show');

    // Toggle
    $submenu.toggleClass('show');
  });

  /* =========================
     PREVENT CLOSE
  ========================= */
  $(document).on('mouseenter', '.navbar-modern .dropdown-menu', function () {
    clearTimeout(hoverTimer);
  });

  /* =========================
     CLICK OUTSIDE
  ========================= */
  $(document).on('click', function (e) {
    if (!$(e.target).closest('.navbar-modern .dropdown').length) {
      $('.dropdown-menu').removeClass('show');
    }
  });

});