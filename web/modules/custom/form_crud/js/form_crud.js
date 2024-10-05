(function ($, Drupal) {
  Drupal.behaviors.fullPageScrollMenu = {
    attach: function (context, settings) {
      $(
        ".block-views-blockhomepage-slick-carousel-block-homepage-slick-carousel .view-content"
      ).slick({
        autoplay: true,
        autoplaySpeed: 5000,
        arrows: false,
      });

      $(".slick-slide").each(function () {
        // Obtener el src de la imagen dentro del slick-slide
        var imgSrc = $(this).find("img").attr("src");

        // Aplicar la imagen como background del div slick-slide
        $(this).css({
          "background-image": "url(" + imgSrc + ")",
          "background-size": "cover",
          "background-position": "center",
          "background-repeat": "no-repeat",
        });

        // Ocultar la imagen original
        $(this).find("img").hide(); // Puedes eliminarla si no la necesitas, usando .remove()
      });
    },
  };
})(jQuery, Drupal);
