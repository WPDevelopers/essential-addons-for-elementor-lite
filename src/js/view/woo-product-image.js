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
      breakpoints: {
         0: {
            direction: "horizontal",
         },
         768: {
            direction: "vertical",
         },
      },
   };

   // Load the thumbs Swiper first
   let sliderThumbsObj = swiperLoader(
      $(".slider__thumbs .swiper-container"),
      sliderThumbs
   );

   sliderThumbsObj
      .then((swiperInstance) => {
         // console.log("Swiper thumbs instance initialized:", swiperInstance);

         // Define the main slider configuration after thumbs instance is ready
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
               swiper: swiperInstance, // Assign the resolved swiper instance here
            },
            breakpoints: {
               0: {
                  direction: "horizontal",
               },
               768: {
                  direction: "vertical",
               },
            },
         };

         // Initialize the main slider after setting the thumbs swiper
         swiperLoader($(".slider__images .swiper-container"), sliderImages)
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

   // sliderThumbsObj
   //    .then((swiperInstance) => {
   //       console.log("Swiper instance initialized:", swiperInstance);
   //       // Now you can work with the swiperInstance directly
   //       // For example, you can access swiperInstance.slides or other properties
   //    })
   //    .catch((error) => {
   //       console.log("Error initializing Swiper:", error);
   //    });

   // console.log(sliderThumbsObj);

   // async function fetchData() {
   //    try {
   //       const response = await fetch(sliderThumbsObj);
   //       // const data = await response.json();
   //       console.log(response);
   //    } catch (error) {
   //       console.log("Error", error);
   //    }
   // }
   // fetchData();

   // let sliderImages = {
   //    direction: "vertical",
   //    slidesPerView: 1,
   //    spaceBetween: 32,
   //    mousewheel: true,
   //    navigation: {
   //       nextEl: ".slider__next",
   //       prevEl: ".slider__prev",
   //    },
   //    grabCursor: true,
   //    thumbs: {
   //       swiper: sliderThumbsObj,
   //    },
   // };

   // swiperLoader("slider__thumbs", sliderThumbs).then((productImage) => {});

   // console.log(sliderImagesObjThumb);

   // let sliderImagesObj = swiperLoader(
   //    $(".slider__images .swiper-container"),
   //    sliderImages
   // );
};

jQuery(window).on("elementor/frontend/init", function () {
   elementorFrontend.hooks.addAction(
      "frontend/element_ready/eael-woo-product-images.default",
      WooProdectImage
   );
});
