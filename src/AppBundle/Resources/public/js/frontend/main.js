/*==============================================================
    wow animation - on scroll
 ==============================================================*/

var wow = new WOW({
    boxClass: 'wow',
    animateClass: 'animated',
    offset: 90,
    mobile: true,
    live: true
});
wow.init();

(function( $ ) {
	$('.navbar-collapse a').click(function(){
		$(".navbar-collapse").collapse('hide');
	});
	$('.carousel').carousel({
        interval: 5000 //changes the speed
    })

    jQuery("input.telefone")
        .mask("(99) 9999-9999?9")
        .focusout(function (event) {
            var target, phone, element;
            target = (event.currentTarget) ? event.currentTarget : event.srcElement;
            phone = target.value.replace(/\D/g, '');
            element = $(target);
            element.unmask();
            if(phone.length > 10) {
                element.mask("(99) 99999-999?9");
            } else {
                element.mask("(99) 9999-9999?9");
            }
        });

    $('#owl-testimonial').owlCarousel({
      loop:true,
      autoplay: true,
      navSpeed: 1000,
      autoplayTimeout: 9000,
      nav:true,
      touchDrag: true,
      mouseDrag: true,
      navText: [
        "<i><img src='../bundles/app/img/frontend/arrow-left.png'></i>",
        "<i><img src='../bundles/app/img/frontend/arrow-right.png'></i>"
      ],
      responsive:{
        0:{
          items:1
        },
        600:{
          items:1,
        },
        768:{
          items:1,
        },
        1000:{
          items:1
        }
      }
    })
    window.setTimeout(function() {

        $(".custom-alert").slideUp(500, function() {
            $(this).remove();
        });
    }, 3000);


})(jQuery);
