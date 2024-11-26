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

   //Thumb
   let $sliderThumbsOptions = $(".product_image_slider__thumbs", $scope);
   let $sliderThumbs = $sliderThumbsOptions.data("pi_thumb");

   //Image
   let $sliderImagesOptions = $(".product_image_slider__container", $scope);
   let $sliderImagesData = $sliderImagesOptions.data("pi_image");

   // Load the thumbs Swiper first
   let sliderThumbsObj = swiperLoader(
      $(".product_image_slider__thumbs .swiper-container"),
      $sliderThumbs
   );

   sliderThumbsObj
      .then((swiperInstance) => {
         let $sliderImages = {
            ...$sliderImagesData,
            thumbs: {
               swiper: swiperInstance,
            },
         };

         // Initialize the main slider after setting the thumbs swiper
         swiperLoader(
            $(".product_image_slider__container .swiper-container"),
            $sliderImages
         )
            .then((mainSwiperInstance) => {})
            .catch((error) => {
               console.log("Error initializing main Swiper:", error);
            });
      })
      .catch((error) => {
         console.log("Error initializing Swiper thumbs:", error);
      });

   //Magnific Popup
   $(".product_image_slider__trigger a").on("click", function (e) {
      e.preventDefault();
      var items = [];
      $(".swiper-slide .image_slider__image img").each(function (index) {
         items.push({
            src: $(this).attr("src"),
         });
      });

      $.magnificPopup.open({
         items: items,
         mainClass: "eael-pi",
         gallery: {
            enabled: true,
         },
         type: "image",
      });
   });
};

jQuery(window).on("elementor/frontend/init", function () {
   elementorFrontend.hooks.addAction(
      "frontend/element_ready/eael-woo-product-images.default",
      WooProdectImage
   );
});
