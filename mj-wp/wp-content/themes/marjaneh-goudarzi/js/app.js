$(document).foundation()
$(document).ready(function() {
  $('.slide-show').slick({
    autoplay: true,
    arrows: true,
    infinite: true,
    speed: 300,
    fade: true,
    cssEase: 'linear'
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
});
