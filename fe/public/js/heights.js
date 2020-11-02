//Iguala la altura de elementos
function igualarAltura(claseDiv){    
    var heights = $(claseDiv).map(function() {
        return $(this).height();
    }).get();

    maxHeight = Math.max.apply(null, heights);

    $(claseDiv).height(maxHeight);
}

$(document).ready(function(){
    
    igualarAltura(".card-text");
    igualarAltura(".list-productos .item-producto .product-name");
    igualarAltura(".list-productos .item-producto .product-tags");
    igualarAltura("#destacados .item-producto .product-name");
    igualarAltura("#destacados .item-producto .product-tags");
    igualarAltura("#masvistos .item-producto .product-name");
    igualarAltura("#masvistos .item-producto .product-tags");
    igualarAltura("#masvendidos .item-producto .product-name");
    igualarAltura("#masvendidos .item-producto .product-tags");
    igualarAltura("#prodRelacionados .item-producto .product-name");
    igualarAltura("#prodRelacionados .item-producto .product-tags");
    igualarAltura(".card .item-producto .card-body");
    igualarAltura(".product-item");
    
    //Altura input group
    var height = $('.item-producto .input-group #qty').siblings('.input-group-append').find('.btn-group-vertical').height();
    $('.item-producto .input-group #qty').css('height',height);
    $('.item-producto .space-input-cant').css('height',height);
});
