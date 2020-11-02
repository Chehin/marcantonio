$(document).ready(function(){
    $('#ex1').zoom();

    $('.itemFoto').click(function(){

        var imagenClick = $(this).find('img').attr('src');
        $('#ex1').find('img').attr('src', imagenClick);
        $('#ex1').zoom();
    })
});