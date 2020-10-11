ea.hooks.addAction("editMode.init", "ea", () => {
    elementor.settings.page.addChangeCallback("eael_ext_table_of_content", function (newValue) {
        elementor.settings.page.setSettings(
            "eael_ext_table_of_content",
            newValue
        );
        var save = elementor.saver.update.apply();
        save.then(function (){
            elementor.reloadPreview();
        })
    });
});

