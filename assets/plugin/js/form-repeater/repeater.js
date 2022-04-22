$(document).ready(function () {
    $('.repeater').repeater({
        // (Required if there is a nested repeater)
        // Specify the configuration of the nested repeaters.
        // Nested configuration follows the same format as the base configuration,
        // supporting options "defaultValues", "show", "hide", etc.
        // Nested repeaters additionally require a "selector" field.
        repeaters: [{
            // (Required)
            // Specify the jQuery selector for this nested repeater
            selector: '.inner-repeater'
        }]
    });
});
