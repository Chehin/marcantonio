$(document).ready(function(){
            
    //Carousel de Productos
    $(".owl-carousel.productos-carousel").owlCarousel({
        loop: false,
        margin: 20,
        stagePadding: 20,
        center: false,
        nav: false,
        rewind: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: false,
        responsiveClass: true,
        responsiveRefreshRate: true,
        responsive : {
            0 : {
                items: 1
            },
            768 : {
                items: 2
            },
            960 : {
                items: 3
            },
            1200 : {
                items: 5
            },
            1920 : {
                items: 6
            }
        }
    });

    //Carousel de Marcas
    $(".owl-carousel.marcas-carousel").owlCarousel({
        loop: false,
        margin: 20,
        stagePadding: 20,
        center: false,
        nav: false,
        rewind: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: false,
        responsiveClass: true,
        responsiveRefreshRate: true,
        responsive : {
            0 : {
                items: 6
            },
            768 : {
                items: 8
            },
            960 : {
                items: 10
            },
            1200 : {
                items: 12
            },
            1920 : {
                items: 14
            }
        }
    });

    //Imagenes del Detalle Producto
    $(".owl-carousel.img-carousel").owlCarousel({
        loop: false,
        margin: 20,
        stagePadding: 20,
        center: false,
        nav: false,
        rewind: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: false,
        responsiveClass: true,
        responsiveRefreshRate: true,
        responsive : {
            0 : {
                items: 4
            },
            768 : {
                items: 4
            },
            960 : {
                items: 4
            },
            1200 : {
                items: 4
            },
            1920 : {
                items: 6
            }
        }
    });
});