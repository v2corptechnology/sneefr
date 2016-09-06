$(function () {
    // Enable tooltips on common sneefers
    $('[data-toggle="tooltip"]').tooltip();

    // Display a flexible image gallery
    new flexImages({ selector: '.flex-images', rowHeight: 150 });

    // Display slider when clicking on thumbs
    baguetteBox.run('.gallery');

    // Listen for the click on the cover to display the slider
    document.querySelector('.cover').addEventListener("click", function() {
        document.querySelector('.gallery .item').click();
    });
});
