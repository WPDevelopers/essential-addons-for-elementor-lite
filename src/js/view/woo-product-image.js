var WooProdectImage = function ($scope, $) {
   // let sliderThumbs = new Swiper(".slider__thumbs .swiper-container", {
   //    direction: "vertical",
   //    slidesPerView: 3,
   //    spaceBetween: 24,
   // });

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
      direction: "vertical",
      slidesPerView: 3,
      spaceBetween: 24,
      navigation: {
         nextEl: ".slider__next",
         prevEl: ".slider__prev",
      },
      freeMode: true,
   };

   // console.log(sliderThumbs);

   console.log(
      "Data",
      swiperLoader($(".slider__images .swiper-container"), sliderThumbs)
   );

   let sliderThumbsObj = swiperLoader(
      $(".slider__thumbs .swiper-container"),
      sliderThumbs
   );

   let sliderImages = {
      direction: "vertical",
      slidesPerView: 1,
      spaceBetween: 32,
      mousewheel: true,
      navigation: {
         nextEl: ".slider__next",
         prevEl: ".slider__prev",
      },
      grabCursor: true,
      thumbs: {
         swiper: sliderThumbsObj,
      },
   };

   // swiperLoader("slider__thumbs", sliderThumbs).then((productImage) => {});

   // console.log(sliderImagesObjThumb);

   let sliderImagesObj = swiperLoader(
      $(".slider__images .swiper-container"),
      sliderImages
   );
};

jQuery(window).on("elementor/frontend/init", function () {
   elementorFrontend.hooks.addAction(
      "frontend/element_ready/eael-woo-product-images.default",
      WooProdectImage
   );
});
