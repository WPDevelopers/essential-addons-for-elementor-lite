let {log} = console;
let verticalTextOrientation = function ($scope, $) { 
   let gradientColor = $scope.data("gradient_colors");
   // log(gradientColor);
   //linear-gradient(90deg, #00f, #0ff, #00f)
   gradientColor.forEach((gradient) => {
      log(gradient);
   });
   $(".elementor-heading-title").css({
      "background-color": "yellow",
      "font-size": "200%",
   });

}

jQuery(window).on("elementor/frontend/init", function () {
   if (eael.elementStatusCheck("eaelVerticalTextOrientation")) {
      return false;
   }
   elementorFrontend.hooks.addAction(
      "frontend/element_ready/widget",
      verticalTextOrientation
   );
});
