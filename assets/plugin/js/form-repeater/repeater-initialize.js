// form repeater Initialization
$('.repeater-default').repeater({
    show: function () {
        $(this).slideDown();
    },
    hide: function (deleteElement) {
        if (confirm('Are you sure you want to delete this element?')) {
            $(this).slideUp(deleteElement);
        }
    }
});