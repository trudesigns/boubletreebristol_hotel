

(function ($) {
    'use strict';

  // nice select
  $('.language select').niceSelect();


//============================== header =========================

 // sticky-menu
  var navbar = $('#navbar');
  var mainWrapper = $('.main-wrapper');
  var sticky = navbar.offset().top;
  $(window).scroll(function () {
    if ($(document).scrollTop() >= sticky) {
      navbar.addClass('sticky');
      mainWrapper.addClass('main-wrapper-section');
    } else {
      navbar.removeClass('sticky');
      mainWrapper.removeClass('main-wrapper-section');
    }
  });


 $('.hero-slider').slick({
        autoplay: true,
        infinite: true,
        arrows: false,
        dots: true,
        autoplaySpeed: 7000,
        pauseOnFocus: false,
        pauseOnHover: false
    });
    $('.hero-slider').slickAnimation();



	$('.testimonial-carousel').slick({
		slidesToShow: 1,
		infinite: true,
		arrows: false,
		autoplay: true,
		autoplaySpeed: 2000
	});




	//============================== Date-picker =========================
	
       $('.tp-datepicker').datepicker({
            startDate: 'dateToday',
            autoclose: true
        });

	// Init Magnific Popup
	$('.gallery-item').magnificPopup({
		delegate: 'a',
		type: 'image',
		gallery: {
			enabled: true
		},
		mainClass: 'mfp-with-zoom',
		navigateByImgClick: true,
		arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
		tPrev: 'Previous (Left arrow key)',
		tNext: 'Next (Right arrow key)',
		tCounter: '<span class="mfp-counter">%curr% of %total%</span>',
		zoom: {
			enabled: true,
			duration: 300,
			easing: 'ease-in-out',
			opener: function (openerElement) {
				return openerElement.is('img') ? openerElement : openerElement.find('img');
			}
		}
	});



	
	//============================== Booking bar =========================

	$('.ed-booking-tab  ul > li:nth-child(1)').click(function () {
		var $this = $(this);
		if ($this.hasClass('ed-done')) {
			$this.removeClass('ed-done').siblings().removeClass('ed-done');
		}
	});
	$('.ed-booking-tab  ul > li:nth-child(2)').click(function () {
		var $this = $(this);
		$this.prev('li').addClass('ed-done');
		$this.next('li').removeClass('ed-done');
		if ($this.hasClass('ed-done')) {
			$this.removeClass('ed-done');
		}
	});
	$('.ed-booking-tab  ul > li:nth-child(3)').click(function () {
		$(this).siblings().addClass('ed-done');

		if ($('.ed-booking-tab  ul > li:nth-child(3)').hasClass('ed-done')) {
			$(this).removeClass('ed-done');
		}
	});


	//============================== Select room =========================
	$('.ed-room-select').click(function (e) {
		e.preventDefault();
		$(this).children().toggleClass('ed-room-select-fill');
	});

   


	$('#contact-form').validate({
		rules: {
			user_name: {
				required: true,
				minlength: 4
			},
			user_email: {
				required: true,
				email: true
			},
			user_subject: {
				required: false
			},
			user_message: {
				required: true
			}
		},
		messages: {
			user_name: {
				required: 'Come on, you have a name don\'t you?',
				minlength: 'Your name must consist of at least 2 characters'
			},
			user_email: {
				required: 'Please put your email address'
			},
			user_message: {
				required: 'Put some messages here?',
				minlength: 'Your name must consist of at least 2 characters'
			}

		},
		submitHandler: function (form) {
			$(form).ajaxSubmit({
				type: 'POST',
				data: $(form).serialize(),
				url: 'sendmail.php',
				success: function () {
					$('#contact-form #success').fadeIn();
				},
				error: function () {

					$('#contact-form #error').fadeIn();
				}
			});
		}
	});







	var map;

	function initialize() {
		var mapOptions = {
			zoom: 13,
			center: new google.maps.LatLng(50.97797382271958, -114.107718560791)
			// styles: style_array_here
		};
		map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
	}

	var google_map_canvas = $('#map-canvas');

	if (google_map_canvas.length) {
		google.maps.event.addDomListener(window, 'load', initialize);
	}

	


})(jQuery);