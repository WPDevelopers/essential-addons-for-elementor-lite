var EaelParticlesHandler = function($scope, $) {
    var sectionId = $scope.data("id"),
        editMode = elementorFrontend.isEditMode(),
        theme = $scope.data("theme"),
        settings;

    $scope.addClass("eael-particles-section");

    if (editMode) {
        var editorElements = null,
            particleArgs = {},
            settings = {};

        if (!window.elementor.hasOwnProperty("elements")) {
            return false;
        }

        editorElements = window.elementor.elements;

        if (!editorElements.models) {
            return false;
        }

        $.each(editorElements.models, function(i, el) {
            if (sectionId == el.id) {
                particleArgs = el.attributes.settings.attributes;
            } else if (
                el.id == $scope.closest(".elementor-top-section").data("id")
            ) {
                $.each(el.attributes.elements.models, function(i, col) {
                    $.each(col.attributes.elements.models, function(i, subSec) {
                        particleArgs = subSec.attributes.settings.attributes;
                    });
                });
            }
        });

        settings.switch = particleArgs["eael_particle_switch"];
        settings.themeSource = particleArgs["eael_particle_theme_from"];

        if (settings.themeSource == "presets") {
            settings.selected_theme =
                ParticleThemesData[particleArgs["eael_particle_preset_themes"]];
        }

        if (
            settings.themeSource == "custom" &&
            "" !== particleArgs["eael_particles_custom_style"]
        ) {
            settings.selected_theme =
                particleArgs["eael_particles_custom_style"];
        }

        if (0 !== settings.length) {
            settings = settings;
        }
    } else {
        if (typeof theme != "undefined" && theme !== "") {
            particlesJS("eael-section-particles-" + sectionId, theme);
        }
    }

    if (!editMode || !settings) {
        return false;
    }

    if (settings.switch == "yes") {
        if (
            settings.themeSource === "presets" ||
            (settings.themeSource === "custom" &&
                "" !== settings.selected_theme)
        ) {
            $scope.attr("id", "eael-section-particles-" + sectionId);
            if (
                typeof particlesJS !== "undefined" &&
                $.isFunction(particlesJS)
            ) {
                particlesJS(
                    "eael-section-particles-" + sectionId,
                    JSON.parse(settings.selected_theme)
                );
                $scope.children("canvas.particles-js-canvas-el").css({
                    position: "absolute",
                    top: 0
                });
            }
        }
    } else {
        $scope.removeClass("eael-particles-section");
    }
};
elementorFrontend.hooks.addAction(
    "frontend/element_ready/section",
    EaelParticlesHandler
);
