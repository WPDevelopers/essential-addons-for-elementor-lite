let LiquidGlassEffectHandler = function ($scope, $) {
   let $scopeId = $scope.data("id"),
      $glassEffects = $scope.data("eael_glass_effects");

   // Check if glass effects data exists
   if (!$glassEffects) {
      return;
   }

   // Apply SVG filter attributes
   const turbulenceElement = document.querySelector("feTurbulence");
   const displacementElement = document.querySelector("feDisplacementMap");

   if (turbulenceElement) {
      turbulenceElement.setAttribute(
         "baseFrequency",
         `${$glassEffects.freq} ${$glassEffects.freq}`
      );
   }

   if (displacementElement) {
      displacementElement.setAttribute("scale", $glassEffects.scale);
   }
};

jQuery(window).on("elementor/frontend/init", function () {
   elementorFrontend.hooks.addAction(
      "frontend/element_ready/widget",
      LiquidGlassEffectHandler
   );
});
