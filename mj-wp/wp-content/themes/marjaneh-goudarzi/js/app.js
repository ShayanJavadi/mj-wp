$(document).foundation();
$(document).ready(function() {
  $('.slide-show').slick({
    autoplay: true,
    arrows: true,
    infinite: true,
    speed: 500,
    fade: true,
    cssEase: 'linear'
  });

  //sticky nav
  $('header').waypoint(function(direction) {
      if (direction == "down") {
          $('nav').addClass('sticky');
          $('nav').addClass('fadein-fast');
      } else {
          $('nav').removeClass('sticky');
          $('nav').removeClass('fadein-fast');
      }
  }, {
    offset: '-145px;'
  });


  $('.js-lesson-2').waypoint(function(direction) {
      $('.js-lesson-2').addClass('animated fadeIn');
  }, {
      offset: '50%'
  });

  $('.js-lesson-3').waypoint(function(direction) {
      $('.js-lesson-3').addClass('animated fadeIn');
  }, {
      offset: '50%'
  });

  $('.js--nav-icon').click(function() {
      var nav = $('.js--main-nav');
      var icon = $('.js--nav-icon i');

      nav.slideToggle(200);

      if ( icon.hasClass('fa-bars')) {
          icon.addClass(' fa-times');
          icon.removeClass(' fa-bars');
      } else {
          icon.addClass(' fa-bars');
          icon.removeClass(' fa-times');
      }
  });

});
