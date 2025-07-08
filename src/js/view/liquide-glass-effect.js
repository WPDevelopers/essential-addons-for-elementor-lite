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

   // Display values in editor mode only
   if (elementorFrontend.isEditMode()) {
      // Store the scope for later updates
      $scope.attr("data-eael-glass-widget", "true");
   }
};

jQuery(window).on("elementor/frontend/init", function () {
   elementorFrontend.hooks.addAction(
      "frontend/element_ready/widget",
      LiquidGlassEffectHandler
   );

   // Add real-time updates for editor mode
   if (elementorFrontend.isEditMode()) {
      // Function to get current control values from elementor model
      function getCurrentGlassEffectValues(elementView) {
         const settings = elementView.model.get("settings");
         const freq = settings.get("eael_liquid_glass_effect_noise_freq");
         const strength = settings.get(
            "eael_liquid_glass_effect_noise_strength"
         );

         return {
            freq: freq && freq.size ? freq.size : 0.008,
            scale: strength && strength.size ? strength.size : 77,
         };
      }

      // Function to update SVG filters and display
      function updateGlassEffectDisplay(elementView) {
         const $element = elementView.$el;
         const glassEffectsData = getCurrentGlassEffectValues(elementView);

         // Update SVG filter attributes
         const turbulenceElement = document.querySelector("feTurbulence");
         const displacementElement =
            document.querySelector("feDisplacementMap");

         if (turbulenceElement) {
            turbulenceElement.setAttribute(
               "baseFrequency",
               `${glassEffectsData.freq} ${glassEffectsData.freq}`
            );
         }

         if (displacementElement) {
            displacementElement.setAttribute("scale", glassEffectsData.scale);
         }
      }

      // Listen for control changes in the editor
      elementor.channels.editor.on(
         "change",
         function (controlView, elementView) {
            if (!elementView) return;

            const controlName = controlView.model.get("name");

            // Check if the changed control affects liquid glass effect
            if (
               controlName === "eael_liquid_glass_effect_noise_freq" ||
               controlName === "eael_liquid_glass_effect_noise_strength"
            ) {
               // Update immediately without delay
               updateGlassEffectDisplay(elementView);
            }
         }
      );

      // Alternative approach: Listen for any setting change and check if it's our widget
      elementor.channels.editor.on("change", function (controlView) {
         const controlName = controlView.model.get("name");

         if (
            controlName === "eael_liquid_glass_effect_noise_freq" ||
            controlName === "eael_liquid_glass_effect_noise_strength"
         ) {
            // Find the current editing element
            const currentElement = elementor.getCurrentElement();
            if (currentElement && currentElement.view) {
               updateGlassEffectDisplay(currentElement.view);
            }
         }
      });

      // Listen for panel changes to update display when switching between elements
      elementor.channels.editor.on("panel:change", function () {
         setTimeout(function () {
            const currentElement = elementor.getCurrentElement();
            if (currentElement && currentElement.view) {
               const settings = currentElement.view.model.get("settings");
               if (settings.get("eael_liquid_glass_effect_switch") === "yes") {
                  updateGlassEffectDisplay(currentElement.view);
               }
            }
         }, 100);
      });
   }
});
