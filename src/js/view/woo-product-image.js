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

   // Get unique ID for the slider
   const sliderId = $scope.data("id"); // Ensure your Elementor widget has a unique data attribute like data-id
   const sliderThumbSelector = `#slider-container-${sliderId} .product_image_slider__thumbs .swiper-container`;
   const sliderImageSelector = `#slider-container-${sliderId} .product_image_slider__container .swiper-container`;

   // Thumb slider options
   let $sliderThumbsOptions = $scope.find(".product_image_slider__thumbs");
   let $sliderThumbs = $sliderThumbsOptions.data("pi_thumb");

   // Image slider options
   let $sliderImagesOptions = $scope.find(".product_image_slider__container");
   let $sliderImagesData = $sliderImagesOptions.data("pi_image");

   // Set slider height dynamically
   $(window).on("load", function () {
      let $getImageHeight = $scope.find(".image_slider__image").height();
      $scope
         .find(
            ".eael-pi-thumb-left .product_image_slider .product_image_slider__thumbs, .eael-pi-thumb-right .product_image_slider .product_image_slider__thumbs"
         )
         .css("height", $getImageHeight);
   });

   // Load the thumbs Swiper first
   let sliderThumbsObj = swiperLoader($(sliderThumbSelector), $sliderThumbs);

   sliderThumbsObj
      .then((swiperInstance) => {
         let $sliderImages = {
            ...$sliderImagesData,
            ...($sliderThumbs.thumbnail === "yes" && {
               thumbs: {
                  swiper: swiperInstance,
               },
            }),
         };

         // Initialize the main slider after setting the thumbs swiper
         swiperLoader($(sliderImageSelector), $sliderImages)
            .then((mainSwiperInstance) => {})
            .catch((error) => {
               console.log("Error initializing main Swiper:", error);
            });
      })
      .catch((error) => {
         console.log("Error initializing Swiper thumbs:", error);
      });

   // Magnific Popup for the specific slider
   $scope.find(".product_image_slider__trigger a").on("click", function (e) {
      e.preventDefault();
      var items = [];
      $scope
         .find(".swiper-slide .image_slider__image img")
         .each(function (index) {
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
