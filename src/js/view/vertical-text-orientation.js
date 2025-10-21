let verticalTextOrientation = function ($scope, $) {
   let gradientColor = $scope.data("gradient_colors"),
       $scopeId = $scope.data("id");

   /**
    * For editor page - following hover-effect.js pattern
    */
   if (window.isEditMode) {
      if (window.isRunFirstTime === undefined && window.isEditMode || 1) {
         window.isRunFirstTime = true;
         var eaelEditModeSettings = [];

         function getVerticalTextSettings($el) {
            $.each($el, function (i, el) {
               let $getSettings = el.attributes.settings.attributes;
               if (el.attributes.elType === 'widget') {
                  if ($getSettings['eael_vertical_text_orientation_switch'] === 'yes' && $getSettings['eael_vto_writing_styling_type'] === 'gradient') {
                     eaelEditModeSettings[el.attributes.id] = el.attributes.settings.attributes;
                  }
               }

               if (el.attributes.elType === 'container') {
                  getVerticalTextSettings(el.attributes.elements.models);
               }

               if (el.attributes.elType === 'section') {
                  getVerticalTextSettings(el.attributes.elements.models);
               }

               if (el.attributes.elType === 'column') {
                  getVerticalTextSettings(el.attributes.elements.models);
               }
            });
         }

         getVerticalTextSettings(window.elementor.elements.models);
      }

      // Get settings for current widget from editor mode settings
      for (let key in eaelEditModeSettings) {
         if ($scopeId === key) {
            // Get gradient color from editor settings
            let editorGradientColor = eaelEditModeSettings[key]['eael_vto_writing_gradient_color_repeater'];

            // Create array of objects like frontend format
            let gradientArray = [];

            if (editorGradientColor && editorGradientColor.length > 0) {
               editorGradientColor.forEach((gradient) => {
                  if (gradient.attributes && gradient.attributes.eael_vto_writing_gradient_color && gradient?.attributes?.eael_vto_writing_gradient_color_location) {
                     gradientArray.push({
                        color: gradient.attributes.eael_vto_writing_gradient_color,
                        location: gradient.attributes.eael_vto_writing_gradient_color_location.size + gradient.attributes.eael_vto_writing_gradient_color_location.unit
                     });
                  }
               });

               // Update gradientColor with the properly formatted array
               if (gradientArray.length > 0) {
                  let gradientStops = [];

                  // Check if gradientColor exists and is an array
                  if (
                     gradientArray &&
                     Array.isArray(gradientArray) &&
                     gradientArray.length > 0
                  ) {
                     gradientArray.forEach((gradient) => {
                        if (gradient.color && gradient.location) {
                           gradientStops.push(
                              `${gradient.color} ${gradient.location}`
                           );
                        }
                     });

                     // Only apply gradient if we have valid gradient stops
                     if (gradientStops.length > 0) {
                        let linearGradient = `linear-gradient(90deg, ${gradientStops.join(
                           ", "
                        )})`;

                        const targetSelectors = [
                           ".elementor-heading-title",
                           ".elementor-text-editor p",
                           ".elementor-headline",
                           ".eael-dual-header",
                           ".eael-fancy-text-container",
                        ];
                        const fullSelectors = targetSelectors.map(selector => `.elementor-element-${$scopeId} ${selector}`).join(', ');
                        
                        $(fullSelectors).css({
                           background: linearGradient + " -100% / 200%",
                           "-webkit-background-clip": "text",
                           "-webkit-text-fill-color": "transparent",
                           "background-clip": "text",
                        });
                     }
                  }
               }
            }
         }
      }
   }

   /**
    * Frontend page - apply gradient styles
    */
   function applyGradientStyles() {
      let gradientStops = [];

      // Check if gradientColor exists and is an array
      if (gradientColor && Array.isArray(gradientColor) && gradientColor.length > 0) {
         gradientColor.forEach((gradient) => {
            if (gradient.color && gradient.location) {
               gradientStops.push(`${gradient.color} ${gradient.location}`);
            }
         });

         // Only apply gradient if we have valid gradient stops
         let gradientColorAngle = $scope.data("gradient_color_angle");
         if (gradientStops.length > 0) {
            let linearGradient = `linear-gradient(${gradientColorAngle}, ${gradientStops.join(
               ", "
            )})`;

            // Define target selectors
            const targetSelectors = [
               ".elementor-heading-title",
               "p",
               ".elementor-headline",
               ".eael-dual-header",
               ".eael-fancy-text-container",
            ];

            const fullSelector = targetSelectors.map(selector => `.elementor-element-${$scopeId} ${selector}`).join(', ');

            $(fullSelector).css({
               background: linearGradient + " -100% / 200%",
               "-webkit-background-clip": "text",
               "-webkit-text-fill-color": "transparent",
               "background-clip": "text",
            });
         }
      }
   }

   // Apply gradient styles for both frontend and editor
   applyGradientStyles();
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
