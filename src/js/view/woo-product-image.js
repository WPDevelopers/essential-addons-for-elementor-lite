var WooProdectImage = function ($scope, $) {
   const swiperLoader = (swiperElement, swiperConfig) => {
      if ("undefined" === typeof Swiper || "function" === typeof Swiper) {
         const asyncSwiper = elementorFrontend.utils.swiper;
         return new asyncSwiper(swiperElement, swiperConfig).then(
            (newSwiperInstance) => {
               return newSwiperInstance;
            }
         );
      } else {
         return swiperPromise(swiperElement, swiperConfig);
      }
   };

   const swiperPromise = (swiperElement, swiperConfig) => {
      return new Promise((resolve, reject) => {
         const swiperInstance = new Swiper(swiperElement, swiperConfig);
         resolve(swiperInstance);
      });
   };

   let sliderThumbs = {
      // direction: "vertical",
      slidesPerView: 3,
      spaceBetween: 24,
      navigation: {
         nextEl: ".product_image_slider__next",
         prevEl: ".product_image_slider__prev",
      },
      freeMode: true,
   };

   // Load the thumbs Swiper first
   let sliderThumbsObj = swiperLoader(
      $(".product_image_slider__thumbs .swiper-container"),
      sliderThumbs
   );

   sliderThumbsObj
      .then((swiperInstance) => {
         let sliderImages = {
            // direction: "vertical",
            slidesPerView: 1,
            spaceBetween: 32,
            // mousewheel: true,
            navigation: {
               nextEl: ".product_image_slider__next",
               prevEl: ".product_image_slider__prev",
            },
            // grabCursor: true,
            // loop: true,
            // autoplay: {
            //    delay: 500,
            //    disableOnInteraction: false,
            // },
            thumbs: {
               swiper: swiperInstance,
            },
         };

         // Initialize the main slider after setting the thumbs swiper
         swiperLoader(
            $(".product_image_slider__container .swiper-container"),
            sliderImages
         )
            .then((mainSwiperInstance) => {
               // console.log(
               //    "Main swiper instance initialized:",
               //    mainSwiperInstance
               // );
            })
            .catch((error) => {
               console.log("Error initializing main Swiper:", error);
            });
      })
      .catch((error) => {
         console.log("Error initializing Swiper thumbs:", error);
      });
};

jQuery(window).on("elementor/frontend/init", function () {
   elementorFrontend.hooks.addAction(
      "frontend/element_ready/eael-woo-product-images.default",
      WooProdectImage
   );
});
