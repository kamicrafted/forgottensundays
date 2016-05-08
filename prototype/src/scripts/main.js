$(function() {
  var $header = $('.site-header');
  var $content = $('.site-content');
  var $headerHeight = $header.height();
  console.log($headerHeight);

  $(window).scroll(function() {
    console.log($(this).scrollTop());
    if ( $(this).scrollTop() <= 150 && $header.hasClass('site-header--compact') ) {
      $header.stop().velocity({top: 0}, 50);
      $header.removeClass('site-header--compact');
      $content.css('margin-top', 50);
    }
    if ( $(this).scrollTop() > 250) {
      if (!$header.hasClass('site-header--compact')) {
        $header.addClass('site-header--compact');
        $header.stop().velocity({top: -200}, 0).stop().velocity({top: 0}, 100);
        $content.css('margin-top', $headerHeight + 50);
      }
    }
    if ( $(this).scrollTop() < 250) {
      if ($header.hasClass('site-header--compact')) {
        $header.stop().velocity({top: -150}, 50);
      }
    }
  });
});

