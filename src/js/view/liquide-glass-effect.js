let LiquidGlassEffectHandler = function ($scope, $) {
   let $scopeId = $scope.data("id"),
      $glassEffects = $scope.data("eael_glass_effects");

   // Check if glass effects data exists
   if (!$glassEffects) {
      return;
   }

   // Apply SVG filter attributes
   const glassEffects = {
      "eael-glass-distortion1": {
         freq: $glassEffects.freq,
         scale: $glassEffects.scale,
      },
      "eael-glass-distortion2": {
         freq: $glassEffects.freq,
         scale: $glassEffects.scale,
      },
      "eael-glass-distortion3": {
         freq: $glassEffects.freq,
         scale: $glassEffects.scale,
      },
   };

   // Function to update SVG filter attributes
   function updateFilterAttributes(filterId, { freq, scale }) {
      const filterElement = document.querySelector(`#${filterId}`);
      if (!filterElement) {
         return;
      }

      const turbulenceElement = filterElement.querySelector("feTurbulence");
      if (turbulenceElement) {
         turbulenceElement.setAttribute("baseFrequency", `${freq} ${freq}`);
      }

      const displacementElement =
         filterElement.querySelector("feDisplacementMap");
      if (displacementElement) {
         displacementElement.setAttribute("scale", scale);
      }
   }

   // Apply to all filters
   Object.entries(glassEffects).forEach(([id, config]) => {
      updateFilterAttributes(id, config);
   });

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
         const effectType = settings.get("eael_liquid_glass_effect");

         let freq, strength;

         // Get effect-specific noise settings based on the selected effect
         switch (effectType) {
            case "effect4":
               freq = settings.get(
                  "eael_liquid_glass_effect_noise_freq_effect4"
               );
               strength = settings.get(
                  "eael_liquid_glass_effect_noise_strength_effect4"
               );
               break;
            case "effect5":
               freq = settings.get(
                  "eael_liquid_glass_effect_noise_freq_effect5"
               );
               strength = settings.get(
                  "eael_liquid_glass_effect_noise_strength_effect5"
               );
               break;
            case "effect6":
               freq = settings.get(
                  "eael_liquid_glass_effect_noise_freq_effect6"
               );
               strength = settings.get(
                  "eael_liquid_glass_effect_noise_strength_effect6"
               );
               break;
            default:
               freq = null;
               strength = null;
         }

         return {
            freq: freq && freq.size ? freq.size : 0.008,
            scale: strength && strength.size ? strength.size : 77,
         };
      }

      // Function to update SVG filters and display
      function updateGlassEffectDisplay(elementView) {
         const glassEffectsData = getCurrentGlassEffectValues(elementView);

         // Update SVG filter attributes
         const glassEffects = {
            "eael-glass-distortion1": {
               freq: glassEffectsData.freq,
               scale: glassEffectsData.scale,
            },
            "eael-glass-distortion2": {
               freq: glassEffectsData.freq,
               scale: glassEffectsData.scale,
            },
            "eael-glass-distortion3": {
               freq: glassEffectsData.freq,
               scale: glassEffectsData.scale,
            },
         };

         // Function to update SVG filter attributes
         function updateFilterAttributes(filterId, { freq, scale }) {
            const filterElement = document.querySelector(`#${filterId}`);
            if (!filterElement) {
               return;
            }

            const turbulenceElement =
               filterElement.querySelector("feTurbulence");
            if (turbulenceElement) {
               turbulenceElement.setAttribute(
                  "baseFrequency",
                  `${freq} ${freq}`
               );
            }

            const displacementElement =
               filterElement.querySelector("feDisplacementMap");
            if (displacementElement) {
               displacementElement.setAttribute("scale", scale);
            }
         }

         // Apply to all filters
         Object.entries(glassEffects).forEach(([id, config]) => {
            updateFilterAttributes(id, config);
         });
      }

      // Listen for control changes in the editor
      elementor.channels.editor.on(
         "change",
         function (controlView, elementView) {
            if (!elementView) return;

            const controlName = controlView.model.get("name");

            // Check if the changed control affects liquid glass effect
            if (
               controlName === "eael_liquid_glass_effect_noise_freq_effect4" ||
               controlName ===
                  "eael_liquid_glass_effect_noise_strength_effect4" ||
               controlName === "eael_liquid_glass_effect_noise_freq_effect5" ||
               controlName ===
                  "eael_liquid_glass_effect_noise_strength_effect5" ||
               controlName === "eael_liquid_glass_effect_noise_freq_effect6" ||
               controlName === "eael_liquid_glass_effect_noise_strength_effect6"
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
            controlName === "eael_liquid_glass_effect_noise_freq_effect4" ||
            controlName === "eael_liquid_glass_effect_noise_strength_effect4" ||
            controlName === "eael_liquid_glass_effect_noise_freq_effect5" ||
            controlName === "eael_liquid_glass_effect_noise_strength_effect5" ||
            controlName === "eael_liquid_glass_effect_noise_freq_effect6" ||
            controlName === "eael_liquid_glass_effect_noise_strength_effect6"
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
