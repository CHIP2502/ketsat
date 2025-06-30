$(document).ready(function(){
	//home slider
	$("#newproduct-slider").owlCarousel({
		items : 5,
		itemsCustom : false,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [980,2],
		itemsMobile : [533,1],
		navigation : true,
		pagination:false,
		autoPlay: true,
		lazyLoad : true,
	});
	//home slider
	$("#newproduct-slider2").owlCarousel({
		items : 5,
		itemsCustom : false,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [980,2],
		itemsMobile : [533,1],
		navigation : true,
		pagination:false,
		autoPlay: true,
		lazyLoad : true,
	});
	//home slider
	$("#newproduct-slider3").owlCarousel({
		items : 5,
		itemsCustom : false,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [980,2],
		itemsMobile : [533,1],
		navigation : true,
		pagination:false,
		autoPlay: true,
		lazyLoad : true,
	});
	//Hotnews slider
	$("#banner-slider").owlCarousel({
		pagination :true, // Show next and prev buttons
		slideSpeed : 300,
		paginationSpeed : 400,
		singleItem:true,
	});
	
	
	
	
	// button top
	$('.go-top').click(function(){
		$("html, body").animate({scrollTop: $('body').offset().top}, 700);
	});
	$(function () {
		$(window).scroll(function () {
			if ($(this).scrollTop() > 400) {
				$('.go-top').fadeIn();
			} else {
				$('.go-top').fadeOut();
			}
		});
	});
	
	
	
});