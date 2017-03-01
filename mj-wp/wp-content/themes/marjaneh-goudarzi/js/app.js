$(document).foundation();
$(document).ready(function() {

  //change the color of navbar if the page doenst have a jumbo picture at the top
  //so that the text is visible
  var hasSectionSlide = $('.section-slide-show');
  if (hasSectionSlide.length === 0) {
    $('header').addClass('nav-no-slide-show');
    console.log('works');
  }


  $('.slide-show').slick({
    autoplay: true,
    arrows: true,
    infinite: true,
    speed: 500,
    fade: true,
    cssEase: 'linear'
  });

  $('header').waypoint(function(direction) {
    //if cart is full and it is the right window size
    if ($(window).width() < 845 && !($('.absolute-cart-box').is(':empty'))) {
      if (direction == "down") {
        $('.absolute-cart-box').hide();
      } else {
        $('.absolute-cart-box').show();
      }
    }
  }, {
    offset: '-25px;'
  });


  //sticky nav
  $('body').waypoint(function(direction) {
      if (direction == "down") {
          $('nav').addClass('sticky');
          $('nav').addClass('fadein-fast');
          //if cart is full and it is the right window size
          if ($(window).width() < 845 && !($('.absolute-cart-box').is(':empty'))) {
            $('.absolute-cart-box').show();
          }
      } else {
          $('nav').removeClass('sticky');
          $('nav').removeClass('fadein-fast');
          //if cart is full and it is the right window size
          if ($(window).width() < 845 && !($('.absolute-cart-box').is(':empty'))) {
            $('.absolute-cart-box').hide();
          }
      }
  }, {
    offset: '-205px;'
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
      var slider = $('.js--slider');
      var icon = $('.js--nav-icon i');

      nav.slideToggle(200);

      if ( icon.hasClass('fa-bars')) {
          slider.addClass('hide');
          icon.addClass(' fa-times');
          icon.removeClass(' fa-bars');
      } else {
          slider.removeClass('hide');
          icon.addClass(' fa-bars');
          icon.removeClass(' fa-times');
      }
  });

});
