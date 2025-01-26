var WooProdectImage = function ($scope, $) {
   var $productGallery = $(".eael-single-product-images");

   let $sliderThumbsOptions = $(".product_image_slider__thumbs", $scope);
   let $sliderThumbs = $sliderThumbsOptions.data("pi_thumb");

   var $productGalleryImage = $(
      ".swiper-wrapper .swiper-slide:first-child .image_slider__image > img",
      $productGallery
   );

   var $productThumbImage = $(
      ".swiper-wrapper .swiper-slide:first-child .product_image_slider__thumbs__image > img",
      $productGallery
   );

   var originalImage = {
      src: $productGalleryImage.attr("src"),
      srcset: $productGalleryImage.attr("srcset"),
      sizes: $productGalleryImage.attr("sizes"),
   };

   var originalThumbImage = {
      src: $productThumbImage.attr("src"),
      srcset: $productThumbImage.attr("srcset"),
      sizes: $productThumbImage.attr("sizes"),
   };

   // Listen for show_variation event
   $(".variations_form").on("show_variation", function (event, variation) {
      if (variation && variation.image && variation.image.src) {
         eaelVariationProductImage(variation.image);
         $(".swiper-container", $scope)[0].swiper.autoplay.stop();
         $(".swiper-container", $scope)[0].swiper.slideTo(0);
         $(".swiper-container", $scope)[1].swiper.autoplay.stop();
         $(".swiper-container", $scope)[1].swiper.slideTo(0);
      }
   });

   $(".variations_form").on("hide_variation", function () {
      eaelRemoveVariationProductImage();
      if ($sliderThumbs.autoplay !== undefined) {
         $(".swiper-container", $scope)[0].swiper.autoplay.start();
         $(".swiper-container", $scope)[1].swiper.autoplay.start();
      }
   });

   $(".variations_form").on("reset_image", function () {
      eaelRemoveVariationProductImage();
      if ($sliderThumbs.autoplay !== undefined) {
         $(".swiper-container", $scope)[0].swiper.autoplay.start();
         $(".swiper-container", $scope)[1].swiper.autoplay.start();
      }
   });

   const eaelVariationProductImage = (variationImage) => {
      $productGalleryImage
         .attr("src", variationImage.src)
         .attr("srcset", variationImage.srcset)
         .attr("sizes", variationImage.sizes)
         .attr("data-src", variationImage.src)
         .attr("data-large_image", variationImage.full_src);

      $productThumbImage
         .attr("src", variationImage.src)
         .attr("srcset", variationImage.srcset)
         .attr("sizes", variationImage.sizes)
         .attr("data-src", variationImage.src)
         .attr("data-large_image", variationImage.full_src);
   };

   const eaelRemoveVariationProductImage = () => {
      $productGalleryImage.fadeOut(100, function () {
         $productGalleryImage
            .attr("src", originalImage.src)
            .attr("srcset", originalImage.srcset)
            .attr("sizes", originalImage.sizes)
            .removeAttr("data-src")
            .removeAttr("data-large_image");
         $productGalleryImage.fadeIn(100);
      });

      $productThumbImage.fadeOut(100, function () {
         $productThumbImage
            .attr("src", originalThumbImage.src)
            .attr("srcset", originalThumbImage.srcset)
            .attr("sizes", originalThumbImage.sizes)
            .removeAttr("data-src")
            .removeAttr("data-large_image");
         $productThumbImage.fadeIn(100);
      });
   };

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
   // let $sliderThumbsOptions = $(".product_image_slider__thumbs", $scope);
   // let $sliderThumbs = $sliderThumbsOptions.data("pi_thumb");
   // console.log("Item", $sliderThumbs);

   // Image slider options
   let $sliderImagesOptions = $(".product_image_slider__container", $scope);
   let $sliderImagesData = $sliderImagesOptions.data("pi_image");

   // Set slider height dynamically
   $(window).on("load", function () {
      // Check if the screen width is less than or equal to 767px
      if (window.matchMedia("(max-width: 767px)").matches) {
         // For small screens
         let getImageHeight = $(".image_slider__image", $scope).height();
         let newThumbHeight = $sliderThumbs.slidesPerView * 50;
         let compareHeight = Math.min(newThumbHeight, getImageHeight);

         $(
            ".eael-pi-thumb-left .product_image_slider .product_image_slider__thumbs, .eael-pi-thumb-right .product_image_slider .product_image_slider__thumbs",
            $scope
         ).css("height", compareHeight);
      } else {
         // For larger screens
         let getImageHeight = $(".image_slider__image", $scope).height();
         let newThumbHeight = $sliderThumbs.slidesPerView * 100;
         let compareHeight = Math.min(newThumbHeight, getImageHeight);

         $(
            ".eael-pi-thumb-left .product_image_slider .product_image_slider__thumbs, .eael-pi-thumb-right .product_image_slider .product_image_slider__thumbs",
            $scope
         ).css("height", compareHeight);
      }
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
   $(".product_image_slider__trigger a", $scope).on("click", function (e) {
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
