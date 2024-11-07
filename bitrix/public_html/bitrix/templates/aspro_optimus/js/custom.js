/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/

$(document).ready(function () {
	
new WOW().init();

var $navFix = $(".bx_filter_vertical");
var $contacts_scroll = $('#contacts').offset().top;
$(window).scroll(function(){
	if ($(this).scrollTop() >= 128) {
		$('#header').css('position', 'fixed');
		$('.dummy').css('display', 'block');
	} else {
		$('#header').css('position', 'relative');
		$('.dummy').css('display', 'none');
	}
	if ($(this).scrollTop() >= $contacts_scroll - 800) {
		$navFix.removeClass("fixed");
	} else if ( $(this).scrollTop() > 2000){
		$navFix.addClass("fixed");
	} else if ($(this).scrollTop() <= 2000) {
		$navFix.removeClass("fixed");
	}
});

$('#my_zayavka_popup .form_body .form-control:first-child input').attr('minlength', 3);
$('#my_rassrochka_popup .form_body .form-control:first-child input').attr('minlength', 3);
$('#my_zayavka_popup .form_body .form-control:first-child input').attr('maxlength', 50);
$('#my_rassrochka_popup .form_body .form-control:first-child input').attr('maxlength', 50);

$('#zayavka').click(function() {
	$('.my_darkener').css('display', 'block');
	$('#my_zayavka_popup').css('display', 'block');
	$('#header').css('display', 'none');
});
$('#rassrochka').click(function() {
	$('.my_darkener').css('display', 'block');
	$('#my_rassrochka_popup').css('display', 'block');
	$('#header').css('display', 'none');
});
$('.my_darkener').click(function() {
	$('#my_zayavka_popup').css('display', 'none');
	$('.my_darkener').css('display', 'none');
	$('.my_success').css('display', 'none');
	$('#my_rassrochka_popup').css('display', 'none');
	$('#header').css('display', 'block');
});
$('.my_closer').click(function() {
	$('.my_order_popup').css('display', 'none');
	$('.my_darkener').css('display', 'none');
	$('.my_success').css('display', 'none');
	$('#my_rassrochka_popup').css('display', 'none');
	$('#header').css('display', 'block');
});

function animate(elem){
    var effect = elem.data("effect");
    if(!effect || elem.hasClass(effect)) return false;
    elem.addClass(effect);
    setTimeout( function(){
        elem.removeClass(effect);
    }, 1000);
}
 
$(".animated").mouseenter(function() {
    animate($(this));
});



		$('#lofslidecontent45').lofJSidernews( { interval:10000,
											 	easing:'easeInOutQuad',
												duration:1200,
												auto:true } );
	$('#lofslidecontent46').lofJSidernews( { interval:6000,
											 	easing:'easeInOutQuad',
												duration:1200,
												mainWidth:725,
												mainHeight:372,
												auto:true } );



												
$('#contacts div.afbf_item_pole').each(function(i) {
    var number = i + 1;
    $(this).addClass("formpole"+number);
});

$('<div class="row" id="forma1"></div>').insertBefore('#contacts .formpole1');
$(".formpole1").addClass("col-md-4 col-xs-12");
$(".formpole2").addClass("col-md-4 col-xs-12");
$(".formpole3").addClass("col-md-4 col-xs-12");
/*$('<div id="wrapper22"></div>').insertBefore('#contacts .formpole4');
$('#contacts .formpole4').appendTo('#wrapper22');
$('#contacts .afbf_submit_block').appendTo('#wrapper22');*/
$(".formpole4").addClass("col-md-12");
$('#contacts .formpole1').appendTo('#forma1');
$('#contacts .formpole2').appendTo('#forma1');
$('#contacts .formpole3').appendTo('#forma1');
$('#contacts .formpole4').appendTo('#forma1');


$(".section_element .owl-carousel").owlCarousel({
        items: 5,
        margin: 12,
        nav: true,
        navText: ["<img src='/bitrix/templates/aspro_optimus/images/svg/left.svg'/>", "<img src='/bitrix/templates/aspro_optimus/images/svg/right.svg'/>"],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            900: {
                items: 2
            },
            1200: {
                items: 2
            },
            1300: {
                items: 2
            }
        }
});

$(".popular__block .owl-carousel").owlCarousel({
        items: 5,
        margin: 12,
        nav: true,
        navText: ["<img src='/bitrix/templates/aspro_optimus/images/svg/left.svg'/>", "<img src='/bitrix/templates/aspro_optimus/images/svg/right.svg'/>"],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            900: {
                items: 3
            },
            1200: {
                items: 4
            },
            1300: {
                items: 5
            }
        }
});
$(".projects__block .owl-carousel").owlCarousel({
        items: 1,
        nav: true,
        navText: ["<img src='/bitrix/templates/aspro_optimus/images/svg/left.svg'/>", "<img src='/bitrix/templates/aspro_optimus/images/svg/right.svg'/>"]        
});
$(".main__slider .owl-carousel").owlCarousel({
        items: 1,
        nav: true,
dots: true,
        navText: ["<img src='/bitrix/templates/aspro_optimus/images/svg/left.svg'/>", "<img src='/bitrix/templates/aspro_optimus/images/svg/right.svg'/>"]        
});


});

(function($) {

var types = ['DOMMouseScroll', 'mousewheel'];

$.event.special.mousewheel = {
	setup: function() {
		if ( this.addEventListener )
			for ( var i=types.length; i; )
				this.addEventListener( types[--i], handler, false );
		else
			this.onmousewheel = handler;
	},
	
	teardown: function() {
		if ( this.removeEventListener )
			for ( var i=types.length; i; )
				this.removeEventListener( types[--i], handler, false );
		else
			this.onmousewheel = null;
	}
};

$.fn.extend({
	mousewheel: function(fn) {
		return fn ? this.bind("mousewheel", fn) : this.trigger("mousewheel");
	},
	
	unmousewheel: function(fn) {
		return this.unbind("mousewheel", fn);
	}
});


function handler(event) {
	var args = [].slice.call( arguments, 1 ), delta = 0, returnValue = true;
	
	event = $.event.fix(event || window.event);
	event.type = "mousewheel";
	
	if ( event.wheelDelta ) delta = event.wheelDelta/120;
	if ( event.detail     ) delta = -event.detail/3;
	
	// Add events and delta to the front of the arguments
	args.unshift(event, delta);

	return $.event.handle.apply(this, args);
}

})(jQuery);

/**
 * @version		$Id:  $Revision
 * @package		jquery
 * @subpackage	lofslidernews
 * @copyright	Copyright (C) JAN 2010 LandOfCoder.com <@emai:landofcoder@gmail.com>. All rights reserved.
 * @website     http://landofcoder.com
 * @license		This plugin is dual-licensed under the GNU General Public License and the MIT License 
 */
// JavaScript Document
(function($) {
	 $.fn.lofJSidernews = function( settings ) {
	 	return this.each(function() {
			// get instance of the lofSiderNew.
			new  $.lofSidernews( this, settings ); 
		});
 	 }
	 $.lofSidernews = function( obj, settings ){
		this.settings = {
			direction	    	: '',
			mainItemSelector    : 'li',
			navInnerSelector	: 'ul',
			navSelector  		: 'li' ,
			navigatorEvent		: 'click',
			wapperSelector: 	'.lof-main-wapper',
			interval	  	 	: 4000,
			auto			    : true, // whether to automatic play the slideshow
			maxItemDisplay	 	: 3,
			startItem			: 0,
			navPosition			: 'vertical', 
			navigatorHeight		: 100,
			navigatorWidth		: 450,
			duration			: 600,
			navItemsSelector    : '.lof-navigator li',
			navOuterSelector    : '.lof-navigator-outer' ,
			isPreloaded			: true,
			easing				: 'easeInOutQuad'
		}	
		$.extend( this.settings, settings ||{} );	
		this.nextNo         = null;
		this.previousNo     = null;
		this.maxWidth  = this.settings.mainWidth || 600;
		this.wrapper = $( obj ).find( this.settings.wapperSelector );	
		this.slides = this.wrapper.find( this.settings.mainItemSelector );
		if( !this.wrapper.length || !this.slides.length ) return ;
		// set width of wapper
		if( this.settings.maxItemDisplay > this.slides.length ){
			this.settings.maxItemDisplay = this.slides.length;	
		}
		this.currentNo      = isNaN(this.settings.startItem)||this.settings.startItem > this.slides.length?0:this.settings.startItem;
		this.navigatorOuter = $( obj ).find( this.settings.navOuterSelector );	
		this.navigatorItems = $( obj ).find( this.settings.navItemsSelector ) ;
		this.navigatorInner = this.navigatorOuter.find( this.settings.navInnerSelector );
		
		if( this.settings.navPosition == 'horizontal' ){ 
			this.navigatorInner.width( this.slides.length * this.settings.navigatorWidth );
			this.navigatorOuter.width( this.settings.maxItemDisplay * this.settings.navigatorWidth );
			this.navigatorOuter.height(	this.settings.navigatorHeight );
			
		} else {
			this.navigatorInner.height( this.slides.length * this.settings.navigatorHeight );	
			
			this.navigatorOuter.height( this.settings.maxItemDisplay * this.settings.navigatorHeight );
			this.navigatorOuter.width(	this.settings.navigatorWidth );
		}		
		this.navigratorStep = this.__getPositionMode( this.settings.navPosition );		
		this.directionMode = this.__getDirectionMode();  
		
		
		if( this.settings.direction == 'opacity') {
			this.wrapper.addClass( 'lof-opacity' );
			$(this.slides).css('opacity',0).eq(this.currentNo).css('opacity',1);
		} else { 
			this.wrapper.css({'left':'-'+this.currentNo*this.maxSize+'px', 'width':( this.maxWidth ) * this.slides.length } );
		}

		
		if( this.settings.isPreloaded ) {
			this.preLoadImage( this.onComplete );
		} else {
			this.onComplete();
		}
		
	 }
     $.lofSidernews.fn =  $.lofSidernews.prototype;
     $.lofSidernews.fn.extend =  $.lofSidernews.extend = $.extend;
	 
	 $.lofSidernews.fn.extend({
							  
		startUp:function( obj, wrapper ) {
			seft = this;

			this.navigatorItems.each( function(index, item ){
				$(item).click( function(){
					seft.jumping( index, true );
					seft.setNavActive( index, item );					
				} );
				$(item).css( {'height': seft.settings.navigatorHeight, 'width':  seft.settings.navigatorWidth} );
			})
			this.registerWheelHandler( this.navigatorOuter, this );
			this.setNavActive(this.currentNo );
			
			if( this.settings.buttons && typeof (this.settings.buttons) == "object" ){
				this.registerButtonsControl( 'click', this.settings.buttons, this );

			}
			if( this.settings.auto ) 
			this.play( this.settings.interval,'next', true );
			
			return this;
		},
		onComplete:function(){
			setTimeout( function(){ $('.preload').fadeOut( 900 ); }, 400 );	this.startUp( );
		},
		preLoadImage:function(  callback ){
			var self = this;
			var images = this.wrapper.find( 'img' );
	
			var count = 0;
			images.each( function(index,image){ 
				if( !image.complete ){				  
					image.onload =function(){
						count++;
						if( count >= images.length ){
							self.onComplete();
						}
					}
					image.onerror =function(){ 
						count++;
						if( count >= images.length ){
							self.onComplete();
						}	
					}
				}else {
					count++;
					if( count >= images.length ){
						self.onComplete();
					}	
				}
			} );
		},
		navivationAnimate:function( currentIndex ) { 
			if (currentIndex <= this.settings.startItem 
				|| currentIndex - this.settings.startItem >= this.settings.maxItemDisplay-1) {
					this.settings.startItem = currentIndex - this.settings.maxItemDisplay+2;
					if (this.settings.startItem < 0) this.settings.startItem = 0;
					if (this.settings.startItem >this.slides.length-this.settings.maxItemDisplay) {
						this.settings.startItem = this.slides.length-this.settings.maxItemDisplay;
					}
			}		
			this.navigatorInner.stop().animate( eval('({'+this.navigratorStep[0]+':-'+this.settings.startItem*this.navigratorStep[1]+'})'), 
												{duration:500, easing:'easeInOutQuad'} );	
		},
		setNavActive:function( index, item ){
			if( (this.navigatorItems) ){ 
				this.navigatorItems.removeClass( 'active' );
				$(this.navigatorItems.get(index)).addClass( 'active' );	
				this.navivationAnimate( this.currentNo );	
			}
		},
		__getPositionMode:function( position ){
			if(	position  == 'horizontal' ){
				return ['left', this.settings.navigatorWidth];
			}
			return ['top', this.settings.navigatorHeight];
		},
		__getDirectionMode:function(){
			switch( this.settings.direction ){
				case 'opacity': this.maxSize=0; return ['opacity','opacity'];
				default: this.maxSize=this.maxWidth; return ['left','width'];
			}
		},
		registerWheelHandler:function( element, obj ){ 
			 element.bind('mousewheel', function(event, delta ) {
				var dir = delta > 0 ? 'Up' : 'Down',
					vel = Math.abs(delta);
				if( delta > 0 ){
					obj.previous( true );
				} else {
					obj.next( true );
				}
				return false;
			});
		},
		registerButtonsControl:function( eventHandler, objects, self ){ 
			for( var action in objects ){ 
				switch (action.toString() ){
					case 'next':
						objects[action].click( function() { self.next( true) } );
						break;
					case 'previous':
						objects[action].click( function() { self.previous( true) } );
						break;
				}
			}
			return this;	
		},
		onProcessing:function( manual, start, end ){	 		
			this.previousNo = this.currentNo + (this.currentNo>0 ? -1 : this.slides.length-1);
			this.nextNo 	= this.currentNo + (this.currentNo < this.slides.length-1 ? 1 : 1- this.slides.length);				
			return this;
		},
		finishFx:function( manual ){
			if( manual ) this.stop();
			if( manual && this.settings.auto ){ 
				this.play( this.settings.interval,'next', true );
			}		
			this.setNavActive( this.currentNo );	
		},
		getObjectDirection:function( start, end ){
			return eval("({'"+this.directionMode[0]+"':-"+(this.currentNo*start)+"})");	
		},
		fxStart:function( index, obj, currentObj ){
				if( this.settings.direction == 'opacity' ) { 
					$(this.slides).stop().animate({opacity:0}, {duration: this.settings.duration, easing:this.settings.easing} );
					$(this.slides).eq(index).stop().animate( {opacity:1}, {duration: this.settings.duration, easing:this.settings.easing} );
				}else {
					this.wrapper.stop().animate( obj, {duration: this.settings.duration, easing:this.settings.easing} );
				}
			return this;
		},
		jumping:function( no, manual ){
			this.stop(); 
			if( this.currentNo == no ) return;		
			 var obj = eval("({'"+this.directionMode[0]+"':-"+(this.maxSize*no)+"})");
			this.onProcessing( null, manual, 0, this.maxSize )
				.fxStart( no, obj, this )
				.finishFx( manual );	
				this.currentNo  = no;
		},
		next:function( manual , item){

			this.currentNo += (this.currentNo < this.slides.length-1) ? 1 : (1 - this.slides.length);	
			this.onProcessing( item, manual, 0, this.maxSize )
				.fxStart( this.currentNo, this.getObjectDirection(this.maxSize ), this )
				.finishFx( manual );
		},
		previous:function( manual, item ){
			this.currentNo += this.currentNo > 0 ? -1 : this.slides.length - 1;
			this.onProcessing( item, manual )
				.fxStart( this.currentNo, this.getObjectDirection(this.maxSize ), this )
				.finishFx( manual	);			
		},
		play:function( delay, direction, wait ){	
			this.stop(); 
			if(!wait){ this[direction](false); }
			var self  = this;
			this.isRun = setTimeout(function() { self[direction](true); }, delay);
		},
		stop:function(){ 
			if (this.isRun == null) return;
			clearTimeout(this.isRun);
            this.isRun = null; 
		}
	})
})(jQuery)