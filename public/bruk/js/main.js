(function() {
	"use strict";

    window.onload = function(){

        //Header Sticky
        const getHeaderId = document.querySelector(".navbar-area");
        if (getHeaderId) {
            window.addEventListener('scroll', event => {
                const height = 150;
                const { scrollTop } = event.target.scrollingElement;
                document.querySelector('#navbar').classList.toggle('sticky', scrollTop >= height);
            });
        }
        
        // Back to Top
        const getId = document.getElementById("backtotop");
        if (getId) {
            const topbutton = document.getElementById("backtotop");
            topbutton.onclick = function (e) {
                window.scrollTo({ top: 0, behavior: "smooth" });
            };
            window.onscroll = function () {
                if (document.body.scrollTop > 200 || document.documentElement.scrollTop > 200) {
                    topbutton.style.opacity = "1";
                } else {
                    topbutton.style.opacity = "0";
                }
            };
        }
        
        // Preloader
        const getPreloaderId = document.getElementById('preloader');
        if (getPreloaderId) {
            getPreloaderId.style.display = 'none';
        }
    };

    //Hero Slider
    var myswiper = new Swiper(".hero-slider", {
        effect: "fade",
        loop: true,
        speed: 900,
        keyboard: {
            enabled: true,
            onlyInViewport: false
        },
        pagination: {
            el: ".hero-pagination",
            clickable: true,
        }
    });

    //Service Slider
    var myswiper = new Swiper(".service-slider", {
        slidesPerView: 1,
        speed: 1000,
        spaceBetween: 32,
        loop: false,
        autoHeight: true,
        mousewheel: {
            enabled: true,
            sensitivity: 4,
            releaseOnEdges: true
        },
        pagination: {
            el: ".service-pagination",
            clickable: true,
        },
        breakpoints: {
            992: {
                direction: "vertical",
            },
        },
    });
    document.addEventListener('DOMContentLoaded', function () {
        myswiper.on('reachBeginning', function () {
            window.scrollBy(0, -window.innerHeight);
        });
        myswiper.on('reachEnd', function () {
          window.scrollBy(0, window.innerHeight);
        });
    });

    //Project Slider
    var swiper = new Swiper(".project-slider", {
        spaceBetween: 0,
        grabCursor: true,
        loop: false,
        speed:1400,
        pagination: {
            el: ".project-pagination",
            clickable: true,
        },
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        breakpoints: {
            0: {
              slidesPerView: 1
            },
            768: {
              slidesPerView: 1.5,
              spaceBetween: 20
            },
            992: {
                slidesPerView: 2
            },
            1200: {
                slidesPerView: 2.4
            },
            1400: {
                slidesPerView: 2.5
            },
            1600: {
                slidesPerView: 3
            }
        },
    });

    //Testimonial Slider
    var swiper = new Swiper(".testimonial-slider-one", {
        slidesPerView: 1,
        spaceBetween: 25,
        grabCursor: true,
        loop: false,
        speed:1400,
        pagination: {
            el: ".testimonial-pagination",
            clickable: true,
        },
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        breakpoints: {
            0: {
              slidesPerView: 1,
            },
            768: {
              slidesPerView: 1.6
            },
            992: {
                slidesPerView: 2.2
            },
            1200: {
                slidesPerView: 2.6
            },
            1400: {
                slidesPerView: 2.5
            },
            1600: {
                slidesPerView: 2.8
            },
            1920: {
                slidesPerView: 3.3
            }
        },
    });
    var swiper = new Swiper(".testimonial-slider-two", {
        slidesPerView: 1,
        paceBetween: 25,
        grabCursor: true,
        loop: false,
        speed: 1400,
        pagination: {
            el: ".testimonial-pagination",
            clickable: true,
        },
        breakpoints: {
            0: {
                direction: 'horizontal'
            },
            768: {
                direction: 'vertical'
            },
        },
    });
    var swiper = new Swiper(".testimonial-slider-three", {
        slidesPerView: 1,
        paceBetween: 25,
        grabCursor: true,
        loop: false,
        speed:1400,
        pagination: {
            el: ".testimonial-pagination",
            clickable: true,
        },
    });

    //Brand Slider
    var swiper = new Swiper(".brand-slider", {
        spaceBetween: 15,
        loop: true,
        speed:1400,
        speed:13000,
        freemode: false,
		simulateTouch: false,
		autoplay: {
			delay: 1,
			disableOnInteraction: true
		},
        breakpoints: {
            0: {
              slidesPerView: 2
            },
            768: {
              slidesPerView: 4
            },
            992: {
                slidesPerView: 4
            },
            1200: {
                slidesPerView: 5
            },
            1400: {
                slidesPerView: 6
            },
            1600: {
                slidesPerView: 7
            },
            1920: {
                slidesPerView: 8
            }
        },
    });

    //Blog Slider
    var swiper = new Swiper(".blog-slider", {
        spaceBetween: 24,
        grabCursor: true,
        loop: false,
        autoHeight: true,
        speed:1400,
        autoplay: {
            delay: 3500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".blog-pagination",
            clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 1
            },
            768: {
                slidesPerView: 2
            },
            1200: {
                slidesPerView: 3
            }
        },
    });
    
    // Counter Js
    if ("IntersectionObserver" in window) {
        let counterObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                let counter = entry.target;
                let target = parseInt(counter.innerText);
                let step = target / 200;
                let current = 0;
                let timer = setInterval(function () {
                    current += step;
                    counter.innerText = Math.floor(current);
                    if (parseInt(counter.innerText) >= target) {
                    clearInterval(timer);
                    }
                }, 10);
                counterObserver.unobserve(counter);
                }
            });
        });
        let counters = document.querySelectorAll(".counter");
            counters.forEach(function (counter) {
            counterObserver.observe(counter);
        });
    }

    // Scrollcue
    scrollCue.init();

})();

    // Offcanvas Responsive Menu
    const list = document.querySelectorAll('.responsive-menu-list');
    function accordion(e) {
        e.stopPropagation(); 
        if(this.classList.contains('active')){
            this.classList.remove('active');
        }
        else if(this.parentElement.parentElement.classList.contains('active')){
            this.classList.add('active');
        }
        else {
            for(i=0; i < list.length; i++){
                list[i].classList.remove('active');
            }
            this.classList.add('active');
        }
    }
    for(i = 0; i < list.length; i++ ){
        list[i].addEventListener('click', accordion);
    }

try {

    // function to set a given theme/color-scheme
	function setTheme(themeName) {
		localStorage.setItem('bruk_theme', themeName);
		document.documentElement.className = themeName;
	}
	// function to toggle between light and dark theme
	function toggleTheme() {
		if (localStorage.getItem('bruk_theme') === 'theme-dark') {
			setTheme('theme-light');
		} else {
			setTheme('theme-dark');
		}
	}
	// Immediately invoked function to set the theme on initial load
	(function () {
		if (localStorage.getItem('bruk_theme') === 'theme-dark') {
			setTheme('theme-dark');
			document.querySelector('.slider-btn').checked = false;
		} else {
			setTheme('theme-light');
		document.querySelector('.slider-btn').checked = true;
		}
	})();
    
} catch (err) {}