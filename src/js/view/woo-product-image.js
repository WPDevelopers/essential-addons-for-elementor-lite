var WooProdectImage = function ($scope, $) {
   //Select key elements
   const $productGallery = $(".eael-single-product-images");
   const $sliderThumbsOptions = $(".product_image_slider__thumbs", $scope);
   const $sliderThumbs = $sliderThumbsOptions.data("pi_thumb");

   //Find initial images
   const $productGalleryImage = $(
      ".swiper-wrapper .swiper-slide:first-child .image_slider__image > img",
      $productGallery
   );
   const $productThumbImage = $(
      ".swiper-wrapper .swiper-slide:first-child .product_image_slider__thumbs__image > img",
      $productGallery
   );

   //Store original image attributes
   const originalImage = getImageAttributes($productGalleryImage);
   const originalThumbImage = getImageAttributes($productThumbImage);

   //Helper function to get image attributes
   function getImageAttributes($image) {
      return {
         src: $image.attr("src"),
         srcset: $image.attr("srcset"),
         sizes: $image.attr("sizes"),
      };
   }

   // Listen for show variation event
   $(".variations_form").on("show_variation", handleShowVariation);

   //Event handler for showing variation
   function handleShowVariation(event, variation) {
      if (variation?.image?.src) {
         updateProductImage(variation.image);
         stopSliders();
      }
   }

   //Stop sliders
   function stopSliders() {
      toggleSliderAutoplay("stop");
   }

   //Toggle slider autoplay
   function toggleSliderAutoplay(action) {
      const sliders = $(".swiper-container", $scope);
      sliders.each((index, slider) => {
         slider.swiper.autoplay[action]();
         slider.swiper.slideTo(0);
      });
   }

   //Update product iamges with variation images
   function updateProductImage(variationImage) {
      setImageAttributes($productGalleryImage, variationImage);
      setThumbImageAttributes($productThumbImage, variationImage);
   }

   //Set image attributes
   function setImageAttributes($image, imageAttributes) {
      $image
         .attr("src", imageAttributes.src)
         .attr("srcset", imageAttributes.srcset)
         .attr("sizes", imageAttributes.sizes)
         .attr("data-src", imageAttributes.src)
         .attr("data-large_image", imageAttributes.full_src);
   }

   //Set thumb image attributes
   function setThumbImageAttributes($image, imageAttributes) {
      $image
         .attr("src", imageAttributes.gallery_thumbnail_src)
         .attr("srcset", imageAttributes.gallery_thumbnail_src)
         .attr("sizes", imageAttributes.gallery_thumbnail_src_h);
   }

   // Listen for hide variation or reset image event
   $(".variations_form").on("hide_variation reset_image", handleResetVariation);

   //Event handler for reseting variation
   function handleResetVariation() {
      resetProductImages();
      resumeSliders();
   }

   //Resume sliders
   function resumeSliders() {
      if ($sliderThumbs.autoplay !== undefined) {
         toggleSliderAutoplay("start");
      }
   }

   //Reset product image to original state
   function resetProductImages() {
      resetProductImage($productGalleryImage, originalImage);
      resetProductImage($productThumbImage, originalThumbImage);
   }

   //Reset a single image with fade effect
   function resetProductImage($image, originalAttributes) {
      $image.fadeOut(100, function () {
         $image
            .attr("src", originalAttributes.src)
            .attr("srcset", originalAttributes.srcset)
            .attr("sizes", originalAttributes.sizes)
            .removeAttr("data-src")
            .removeAttr("data-large_image");
         $image.fadeIn(100);
      });
   }

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
   let $height_for_mobile = $sliderThumbsOptions.data("for_mobile");

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
         let newThumbHeight = $sliderThumbs.slidesPerView * $height_for_mobile;
         let compareHeight = Math.min(newThumbHeight, getImageHeight);

         $(
            ".eael-pi-thumb-left .product_image_slider .product_image_slider__thumbs, .eael-pi-thumb-right .product_image_slider .product_image_slider__thumbs",
            $scope
         ).css("height", compareHeight);

         $scope
            .find(
               ".eael-pi-thumb-bottom .product_image_slider .product_image_slider__thumbs"
            )
            .css("width", compareHeight);
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
