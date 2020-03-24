//Доступный hamburger https://foxland.fi/simple-accessible-svg-menu-hamburger-animation

$(document).ready(function () {

	function hamburger(element, menu) {
		var button = document.getElementById(element),
			menu = document.getElementById(menu),
			menuList = document.querySelector('.menu');
		menuBottom = document.getElementById('js-header__block_bottom');
		button.onclick = function () {
			// Toggle class "opened". Set also aria-expanded to true or false.
			if (-1 !== button.className.indexOf("opened")) {
				button.className = button.className.replace(" opened", "");
				button.setAttribute("aria-expanded", "false");
				menuBottom.classList.remove("active");
				menuList.classList.remove("active");
				$("body").removeClass("fixed");
				$("html").removeClass("js-height");
				$(".header__overlay").hide();
			} else {
				button.className += " opened";
				button.setAttribute("aria-expanded", "true");
				menuBottom.classList.add("active");
				menuList.classList.add("active");
				$("body").addClass("fixed");
				$("html").addClass("js-height");
			}
		};
	}
	var windowWidth2 = $(window).width();

	$(".labels__sale").hover(function () {
		$(this).toggleClass('active')
	});
	$(".labels__item").hover(function () {
		$(this).toggleClass('active')
	});
	if ($(".js-recently-watched").length) {
		let productCard = document.querySelector('.product-card');
		let newCatalog = document.querySelector('.special-offers.catalog');
		if (productCard) {
			var mySwiper13 = new Swiper(".js-recently-watched .swiper-container", {
				slidesPerView: 6,
				slidesPerGroup: 1,
				spaceBetween: 25,
				touchRatio: 1,
				navigation: {
					nextEl: ".recently-watched__next",
					prevEl: ".recently-watched__prev"
				},
				breakpoints: {
					480: {
						slidesPerView: 1.5,
						slidesPerGroup: 1,
						spaceBetween: 8,

					},

					640: {
						slidesPerView: 3,
						slidesPerGroup: 1,
						spaceBetween: 12
					},
					767: {
						slidesPerView: 4,
						slidesPerGroup: 1,
						spaceBetween: 16
					},
				},
				pagination: {
					el: ".swiper-pagination-recently-watched",
					clickable: true
				}

			});
			if ($(".js-recently-watched .swiper-slide").length > 5) {
				$('.recently-watched__prev').show();
				$('.recently-watched__next').show();
			} else {
				$('.recently-watched__prev').hide();
				$('.recently-watched__next').hide();
			}
		} else {
			var mySwiper13 = new Swiper(".js-recently-watched .swiper-container", {
				slidesPerView: 5,
				slidesPerGroup: 1,
				spaceBetween: 25,
				touchRatio: 1,
				navigation: {
					nextEl: ".recently-watched__next",
					prevEl: ".recently-watched__prev"
				},
				breakpoints: {
					480: {
						slidesPerView: 1.5,
						slidesPerGroup: 1,
						spaceBetween: 8,

					},

					640: {
						slidesPerView: 3,
						slidesPerGroup: 1,
						spaceBetween: 12
					},
					767: {
						slidesPerView: 4,
						slidesPerGroup: 1,
						spaceBetween: 16
					},
				},
				pagination: {
					el: ".swiper-pagination-recently-watched",
					clickable: true
				}

			});
			if ($(".js-recently-watched .swiper-slide").length > 5) {
				$('.recently-watched__prev').show();
				$('.recently-watched__next').show();
			} else {
				$('.recently-watched__prev').hide();
				$('.recently-watched__next').hide();
			}
		}


	}
	(function () {
		let mainCollection = document.querySelectorAll('.special-offers .container');
		let catalog = document.querySelector('.special-offers.catalog');

		$(".special-offers  .container").each(function (index, element) {
			var $this = $(this);
			$this.find('[class ^=swiper-pagination]').addClass("instance-pagination-" + index);
		});
		if (catalog) {
			mainCollection.forEach(function (element, index) {
				let $this = $(this);
				element.querySelector('.swiper-container').classList.add("instance-" + index);
				element.querySelector('.swiper-button-next').classList.add("instance-next-" + index);
				element.querySelector('.swiper-button-prev').classList.add("instance-prev-" + index);

				var mySwiper33 = new Swiper(".instance-" + index, {
					slidesPerView: 3,
					spaceBetween: 25,
					slidesPerGroup: 4,
					allowTouchMove: false,

					// loop: true,
					// loopFillGroupWithBlank: true,
					pagination: {
						el: ".instance-pagination-" + index,
						clickable: true
					},
					scrollbar: {
						el: ".swiper-scrollbar",
						hide: false,
						draggable: true
					},
					navigation: {
						nextEl: ".instance-next-" + index,
						prevEl: ".instance-prev-" + index
					},
					breakpoints: {
						// when window width is <= 320px
						767: {
							allowTouchMove: true,
							slidesPerGroup: 1,
							spaceBetween: 22,
							slidesPerView: 2
						},
						600: {
							allowTouchMove: true,
							slidesPerGroup: 1,
							slidesPerView: 1,
							spaceBetween: 10
						}


					}
				});


				if (windowWidth2 > 767) {
					if (element.querySelectorAll('.swiper-slide').length > 3) {
						element.querySelector('.swiper-button-next').style.display = 'block';
						element.querySelector('.swiper-button-prev').style.display = 'block';
						element.querySelector(".instance-pagination-" + index).style.display = 'flex';

					} else {
						element.querySelector('.swiper-button-next').style.display = 'none';
						element.querySelector('.swiper-button-prev').style.display = 'none';
						element.querySelector(".instance-pagination-" + index).style.display = 'none';
					}
				}

			})
		} else {
			mainCollection.forEach(function (element, index) {
				let $this = $(this);
				element.querySelector('.swiper-container').classList.add("instance-" + index);
				element.querySelector('.swiper-button-next').classList.add("instance-next-" + index);
				element.querySelector('.swiper-button-prev').classList.add("instance-prev-" + index);

				var mySwiper33 = new Swiper(".instance-" + index, {
					slidesPerView: 4,
					spaceBetween: 25,
					slidesPerGroup: 4,
					allowTouchMove: false,

					// loop: true,
					// loopFillGroupWithBlank: true,
					pagination: {
						el: ".instance-pagination-" + index,
						clickable: true
					},
					scrollbar: {
						el: ".swiper-scrollbar",
						hide: false,
						draggable: true
					},
					navigation: {
						nextEl: ".instance-next-" + index,
						prevEl: ".instance-prev-" + index
					},
					breakpoints: {
						// when window width is <= 320px
						767: {
							allowTouchMove: true,
							slidesPerGroup: 1,
							spaceBetween: 22,
							slidesPerView: 2
						},
						600: {
							allowTouchMove: true,
							slidesPerGroup: 1,
							slidesPerView: 1,
							spaceBetween: 10
						}


					}
				});


				if (windowWidth2 > 767) {
					if (element.querySelectorAll('.swiper-slide').length > 4) {
						element.querySelector('.swiper-button-next').style.display = 'block';
						element.querySelector('.swiper-button-prev').style.display = 'block';
						element.querySelector(".instance-pagination-" + index).style.display = 'flex';

					} else {
						element.querySelector('.swiper-button-next').style.display = 'none';
						element.querySelector('.swiper-button-prev').style.display = 'none';
						element.querySelector(".instance-pagination-" + index).style.display = 'none';
					}
				}

			})
		}


	})();
	var mySwiper9 = new Swiper(".js-product-card__rel", {
		slidesPerView: 3,
		slidesPerGroup: 1,
		// spaceBetween: 27,
		touchRatio: 1,
		allowTouchMove: false,
		navigation: {
			nextEl: ".rel__next",
			prevEl: ".rel__prev"
		},
		pagination: {
			el: ".rel__pagination",
			clickable: true
		},
		breakpoints: {
			767: {
				allowTouchMove: true,
				slidesPerView: 2,
				// slidesPerGroup: 1,
				spaceBetween: 18
			},
		},
	});
	var mySwiper6 = new Swiper(".js-gallery__item", {
		slidesPerView: 1,
		slidesPerGroup: 1,
		spaceBetween: 0,
		lazy: true,
		effect: "fade"
	});
	var mySwiper10 = new Swiper(".js-download-catalogs", {
		// Optional parameters
		// slidesPerGroup: 1,
		slidesPerView: 1,
		slidesPerColumn: 3,
		spaceBetween: 24,
		allowTouchMove: false,
		// direction: "horizontal",
		// loop: true,
		// If we need pagination
		navigation: {
			nextEl: ".download-catalogs__next",
			prevEl: ".download-catalogs__prev"
		},
		pagination: {
			el: ".swiper-pagination-download-catalogs",
			clickable: true
		},
		breakpoints: {
			// when window width is <= 320px
			767: {
				allowTouchMove: true,
			}
		}
		// Navigation arrows
		// navigation: {
		// 	nextEl: ".main_next",
		// 	prevEl: ".main_prev"
		// },

		// And if we need scrollbar
		// scrollbar: {
		// 	el: ".swiper-scrollbar"
		// }
	});
	var mySwiper11 = new Swiper(".js-practical-features .swiper-container", {
		// Optional parameters
		// slidesPerGroup: 1,
		slidesPerView: 1,
		spaceBetween: 0,
		allowTouchMove: false,
		// direction: "horizontal",
		// loop: true,
		// If we need pagination
		navigation: {
			nextEl: ".practical-features__next",
			prevEl: ".practical-features__prev"
		},
		pagination: {
			el: ".swiper-pagination-practical-features",
			clickable: true
		},
		breakpoints: {
			// when window width is <= 320px
			767: {
				allowTouchMove: true,
			}
		}
	});

	var mySwiper34 = new Swiper(".js-reviews-main .swiper-container", {
		// Optional parameters
		// slidesPerGroup: 1,
		slidesPerView: 1,
		spaceBetween: 0,
		// direction: "horizontal",
		// loop: true,
		// If we need pagination
		navigation: {
			nextEl: ".reviews-main__next",
			prevEl: ".reviews-main__prev"
		},
		pagination: {
			el: ".swiper-pagination-reviews-main",
			clickable: true
		},
		breakpoints: {
			767: {
				spaceBetween: 16
			},
		},
	});

	if ($(".product-card .js-diagram__slider").length) {

		var $slides = $('.product-card .js-diagram__slider').find('.swiper-slide');
		var $first = $slides.eq(0);
		var mySwiper12 = new Swiper(".product-card .js-diagram__slider .swiper-container", {
			slidesPerView: 4,
			slidesPerGroup: 1,
			spaceBetween: 12,
			touchRatio: 1,
			allowTouchMove: false,
			navigation: {
				nextEl: ".diagram__next",
				prevEl: ".diagram__prev"
			},
			breakpoints: {

				600: {
					allowTouchMove: true,
					slidesPerView: 2,
					slidesPerGroup: 1,
					spaceBetween: 16,
				},
				767: {
					allowTouchMove: true,
					slidesPerView: 2,
					slidesPerGroup: 1,
					spaceBetween: 24
				},
			},
			pagination: {
				el: ".diagram__pagination",
				clickable: true
			},

			// other parameters
			on: {
				init: function () {
					console.log('slide init');
					$first.clone().appendTo('.product-card .js-diagram__slider .swiper-container').addClass('swiper-transform');
					// $first.addClass('swiper-transform');
					// mySwiper12.prependSlide('<div class="swiper-slide-virtual">Slide 0"</div>');
				},
			},

			// on: {
			// 	transitionEnd: function () {
			// 		console.log('init-2');
			// 		// mySwiper12.appendSlide('<div class="swiper-slide-virtual">Slide 0"</div>')
			// 		var wrapperTranslate = mySwiper12.getTranslate();
			// 		console.log(wrapperTranslate);
			// 		$first.addClass('swiper-transform')
			// 		$first.css({
			// 			"transform": "translateX(" + (wrapperTranslate * (-1)) + "px)"
			// 		});
			// 	},
			// }
		});
		// mySwiper12.on('init', function () {
		// 	console.log('slide init');
		// 	mySwiper12.prependSlide('<div class="swiper-slide-virtual">Slide 0"</div>');
		// });

		// mySwiper12.on('setTranslate', function () {
		// 	console.log(this.translate);
		// 	$first.addClass('swiper-transform');
		// 	$first.css({
		// 		"transform": "translateX(" + (this.translate * (-1)) + "px)"
		// 	});
		// });

		if ($(".product-card .diagram .swiper-slide").length > 4) {
			$('.diagram__next').show();
			$('.diagram__prev').show();
		} else {
			$('.diagram__next').hide();
			$('.diagram__prev').hide();
		}
	}

	if ($(".catalog + .js-advantages").length) {
		var mySwiper20 = new Swiper(".js-advantages  .advantages__container", {
			slidesPerView: 3,
			// slidesPerColumn: 2,
			spaceBetween: 9,
			touchRatio: 1,
			allowTouchMove: false,
			navigation: {
				nextEl: ".advantages__next",
				prevEl: ".advantages__prev"
			},
			pagination: {
				el: ".swiper-pagination-advantages",
				clickable: true
			},
			breakpoints: {
				767: {
					allowTouchMove: true,
					slidesPerView: 3,
					slidesPerColumn: 1,
					spaceBetween: 9,
				},
				450: {
					allowTouchMove: true,
					slidesPerView: 1,
					slidesPerColumn: 1,
				},
				660: {
					allowTouchMove: true,
					slidesPerView: 2,
					slidesPerGroup: 1,
					slidesPerColumn: 1,
				}
			}

		});
		if ($(".advantages .swiper-slide").length > 3) {
			$('.advantages__prev').show();
			$('.advantages__next').show();
		} else {
			$('.advantages__prev').hide();
			$('.advantages__next').hide();
		}
		$(this).on("click", "a.advantages__link", function (e) {
			console.log('true');
			console.log(window.location = $(this).attr('href'));
			window.location = $(this).attr('href');
			return true;
		});

	}
	if ($(".catalog .js-advantages").length) {
		var mySwiper200 = new Swiper(".catalog .advantages__container", {
			slidesPerView: 3,
			// slidesPerColumn: 2,
			spaceBetween: 9,
			touchRatio: 1,
			allowTouchMove: false,
			navigation: {
				nextEl: ".advantages__next",
				prevEl: ".advantages__prev"
			},
			pagination: {
				el: ".swiper-pagination-advantages",
				clickable: true
			},
			breakpoints: {
				767: {
					allowTouchMove: true,
					slidesPerView: 3,
					slidesPerColumn: 1,
					spaceBetween: 9,
				},
				450: {
					allowTouchMove: true,
					slidesPerView: 1,
					slidesPerColumn: 1,
				},
				660: {
					allowTouchMove: true,
					slidesPerView: 2,
					slidesPerGroup: 1,
					slidesPerColumn: 1,
				}
			}

		});
		if ($(".advantages .swiper-slide").length > 3) {
			$('.advantages__prev').show();
			$('.advantages__next').show();
		} else {
			$('.advantages__prev').hide();
			$('.advantages__next').hide();
		}
		$(this).on("click", "a.advantages__link", function (e) {
			console.log('true')
			console.log(window.location = $(this).attr('href'))
			window.location = $(this).attr('href');
			return true;
		});

	}

	$('.js-product-card__buy,.product-day__buy,.special-offers__buy').click(function () {

		$(document).ajaxSuccess(function () {
			console.log('1111')
			if ($(".basket__item").length > 3) {
				console.log('2222')
				$('.added-cart__items .basket__prev').show();
				$('.added-cart__items .basket__next').show();
				var mySwiper14 = new Swiper(".added-cart__items .swiper-container", {
					slidesPerView: 3,
					slidesPerGroup: 1,
					spaceBetween: 25,
					touchRatio: 1,
					navigation: {
						nextEl: ".added-cart__items .basket__next",
						prevEl: ".added-cart__items .basket__prev"
					},
					breakpoints: {
						550: {
							slidesPerView: 1,
							slidesPerGroup: 1,
							spaceBetween: 16,

						},
						767: {
							slidesPerView: 2,
						},
					}

				});
			}

		});
	});
	if ($(".gallery__item").length) {
		$('.js-button__open').click(function (e) {
			if (!$(this).hasClass('active')) {
				e.preventDefault();
				$(this).addClass('active')
				mySwiper6.slideNext(500);
			} else
			if ($(this).hasClass('active')) {
				console.log('55')
				$(this).removeClass('active')
				mySwiper6.slidePrev(500);
			}
		});

		setInterval(function () {
			$('.js-button__open').addClass('blink_on');
			setTimeout(function () {
				$('.js-button__open').removeClass('blink_on')
			}, 1500);
		}, 3000)

	}

	$(".js-header__search").click(function () {
		if ($(".search__popup").hasClass("search__popup-open") == false) {
			$(".header .search__popup").addClass("search__popup-open");
			$(".header__search").addClass("header__search-open");
			$(".header__overlay").addClass("header__overlay-open");
			$("html").addClass("fixed");

		} else {
			$(".header .search__popup").removeClass("search__popup-open");
			$(".header__search").removeClass("header__search-open");
			$(".header__overlay").removeClass("header__overlay-open");
			$("html").removeClass("fixed");
		}
	});
	$(".js-search-header__input").on("input", function () {
		if ($(this).val()) {
			$(".js-header__button,.js-header__submit").show();
		} else {
			$(".js-header__button,.js-header__submit").hide();
		}
	});
	$(".js-header__button").click(function () {
		$(".header__input").val("");
		$(this).hide();
		$(".js-header__submit").hide();

	});
	$(document).on('click', '[data-ajax]', function (e) {
		e.stopPropagation();
		e.preventDefault();
		$.get(this.getAttribute('data-url'), function (response) {
			globalPopup.append(response).show();
		});
		// $("html").addClass("fixed");
	});
	$(".js-dropdown-menu__title").click(function (event) {
		if (
			$(this)
			.parent()
			.hasClass("active") == false
		) {
			$(".dropdown-menu").removeClass("active");
			$(this)
				.parent()
				.addClass("active");
		} else {
			$(this)
				.parent()
				.removeClass("active");
		}

		$('.dropdown-menu.active ul.dropdown-menu__list').on("mouseover", function (event) {
			// $('body').css('overflow','hidden');
			$('body').addClass('fixed');
		});
		$('.dropdown-menu.active ul.dropdown-menu__list').on("mouseout", function (event) {
			// $('body').css('overflow','visible');
			$('body').removeClass('fixed');
		});
	});
	$(".js-menu-footer__title").click(function (event) {
		if (
			$(this)
			.parent()
			.parent()
			.hasClass("active") == false
		) {
			$(".menu-footer").removeClass("active");
			$(this)
				.parent()
				.parent()
				.addClass("active");
		} else {
			$(this)
				.parent()
				.parent()
				.removeClass("active");
		}
	});
	$(".js-delete__table").click(function () {
		$(this).parent().remove()
	});
	$(".js-filter__title").click(function (e) {
		if ($(this).parent().hasClass("filter__close") == false) {
			$(this).parent().addClass("filter__close");
		} else {
			$(this).parent().removeClass("filter__close");
		}
	});
	$(".js-item__close").click(function () {
		if ($(".filter__form").hasClass("closed") == false) {
			$(".filter__form").addClass("closed");
		} else {
			$(".filter__form").removeClass("closed");
		}
	});
	$(".js-share").click(function (e) {
		e.preventDefault();
		var buttonClose = document.createElement("div");
		buttonClose.className = ('button-close');
		if ($('.share__items').is(':visible')) {

			$('.share__items').hide();
		} else {
			$('.share__items').append(buttonClose);
			$('.share__items').css('display', 'flex');
		}
		// $('.share__items').

	});
	$(".labels img").hover(function () {
		var alt = $(this).attr('alt');
		var handel = $(this).position().left;
		if ($('.labels .labels__popup').length) {
			if ($('.labels .labels__popup').text() == alt) {
				$(".labels .labels__popup").remove();
			} else {
				$(".labels .labels__popup").remove();
				$(".labels").append('<div class="labels__popup" title="' + alt + '">' + alt + '</div>');
			}
		} else {
			$(".labels").append('<div class="labels__popup" title="' + alt + '">' + alt + '</div>');
		}
		$(".labels__popup").css('left', handel);
	});

	$(".js-characteristic-glossary").hover(function () {
		var text = $(this).find('.characteristic-glossary__text').text()
		var $this = $(this);


		if ($(this).hasClass('active')) {
			$(this).removeClass('active');
			// $(this).attr('click', 'true');
		} else {
			if ($(this).attr('click') == 'false') {

				$(this).attr('click', 'true');
				return false
			}
			$('.characteristic-glossary ').removeClass('active');
			$(this).addClass('active');
			console.log(text)
			$(this).find('.characteristic-glossary__link').attr('target', 'blank');

			if ($(this).find(".button-close").length < 1) {
				var buttonClose = document.createElement("div");
				buttonClose.className = ('button-close');
				$(this).find('.popup-gloss').append(buttonClose);

			}
			if (text.length > 300) {
				text = text.substring(0, 300);
				var lastIndex = text.lastIndexOf(" "); // РїРѕР·РёС†РёСЏ РїРѕСЃР»РµРґРЅРµРіРѕ РїСЂРѕР±РµР»Р°
				text = text.substring(0, lastIndex) + '...';
				$(this).find('.characteristic-glossary__text').text(text);
			}

			var target = $this.children('.popup-gloss');

			var targetPos = target.offset().top;
			var windowHeight = $(window).height();
			var elHeight = target.height();
			var scrollToElem = targetPos + elHeight;

			$(window).scroll(function () {
				var winScrollTop = $(this).scrollTop();
				if (winScrollTop > scrollToElem) {
					$(target).parent().removeClass("active");
				}
				if (scrollToElem - windowHeight - elHeight > winScrollTop) {
					$(target).parent().removeClass("active");
				}
			});

		}

	});

	$(".js-filter-glossary").hover(function (e) {
		// e.stopPropagation();
		// e.preventDefault();
		var $this = $(this)
		if ($(this).attr('data-glossary') == "show") {
			$(this).attr('data-glossary', 'hide');
		} else {
			if ($(this).attr('click') == 'false') {

				$(this).attr('click', 'true');
				return false
			}
			$('.js-filter-glossary').attr('data-glossary', 'hide');
			$(this).attr('data-glossary', 'show');
			$(document).mouseup(function (e) { // СЃРѕР±С‹С‚РёРµ РєР»РёРєР° РїРѕ РІРµР±-РґРѕРєСѓРјРµРЅС‚Сѓ

				if (!$this.is(e.target) // РµСЃР»Рё РєР»РёРє Р±С‹Р» РЅРµ РїРѕ РЅР°С€РµРјСѓ Р±Р»РѕРєСѓ
					&&
					$this.has(e.target).length === 0) { // Рё РЅРµ РїРѕ РµРіРѕ РґРѕС‡РµСЂРЅРёРј СЌР»РµРјРµРЅС‚Р°Рј
					$this.attr('data-glossary', 'hide'); // СЃРєСЂС‹РІР°РµРј РµРіРѕ
				}
			});
			var target = $this;
			var targetPos = target.offset().top;
			var windowHeight = $(window).height();
			var elHeight = target.height();
			var scrollToElem = targetPos + elHeight;
			if ($(this).find(".button-close").length < 1) {
				var buttonClose = document.createElement("div");
				buttonClose.className = ('button-close');
				$(this).find('.popup-gloss').append(buttonClose);

			}
			$(window).scroll(function () {
				var winScrollTop = $(this).scrollTop();
				if (winScrollTop > scrollToElem) {
					$(target).attr('data-glossary', 'hide');
				}
				if (scrollToElem - windowHeight - elHeight > winScrollTop) {
					$(target).attr('data-glossary', 'hide');
				}
			});
		}
	});

	$(document).on("click", "a[href='#'].js-close", function (e) {
		e.preventDefault();
		console.log('preventClick-1')
	});
	$(document).on("click", "a[href='#'].load-more", function (e) {
		e.preventDefault();
		console.log('preventClick-2')
	});
	$(document).on("click", ".js-city-change", function (e) {
		$(this).toggleClass('active');
	});
	$(document).on("click", ".button-close", function (e) {
		// var shareItems = document.getElementsByClassName('share__items');

		if (this.parentElement.classList.contains('share__items')) {
			console.log('222222222222');
			this.parentElement.style.display = ('none');
		}
		if (this.parentElement.classList.contains('popup-gloss')) {
			console.log('33333');
			this.parentElement.parentElement.setAttribute('click', 'false');
			this.parentElement.parentElement.removeClass = 'active';
		}
		if (this.parentElement.classList.contains('popup-prop')) {
			console.log('44444');
			this.parentElement.style.display = 'none';
		}
		this.remove();
	});

	// (function() {
	// 	var glossaries = document.querySelectorAll('.js-filter-glossary');
	// 	[].forEach.call(glossaries, function(node) {
	// 		node.addEventListener('click', function(e) {
	// 			e.stopPropagation();
	// 			if(this.getAttribute('data-glossary') == 'show') {
	// 				this.setAttribute('data-glossary', 'hide');
	// 			} else {
	// 				glossaries.setAttribute('data-glossary','hide');
	// 				this.setAttribute('data-glossary', 'show');
	// 			}
	// 		});
	// 	});
	// })();
	// (function () {
	// 	let productCard = document.querySelector('.product-card');

	// 	if (productCard) {
	// 		if (productCard.classList.contains('new-product-card')) {
	// 			console.log('yes')
	// 		} else {

	// 			$('.product-card__announcing .announcing__item img').each(function () {
	// 				var $this = $(this);
	// 				$(this).parent().append($this.attr('alt'));

	// 			});
	// 		}
	// 	}



	// })();

	var mySwiper15 = new Swiper(".js-sidebar-articles", {
		navigation: {
			nextEl: ".articles__next",
			prevEl: ".articles__prev"
		},
		slidesPerView: 1,
		slidesPerGroup: 1,
		spaceBetween: 12,
		pagination: {
			el: ".articles__pagination",
			clickable: true
		},
		breakpoints: {
			600: {
				slidesPerView: 1,
				slidesPerGroup: 1,
				spaceBetween: 12,
			},
			767: {
				slidesPerView: 2,
				slidesPerGroup: 1,
				spaceBetween: 12,
			},
		}
	});
	$(".catalog__prop .js-link-pop-glossary").click(function () {
		var text = $(this).find('.popup-prop__text').text();
		var handel = $(this).position().left;
		const $this = $(this);
		// console.log(text)
		// console.log(handel)

		if ($(this).children('.popup-prop').css('display') == 'none') {
			$('.popup-prop').hide()
			$(this).children('.popup-prop').show();
			$(this).find('.popup-prop__link').attr('target', 'blank');
			if ($(this).find(".button-close").length < 1) {
				var buttonClose = document.createElement("div");
				buttonClose.className = ('button-close');
				$(this).find('.popup-prop').append(buttonClose);

			}
			if (text.length > 300) {
				text = text.substring(0, 300);
				var lastIndex = text.lastIndexOf(" "); // позиция последнего пробела
				text = text.substring(0, lastIndex) + '...';
				$(this).find('.popup-prop__text').text(text);
				console.log($(this).find('.popup-prop__text').text().length);
			}


		} else {
			$(this).children('.popup-prop').hide();

		}
		$(document).mouseup(function (e) { // событие клика по веб-документу
			if (!$this.is(e.target) // если клик был не по нашему блоку
				&&
				$this.has(e.target).length === 0) { // и не по его дочерним элементам
				$('.popup-prop').hide() // скрываем его

			}
		});

	});
	if (windowWidth2 > 767) {
		var mySwiper4 = new Swiper(".col-md-3 .js-container-upsale", {
			// Optional parameters
			// slidesPerGroup: 1,
			slidesPerView: 1,
			slidesPerColumn: 2,
			spaceBetween: 24,
			allowTouchMove: false,
			// direction: "horizontal",
			// loop: true,
			// If we need pagination
			navigation: {
				nextEl: ".upsale__next",
				prevEl: ".upsale__prev"
			},
			pagination: {
				el: ".swiper-pagination-upsale",
				clickable: true
			},
			breakpoints: {
				// when window width is <= 320px
				767: {
					allowTouchMove: true,
					slidesPerView: 1,
					slidesPerColumn: 2,
				}
			}
			// Navigation arrows
			// navigation: {
			// 	nextEl: ".main_next",
			// 	prevEl: ".main_prev"
			// },

			// And if we need scrollbar
			// scrollbar: {
			// 	el: ".swiper-scrollbar"
			// }
		});
		if ($('h1.title:contains("Корзина")')) {
			$('h1.title:contains("Корзина")').css("width", "100%")
		};

	}
	if (windowWidth2 <= 767) {
		$('.logo').appendTo('.header__block_top .container');
		$('.labels').appendTo('.product-card .product__header_top');
		$('.sidebar-right').appendTo('.product-card');
		$('.reviews__main-link').appendTo('.product-card .reviews');
		$(".col-md-3 .js-container-upsale").remove();
		var mySwiper4 = new Swiper(".special-offers.catalog .js-container-upsale", {
			// Optional parameters
			// slidesPerGroup: 1,
			slidesPerView: 1,
			slidesPerColumn: 2,
			spaceBetween: 24,
			allowTouchMove: false,
			// direction: "horizontal",
			// loop: true,
			// If we need pagination
			navigation: {
				nextEl: ".upsale__next",
				prevEl: ".upsale__prev"
			},
			pagination: {
				el: ".swiper-pagination-upsale",
				clickable: true
			},
			breakpoints: {
				// when window width is <= 320px
				767: {
					allowTouchMove: true,
					slidesPerView: 1,
					slidesPerColumn: 2,
				}
			}
			// Navigation arrows
			// navigation: {
			// 	nextEl: ".main_next",
			// 	prevEl: ".main_prev"
			// },

			// And if we need scrollbar
			// scrollbar: {
			// 	el: ".swiper-scrollbar"
			// }
		});
		// var mySwiper15 = new Swiper(".js-sidebar-articles", {
		// 	// navigation: {
		// 	// 	nextEl: ".practical-features__next",
		// 	// 	prevEl: ".practical-features__prev"
		// 	// },
		// 	pagination: {
		// 		el: ".articles__pagination",
		// 		clickable: true
		// 	},
		// 	breakpoints: {
		// 		600: {
		// 			slidesPerView: 1,
		// 			slidesPerGroup: 1,
		// 			spaceBetween: 12,
		// 		},
		// 		767: {
		// 			slidesPerView: 2,
		// 			slidesPerGroup: 1,
		// 			spaceBetween: 12,
		// 		},
		// 	}
		// });
		var mySwiper22 = new Swiper(".js-all-video", {
			// navigation: {
			// 	nextEl: ".practical-features__next",
			// 	prevEl: ".practical-features__prev"
			// },
			slidesPerView: 4,
			slidesPerGroup: 1,
			spaceBetween: 12,
			pagination: {
				el: ".swiper-pagination-all-video",
				clickable: true
			},
			breakpoints: {
				767: {
					slidesPerView: 1,
					slidesPerGroup: 1,
					spaceBetween: 12,
				},
			}
		});
		var mySwiper23 = new Swiper(".catalog .articles.swiper-container", {
			// navigation: {
			// 	nextEl: ".practical-features__next",
			// 	prevEl: ".practical-features__prev"
			// },
			pagination: {
				el: ".articles__pagination",
				clickable: true
			},
			breakpoints: {
				480: {
					slidesPerView: 1,
					slidesPerGroup: 1,
					spaceBetween: 12,

				},
				600: {
					slidesPerView: 2,
					slidesPerGroup: 1,
					spaceBetween: 12,

				},

				767: {
					slidesPerView: 3,
					slidesPerGroup: 1,
					spaceBetween: 16
				},
			}
		});
		if ($(".favorite__items").length) {
			console.log('юк');

		}
		if ($('.js-favorite_list[data-list="Избранное"]').length) {
			var favorite = $('.js-favorite_list[data-list="Избранное"]')
			favorite.addClass('favorite__items')
			$('.js-favorite_filter').prependTo($('.col-md-9'));
			$('.title').prependTo($('.col-md-9'));
			$('.breadcrumbs').prependTo($('.col-md-9'));
		}
		if ($(".filter-new").length) {
			$('.filter-new').append('<div class="filter-new__button js-filter-new__button"><span>закрыть фильтр</span></div>');
			$('.js-filter-new__button').click(function () {
				// $(this).parent().toggleClass('active')
				if ($(this).parent().hasClass('active')) {
					$(this).parent().removeClass('active');
					$('.filter-new__button span').text('закрыть фильтр');

				} else {
					$('.filter-new__button span').text('открыть фильтр');
					$(this).parent().addClass('active');
				}
			})
		}
		$(function () {

			$(".hide-tabs .js-characteristic__title").click(function (e) {
				$(this).toggleClass('active').nextUntil('.characteristic__title').toggleClass('active');
			})

		});
		if ($('h1.title:contains("Статьи и акции")').length > 0) {
			$('.col-md-3').addClass('order');
		}
		(function () {
			var firstDropdownMenu = document.querySelector('nav.dropdown-menu');
			$(".header__block_top .menu").appendTo('#js-header__block_bottom .container');
			$('.js-city-change').appendTo('#js-header__block_bottom .menu');
			$('#js-menu .header__telephones').appendTo('#js-header__block_bottom .menu');

			firstDropdownMenu.classList.add("active");
		})();

		(function () {
			let productCard = document.querySelector('.product-card');
			if (productCard) {
				let favorite = productCard.querySelector('.product-card__social');
				let productHeaderBottom = productCard.querySelector('.product__header_bottom');
				productHeaderBottom.append(favorite);
			}
		})();


	}
	$(".js-comparison__select").click(function () {
		if ($(this).hasClass("active") == true) {
			$(".comparison__select ").removeClass("active");
			$(this).addClass("active");
		} else {
			$(".comparison__select ").removeClass("active");
			$(this).addClass("active");
		}
	});
	$(".js-help__row").click(function () {
		if ($(this).hasClass("help__row-open") == false) {
			$(".help__row")
				.children(".help__info")
				.hide();
			$(".help__row").removeClass("help__row-open");
			$(this).addClass("help__row-open");
			$(this)
				.children(".help__info")
				.show();
		} else {
			$(this).removeClass("help__row-open");
			$(this)
				.children(".help__info")
				.hide();
		}
	});
	$(".js-filter_reset").click(function () {
		document.location.href = "";
		return false;
	});
	window.globalPopup = new Popup();
	(function (footerSelector, wrapperSelector) {
		var footer = document.querySelector(footerSelector);
		var wrapper = document.querySelector(wrapperSelector);
		var height;
		var setSize;

		if (!wrapper || !footer) {
			return false;
		}

		setSize = function () {
			height = footer.offsetHeight;

			wrapper.style.paddingBottom = height + "px";
			footer.style.marginTop = height * -1 + "px";
		};

		setSize();

		window.addEventListener("resize", setSize, false);
	})("#js-footer", "#js-wrapper");

	$(".filter__form .checkbox-number").each(function () {
		if ($(this).text() === '0') {
			$(this).parent().addClass('disabled');
		}
	});
	if ($('.filter-tip').length > 0) {
		$(".filter-tip").parent().addClass('parent-tip');
	}
	$('.filter__slider').draggable();
	hamburger("js-hamburger", "js-menu");

	$(".filter__tags").click(function () {
		$(this).addClass('active');
	});
	$(window).scroll(function () {
		var scroll = $(window).scrollTop();
		// var asideHeightSum = asideHeight + asideOffsetTop - 600;
		if (scroll > 10) {
			$(".dropdown-menu").removeClass("active");
			$(".header__overlay").removeClass("header__overlay-open");
			$(".header .search__popup").removeClass("search__popup-open");
			$(".header__search").removeClass("header__search-open");
			$(".header__overlay").removeClass("header__overlay-open");
		}
	});
	$(".header__overlay").click(function () {
		$(".header .search__popup").removeClass("search__popup-open");
		$(".header__search").removeClass("header__search-open");
		$(".header__overlay").removeClass("header__overlay-open");
		$("html").removeClass("fixed");
	});
	// $(function () {
	// 	$(".js-datepicker").datepicker();
	// });
	$(function () {
		$.datepicker.regional['ru'] = {
			closeText: 'Закрыть',
			prevText: '&#x3c;Пред',
			nextText: 'След&#x3e;',
			currentText: 'Сегодня',
			monthNames: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],
			monthNamesShort: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек'],
			dayNames: ['воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота'],
			dayNamesShort: ['вск', 'пнд', 'втр', 'срд', 'чтв', 'птн', 'сбт'],
			dayNamesMin: ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'],
			dateFormat: 'dd.mm.yy',
			firstDay: 1,
			isRTL: false
		};

		$.datepicker.setDefaults($.datepicker.regional['ru']);
		$(".js-datapicker").datepicker({
			minTime: 1,
			format: "d.m.Y",
			minDate: 1,
			// onSelectDate: function(ct, $i) {
			//   this.setOptions({
			// 	minTime: ct.getTime() > new Date ? false : 0
			//   })
			// }
		})
	});
	(function () {
		var mobileDetect = /Android|iPhone|iPad|iPod|BlackBerry|WPDesktop|IEMobile|Opera Mini/i.test(navigator.userAgent);
		var event = mobileDetect ? 'touchend' : 'mouseup';

		function Ratings(opts) {
			var opts = this.extend({
				element: '',
				countRate: 5,
				clickFn: function () {}
			}, opts);
			this.element = opts.element;
			if (!this.element) {
				return;
			}
			this.init(opts);
			this.events(opts);
		}
		Ratings.prototype = {
			init: function (opts) {
				var obj = this;
				this.tags = {};
				this.tags.default_state = this.element.querySelector('.ratings__disable');
				this.tags.hover = this.element.querySelector('.ratings__hover');
				this.tags.click = this.element.querySelector('.ratings__click');
				this.widthElement = this.element.offsetWidth / opts.countRate;
				this.width_votes = 0;
				this.votes = 0;
			},
			events: function (opts) {
				var obj = this;
				this.element.addEventListener('mouseover', function () {
					obj.tags.default_state.style.display = "block";
					obj.tags.hover.style.display = "block";

				}, false);

				this.element.addEventListener('mouseout', function () {

					obj.tags.default_state.style.display = "none";
					obj.tags.hover.style.display = "none";

				}, false);

				this.element.addEventListener('mousemove', function (e) {

					obj.width_votes = e.clientX - obj.element.getBoundingClientRect().left;
					obj.votes = Math.ceil(obj.width_votes / obj.widthElement);
					obj.tags.hover.style.width = obj.votes * obj.widthElement + "px";

				}, false);

				this.element.addEventListener(event, function () {

					obj.tags.click.style.width = obj.votes * obj.widthElement + "px";

					opts.clickFn.call(this, obj.votes);

				}, false);

			},

			extend: function (defaults, source) {

				for (var key in source) {
					if (source.hasOwnProperty(key)) {
						defaults[key] = source[key];
					}
				}

				return defaults;
			}

		};

		window.Ratings = Ratings;


	})();
	$(".js-viewForm").click(function () {
		//upon clicking of the button do an ajax post

		$(document).ajaxSuccess(function () {
			new Ratings({
				element: document.querySelector('.js-ratings__section'), // передаем элемент
				countRate: 5, // кол-во оценок
				clickFn: function (index) {
					$(".js-review_rating").val(index);
				}
			});
			$("[title=Телефон],.js-phone_mask").attr('type', 'tel');
			$("[title=Телефон],.js-phone_mask").attr('placeholder', 'Телефон');
			$("[title=Телефон],.js-phone_mask,[title=Телефон]").prop('required', true);
			Inputmask.extendAliases({
				'customAlias': {
					mask: "+7 (999) 999-99-99",
					oncomplete: function () {
						$(this).removeClass('BadPols');
					},
					onincomplete: function () {
						$(this).addClass('BadPols');
						$(this).val('');
					},
				}
			});
			Inputmask("customAlias").mask("[type=tel]");
		});
	});
	$(".js-about-store__button").click(function () {
		if ($(this).parent().hasClass("active") == false) {
			$(this).text('Свернуть');
			$(this).parent().addClass("active");

		} else {
			$(this).parent().removeClass("active");
			$(this).text("Читать подробнее");
		}
	});
	$(function () {
		if ($('#compare_table').length > 0) {
			$(function () {
				compareArray.init({
					table: document.getElementById("compare_table"),
					button: document.getElementById("compare_table_button").getElementsByTagName("a"),
					indexCols: 0,
					indexRows: 1,
					zebra: true,
					zebraClass: "gray_row",
					callback: function () {

					}
				});

				var butt = $("#compare_table_button"),
					ul = $(butt).next();

				butt.click(function (e) {
					e.stopPropagation();

					if ($(this).hasClass("down")) {
						$(ul).slideUp(200);
						$(this).removeClass("down");
					} else {
						$(ul).slideDown(200);
						$(this).addClass("down");
					}


					return false;
				});

				$(document).click(function () {
					$(butt).removeClass("down");
					$(ul).slideUp(200);
				});
			});
			console.log("compare_table")
			$(".js-comparison-height").parent().parent().addClass('thead__comparison');
			$('.js-comparison__link').click(function (e) {
				e.preventDefault();
			});
			$('.js-compare_block-change.active').parent().addClass('order');
			// $('.comparison__list').height($('.js-compare_block-change.active').innerHeight());
			$('.comparison__list').height($('.comparison__item.order').innerHeight());
			window.onload = function () {
				// $('.comparison__main .characteristic__row').each(function (index, el) {
				// 	var height = $('.swiper-container-comparison .characteristic__row').eq(index).height();
				// 	$(el).height(height);
				// });
				if (windowWidth2 > 767) {
					console.log($(".products-line").outerHeight(true))
					$(".thead__comparison").css({
						'height': ($(".products-line").outerHeight() + 'px')
					});
					console.log('1')

				} else {
					console.log('2')
					$(".thead__comparison").css({
						'margin-bottom': ($(".products-line").innerHeight() + 'px')
					});
					$(".js-swiper-comparison").css({
						'margin-top': ($(".thead__comparison").height() - 10 + 'px')
					});
					$(".comparison .js-swiper-pagination").css({
						'top': ($(".thead__comparison").height() + 29 + 'px')
					});
					$(".comparison .js-swiper-prev").css({
						'top': ($(".thead__comparison").height() + 48 + 'px')
					});
					$(".comparison .js-swiper-next").css({
						'top': ($(".thead__comparison").height() + 48 + 'px')
					});
					$('.comparison__main .characteristic__row').each(function (index, el) {
						if ($(this).text().length > 60) {
							$(this).addClass('level')
							$('.js-swiper-comparison .characteristic__row').eq(index).addClass("level");
							console.log($(this).text())
						}
					});
				}
			}();
			var comparison_height = $('.thead__comparison').height();
			console.log(comparison_height)

			var if_max_width = false;
			$(window).scroll(function () {
				var scroll = $(window).scrollTop();
				if (scroll > comparison_height && !if_max_width) {
					if_max_width = true;
					$('.comparison').addClass('fixed');

				} else if (scroll < comparison_height && if_max_width) {
					if_max_width = false;
					$('.comparison').removeClass('fixed');
				}
			});
			$('.comparison .fixed-header').css({
				'width': ($('.comparison').width())
			});
			var mySwiper7 = new Swiper(".comparison__diagram .diagram__slider", {
				slidesPerView: 3,
				slidesPerGroup: 1,
				spaceBetween: 27,
				touchRatio: 1,
				allowTouchMove: false,
				navigation: {

					nextEl: ".special-offers_next",
					prevEl: ".special-offers_prev"
				},
				breakpoints: {
					767: {
						allowTouchMove: true,
						slidesPerView: 2,
						touchRatio: 1,
						spaceBetween: 16,
						slidesPerGroup: 1,
						// pagination: {
						// 	type: "fraction",
						// },
					},
				},
				pagination: {
					el: ".swiper-pagination",
					clickable: true

				},
				thumbs: {
					swiper: mySwiper8,
					swiper: mySwiper5
				}

			});
			var mySwiper8 = new Swiper(".fixed-header .swiper-container", {
				slidesPerView: 3,
				slidesPerGroup: 1,
				spaceBetween: 27,
				touchRatio: 1,
				allowTouchMove: false,
				navigation: {
					nextEl: ".special-offers_next",
					prevEl: ".special-offers_prev"
				},
				breakpoints: {
					767: {
						allowTouchMove: true,
						slidesPerView: 2,
						slidesPerGroup: 1,
						spaceBetween: 16,

					},
					// 600: {
					// 	slidesPerView: 2,
					// },
					// 768: {
					// 	slidesPerView: 2,
					// 	slidesPerGroup: 1,
					// 	spaceBetween: 24
					// },
				},
				pagination: {
					el: ".swiper-pagination",
					clickable: true

				},
				thumbs: {
					swiper: mySwiper7,
					swiper: mySwiper5
				}
			});

			var mySwiper5 = new Swiper(".js-swiper-comparison", {
				slidesPerView: 3,
				slidesPerGroup: 1,
				spaceBetween: 27,
				allowTouchMove: false,
				// loop: true,
				// loopFillGroupWithBlank: true,
				navigation: {
					nextEl: ".special-offers_next",
					prevEl: ".special-offers_prev"
				},
				// scrollbar: {
				// 	el: ".swiper-scrollbar",
				// 	hide: false,
				// 	draggable: true
				// },
				pagination: {
					el: ".swiper-pagination",
					clickable: true

				},
				breakpoints: {
					767: {
						allowTouchMove: true,
						slidesPerView: 2,
						touchRatio: 1,
						spaceBetween: 16,
						slidesPerGroup: 1,
						pagination: {
							type: "fraction",
						},
					},
					// 	600: {
					// 		slidesPerView: 1,
					// 	},
					// 	768: {
					// 		slidesPerView: 2,
					// 		slidesPerGroup: 1,
					// 	},
				},
				thumbs: {
					swiper: mySwiper7,
					swiper: mySwiper8
				}
			});

		}
		if ($(".diagram").length) {
			var value = 0
			var array = []
			$('.diagram__items').each(function (index, el) {
				var arr = []
				$(this).find('.diagram__pic').each(function (index, element) {
					arr.push($(element).data('param1'))
					array.push($(element).data('param1'))
				})
				var maxVal = Math.max.apply(null, arr);
				$('.diagram__maxvalue').text(maxVal)
				value = maxVal
			})
			var zeroCheck = array.reduce(function (prev, next) {
				return prev + next;
			});
			if (zeroCheck == 0) {
				$('.diagram__items .swiper-slide').each(function (index, el) {
					var data1 = $(this).find('.diagram__pic').data('param1');
					$(this).find('.diagram__text').text(data1 + '  руб');
					$(this).find('.schedule').css({
						'height': ($(this).find('.diagram__pic').data('param1') / 100 + '%')
					});
				})
			} else {
				$('.diagram__items .swiper-slide').each(function (index, el) {
					var data1 = $(this).find('.diagram__pic').data('param1');
					$(this).find('.diagram__text').text(data1 + '  руб');
					$(this).find('.schedule').css({
						'height': ($(this).find('.diagram__pic').data('param1') / value * 100 + '%')
					});
				})
			}
			console.log(value);
			$('.js-diagram__param2').click(function () {
				var array = []
				$('.diagram__tabs').removeClass('active');
				$(this).addClass('active');
				$('.diagram__items').each(function (index, el) {
					var arr = []
					$(this).find('.diagram__pic').each(function (index, element) {
						arr.push($(element).data('param2'))
						array.push($(element).data('param2'))
					})
					var maxVal = Math.max.apply(null, arr);
					$('.diagram__maxvalue').text(maxVal)
					value = maxVal
				})
				var zeroCheck = array.reduce(function (prev, next) {
					return prev + next;
				});
				if (zeroCheck == 0) {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param2');
						$(this).find('.diagram__text').text(data1);
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param2') / 100 + '%')
						});
					})
				} else {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param2');
						$(this).find('.diagram__text').text(data1);
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param2') / value * 100 + '%')
						});
					})
				}

			})
			$('.js-diagram__param1').click(function () {
				var array = []
				$('.diagram__tabs').removeClass('active');
				$(this).addClass('active');
				$('.diagram__items').each(function (index, el) {
					var arr = []
					$(this).find('.diagram__pic').each(function (index, element) {
						arr.push($(element).data('param1'))
						array.push($(element).data('param1'))

					})
					var maxVal = Math.max.apply(null, arr);
					$('.diagram__maxvalue').text(maxVal)
					value = maxVal
				})

				var zeroCheck = array.reduce(function (prev, next) {
					return prev + next;
				});
				if (zeroCheck == 0) {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param1');
						$(this).find('.diagram__text').text(data1 + '  руб');
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param1') / 100 + '%')
						});
					})
				} else {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param1');
						$(this).find('.diagram__text').text(data1 + '  руб');
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param1') / value * 100 + '%')
						});
					})
				}

			})
			$('.js-diagram__param3').click(function () {
				var array = []
				$('.diagram__tabs').removeClass('active');
				$(this).addClass('active');
				$('.diagram__items').each(function (index, el) {
					var arr = []
					$(this).find('.diagram__pic').each(function (index, element) {
						arr.push($(element).data('param3'))
						array.push($(element).data('param3'))
					})
					var maxVal = Math.max.apply(null, arr);
					$('.diagram__maxvalue').text(maxVal)
					value = maxVal
				})

				var zeroCheck = array.reduce(function (prev, next) {
					return prev + next;
				});

				if (zeroCheck == 0) {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param3');
						$(this).find('.diagram__text').text(data1);
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param3') / 100 + '%')
						});
					})
				} else {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param3');
						$(this).find('.diagram__text').text(data1);
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param3') / value * 100 + '%')
						});
					})
				}

			})
			$('.js-diagram__param4').click(function () {
				var array = []
				$('.diagram__tabs').removeClass('active');
				$(this).addClass('active');
				$('.diagram__items').each(function (index, el) {
					var arr = []
					$(this).find('.diagram__pic').each(function (index, element) {
						arr.push($(element).data('param4'))
						array.push($(element).data('param4'))
					})
					var maxVal = Math.max.apply(null, arr);
					$('.diagram__maxvalue').text(maxVal)
					value = maxVal
				})
				var zeroCheck = array.reduce(function (prev, next) {
					return prev + next;
				});

				if (zeroCheck == 0) {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param4');
						$(this).find('.diagram__text').text(data1);
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param4') / 100 + '%')
						});
					})
				} else {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param4');
						$(this).find('.diagram__text').text(data1);
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param4') / value * 100 + '%')
						});
					})
				}

			})
			$('.js-diagram__param5').click(function () {
				var array = []
				$('.diagram__tabs').removeClass('active');
				$(this).addClass('active');
				$('.diagram__items').each(function (index, el) {
					var arr = []
					$(this).find('.diagram__pic').each(function (index, element) {
						arr.push($(element).data('param5'))
						array.push($(element).data('param5'))
					})
					var maxVal = Math.max.apply(null, arr);
					$('.diagram__maxvalue').text(maxVal)
					value = maxVal
				})
				var zeroCheck = array.reduce(function (prev, next) {
					return prev + next;
				});

				if (zeroCheck == 0) {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param5');
						$(this).find('.diagram__text').text(data1);
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param5') / 100 + '%')
						});
					})
				} else {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param5');
						$(this).find('.diagram__text').text(data1);
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param5') / value * 100 + '%')
						});
					})
				}

			})
			$('.js-diagram__param6').click(function () {
				var array = []
				$('.diagram__tabs').removeClass('active');
				$(this).addClass('active');
				$('.diagram__items').each(function (index, el) {
					var arr = []
					$(this).find('.diagram__pic').each(function (index, element) {
						arr.push($(element).data('param6'))
						array.push($(element).data('param6'))
					})
					var maxVal = Math.max.apply(null, arr);
					$('.diagram__maxvalue').text(maxVal)
					value = maxVal
				})
				var zeroCheck = array.reduce(function (prev, next) {
					return prev + next;
				});

				if (zeroCheck == 0) {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param6');
						$(this).find('.diagram__text').text(data1);
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param6') / 100 + '%')
						});
					})
				} else {
					$('.diagram__items .swiper-slide').each(function (index, el) {
						var data1 = $(this).find('.diagram__pic').data('param6');
						$(this).find('.diagram__text').text(data1);
						$(this).find('.schedule').css({
							'height': ($(this).find('.diagram__pic').data('param6') / value * 100 + '%')
						});
					})
				}

			})
			if (windowWidth2 < 768) {
				$('.diagram__sidebar').click(function () {
					$(this).toggleClass('active')
				})
				$('.comparison__list').click(function () {
					$(this).toggleClass('active')
				})
				// mySwiper7.controller.control = mySwiper8;
				// // mySwiper8.controller.control = mySwiper7;
				// mySwiper7.controller.control = mySwiper5;
			}
		}

	});
	$(function () {
		$(".js-glossary__title,.js-glossary__items").click(function () {
			$(this).parent().toggleClass('closed').removeClass('only-link').find('.js-glossary__li').removeClass('active');
			$(this).parent().find('.js-glossary__item').removeClass('test');
		});
		$(".js-glossary .js-glossary__li").click(function () {

			if ($(this).hasClass('active')) {
				$(this).removeClass('active');
				if ($(this).parent().parent().hasClass('only-link')) {
					$('.js-glossary').addClass('closed').removeClass('only-link');
				}
			} else {
				$(this).addClass('active')
			}

		});
		$(".js-glossary.only-link .js-glossary__li.active").click(function () {
			e.stopPropagation();
			e.preventDefault();
			$('.js-glossary').addClass('closed').removeClass('only-link');
		});
		$(".js-glossary a").click(function (e) {
			e.preventDefault();
		});

		var arrElem = document.querySelectorAll('.js-glossary__item');
		var arrLink = document.querySelectorAll('.js-glossary__li');
		var arrayElem = [];

		for (var i = 0; i < arrElem.length; i++) {
			arrayElem.push(arrElem[i]);
			arrElem[i].addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
				$('.js-glossary__li.active').removeClass('active')
				$('.js-glossary').addClass('closed')
				var test = arrayElem.indexOf(e.target);
				$('.js-glossary__item').removeClass('test');
				$(arrayElem[test]).addClass('test').parent().parent().removeClass('closed').addClass('only-link');
				var activeEl = $(arrLink[test]).addClass('active');
				$('html, body').animate({
					scrollTop: $(activeEl).offset().top
				}, 1000);
			});
		}
	});
	$(function () {
		$('.gallery-top [data-fancybox]').attr('data-fancybox', 'group1');
		$('[data-fancybox]').fancybox({
			buttons: [
				"zoom",
				//"share",
				"slideShow",
				"fullScreen",
				// "download",
				"thumbs",
				"close"
			],
			// animationEffect: 'fade',
			animationDuration: 350,
			animationEffect: "material",
			// transitionEffect: "slide",
			transitionDuration: 350,

		});
		$("[data-scroll]").click(function () {
			event.preventDefault();
			var id = $(this).attr('href'),
				top = $(id).offset().top;
			$('body,html').animate({
				scrollTop: top
			}, 1500);
			return false;
		})


	});

	if ($("[type=tel],[title=Телефон],.js-phone_mask").length) {
		console.log('inputmask')
		$("[title=Телефон],.js-phone_mask").attr('type', 'tel')
		$("[title=Телефон],.js-phone_mask,[title=Телефон]").prop('required', true);
		Inputmask.extendAliases({
			'customAlias': {
				mask: "+7 (999) 999-99-99",
				oncomplete: function () {
					$(this).removeClass('BadPols');
				},
				onincomplete: function () {
					$(this).addClass('BadPols');
					$(this).val('');
				},
			}
		});
		Inputmask("customAlias").mask("[type=tel]");
	}
	if ($('.page__title.content-top__title.content-top__title_top span').length > 0) {
		$(".content-top").addClass("content-top__tags");
	}

	// $('.docs-frame iframe').contents().find('html').html("<h1 style='text-align: center;'>This IS an iframe</h1>");
});
$(window).on('load', function () {
	var windowWidth2 = $(window).width();
	setTimeout(function () {
		var mySwiper = new Swiper(".js-swiper-main", {
			// Optional parameters
			direction: "horizontal",
			loop: true,
			autoplay: {
				delay: 4000,
				disableOnInteraction: false,
			},

			// If we need pagination
			pagination: {
				el: ".swiper-pagination",
				clickable: true
			},

			// Navigation arrows
			navigation: {
				nextEl: ".main_next",
				prevEl: ".main_prev"
			},

			// And if we need scrollbar
			scrollbar: {
				el: ".swiper-scrollbar",
				draggable: true
			}
		});
		var galleryThumbs = new Swiper('.js-gallery-thumbs', {
			spaceBetween: 6,
			slidesPerView: 6,
			freeMode: true,
			// direction: "vertical",
			watchSlidesVisibility: true,
			watchSlidesProgress: true,
			touchRatio: 0.2,
			slideToClickedSlide: true,
			breakpoints: {
				// when window width is <= 320px
				// 420: {
				//   slidesPerGroup: 1,
				//   slidesPerView: 1,
				//   spaceBetween: 0
				// },
				// // // when window width is <= 480px
				// // 480: {
				// 700: {
				//   slidesPerView: 2,
				//   slidesPerGroup: 2,
				//   spaceBetween: 20
				// },
				768: {

					spaceBetween: 14
				}
			},
			navigation: {
				nextEl: ".thumbs-next",
				prevEl: ".thumbs-prev"
			},
		});
		var galleryTop = new Swiper('.js-gallery-top', {
			spaceBetween: 0,
			// navigation: {
			// 	nextEl: '.swiper-button-next',
			// 	prevEl: '.swiper-button-prev',
			// },
			pagination: {
				el: ".gallery-top__pagination",
				clickable: true
			},
			thumbs: {
				swiper: galleryThumbs
			}
		});
	}, 100);

	if ($(".filter-new").length) {
		$('.filter-new .bx-filter-parameters-box-container').each(function () {
			if ($(this).find('.checkbox').length >= 4) {
				$(this).addClass('more');
				$(this).find('.checkbox').eq(3).addClass('js-autoHeight');
				$(this).find('.js-autoHeight').hide();
				$(this).find('.js-autoHeight').nextAll(".checkbox").hide();
				// $('.js-autoHeight').each(function () {
				// 	var elHeight = $(this).offset().top
				// 	var elHeightParent = $(this).parent().offset().top
				// 	var summ = elHeight - elHeightParent;
				// 	$(this).closest('.bx-filter-parameters-box-container').css('height', summ + 27);

				// })
			}
		})
		$('<span class="more-text js-more-text">Показать еще</span>').prependTo($('.more .col-xs-12'));
		$('.js-more-text').click(function () {
			if ($(this).parent().parent().hasClass('more')) {
				$(this).parent().parent().removeClass('more');
				$(this).text('Свернуть');
				$(this).siblings('.js-autoHeight').show();
				$(this).siblings('.js-autoHeight').nextAll(".checkbox").show();
			} else {
				$(this).parent().parent().addClass('more');
				$(this).text('Показать еще');
				$(this).siblings('.js-autoHeight').hide();
				$(this).siblings('.js-autoHeight').nextAll(".checkbox").hide();
				// $(this).siblings('.js-autoHeight').each(function () {
				// 	var elHeight = $(this).offset().top
				// 	var elHeightParent = $(this).parent().offset().top
				// 	var summ = elHeight - elHeightParent;
				// 	$(this).closest('.bx-filter-parameters-box-container').css('height', summ + 27);
				// })
			}
		})

	}
	if ($(".filter__form.js-form").length) {
		$('.filter__form.js-form .filter__checkbox').each(function () {
			if ($(this).find('label').length > 4) {
				$(this).addClass('more');
				$(this).find('label').eq(2).addClass('js-autoHeight');
				$('.js-autoHeight').each(function () {
					var elHeight = $(this).offset().top
					var elHeightParent = $(this).parent().offset().top
					var summ = elHeight - elHeightParent;
					$(this).closest('.filter__checkbox').css('height', summ + 27);
				})
			}
		})
		$('<span class="more-text js-more-text">Показать еще</span>').prependTo($('.more'));
		$('.js-more-text').click(function () {
			if ($(this).parent().hasClass('more')) {
				$(this).parent().removeClass('more');
				$(this).parent().css('height', 'auto');
				$(this).text('Свернуть');
			} else {
				$(this).parent().addClass('more');
				$(this).text('Показать еще');

				$(this).siblings('.js-autoHeight').each(function () {
					var elHeight = $(this).offset().top
					var elHeightParent = $(this).parent().offset().top
					var summ = elHeight - elHeightParent;
					$(this).closest('.filter__checkbox').css('height', summ + 27);
				})
			}
		})
	}
	if ($(".comparison").length) {
		if (windowWidth2 > 767) {
			console.log($(".products-line").outerHeight(true))
			$(".thead__comparison").css({
				'height': ($(".products-line").outerHeight() + 'px')
			});
			console.log('1')

		} else {
			console.log('2')
			$(".thead__comparison").css({
				'margin-bottom': ($(".products-line").innerHeight() + 'px')
			});
			$(".js-swiper-comparison").css({
				'margin-top': ($(".thead__comparison").height() - 10 + 'px')
			});
			$(".comparison .js-swiper-pagination").css({
				'top': ($(".thead__comparison").height() + 29 + 'px')
			});
			$(".comparison .js-swiper-prev").css({
				'top': ($(".thead__comparison").height() + 48 + 'px')
			});
			$(".comparison .js-swiper-next").css({
				'top': ($(".thead__comparison").height() + 48 + 'px')
			});
			$('.comparison__main .characteristic__row').each(function (index, el) {
				if ($(this).text().length > 60) {
					$(this).addClass('level')
					$('.js-swiper-comparison .characteristic__row').eq(index).addClass("level");
					console.log($(this).text())
				}
			});
		}
		$('.comparison__main .characteristic__row').each(function (index, el) {
			if ($(this).text().length > 60) {
				$(this).addClass('level')
				$('.js-swiper-comparison .characteristic__row').eq(index).addClass("level");
				console.log($(this).text())
			}
			if ($(this).text().length > 110) {
				$(this).addClass('level-2')
				$('.js-swiper-comparison .characteristic__row').eq(index).addClass("level-2");
				console.log($(this).text())
			}
		});
		$('.comparison__main .characteristic__row').each(function (index, el) {
			if (windowWidth2 >= 768) {
				var height = $('.js-swiper-comparison .characteristic__row').eq(index).outerHeight();
				$(el).outerHeight(height);
			} else {
				setTimeout(function () {
					var height = $('.js-swiper-comparison .characteristic__row').eq(index).outerHeight();
					$(el).outerHeight(height);
				}, 500);
				setTimeout(function () {
					var height = $('.js-swiper-comparison .characteristic__row').eq(index).outerHeight();
					$(el).outerHeight(height);
				}, 2000);
				setTimeout(function () {
					var height = $('.js-swiper-comparison .characteristic__row').eq(index).outerHeight();
					$(el).outerHeight(height);
				}, 4000);
			}
		});

		$('.comparison__item').click(function () {
			$('.comparison__item').removeClass('order')
			$(this).addClass('order')
		});

	}

	// $('.js-compare_block-change.active').parent().addClass('order');
	if ($('.comparison__link')) {
		$('.comparison__list').each(function () {
			console.log(this)
			if ($(this).find('.comparison__link').hasClass('active')) {
				$(this).parent().addClass('order');

			} else {
				$('.comparison__list .comparison__item:eq(0)').addClass('order')
			}
		})
		// if (select.hasClass('active')) {
		// 	console.log('1')
		// }
		$('.comparison__list').height($('.comparison__item.order').innerHeight());
	}
	// Убрать прикрепленный файл после отправки рекламации
	if ($('.MultiFile-intercepted').length) {
		$(document).ajaxSuccess(function () {
			let returnExchangeForm = document.querySelector('.return-exchange__form');

			returnExchangeForm.querySelectorAll('.MultiFile-remove').forEach(element => element.click())
			// if (returnExchangeForm) {
			// 	returnExchangeForm.querySelectorAll('.MultiFile-remove').forEach(element => console.log(element))
			// }
		});

	}


	var mySwiper45 = new Swiper(".product-card .js-container-upsale", {
		// Optional parameters
		// slidesPerGroup: 1,
		slidesPerView: 1,
		slidesPerColumn: 2,
		spaceBetween: 24,
		allowTouchMove: false,
		// direction: "horizontal",
		// loop: true,
		// If we need pagination
		navigation: {
			nextEl: ".upsale__next",
			prevEl: ".upsale__prev"
		},
		pagination: {
			el: ".swiper-pagination-upsale",
			clickable: true
		},
		breakpoints: {
			// when window width is <= 320px
			767: {
				allowTouchMove: true,
				slidesPerView: 1,
				slidesPerColumn: 2,
			}
		}
		// Navigation arrows
		// navigation: {
		// 	nextEl: ".main_next",
		// 	prevEl: ".main_prev"
		// },

		// And if we need scrollbar
		// scrollbar: {
		// 	el: ".swiper-scrollbar"
		// }
	});

});
