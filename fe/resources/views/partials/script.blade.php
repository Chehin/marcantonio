
    {!!Html::script('vendor/jquery/jquery.min.js')!!}
    {!!Html::script('https://code.jquery.com/ui/1.11.4/jquery-ui.js')!!}

    {!!Html::script('vendor/jquery/jquery.validate.js')!!}

    {!!Html::script('vendor/jquery/jquery.zoom.js')!!}

{{--    {!!Html::script('js/jquery.flexslider.js')!!}--}}

    {!!Html::script('vendor/owlCarousel2/owl.carousel.js')!!}

    {!!Html::script('vendor/bootstrap/js/bootstrap.min.js')!!}

    {!!Html::script('https://unpkg.com/@popperjs/core@2')!!}

    {!!Html::script('js/corousel-setting.js')!!}

    {!!Html::script('js/zoom-detalle.js')!!}

    {!!Html::script('js/custom.js')!!}
    <!-- Igualar altura elementos -->
    {!!Html::script('js/heights.js')!!}

    {!!Html::script('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js')!!}

{{--    {!!Html::script('js/bootstrap-dropdownhover.min.js')!!}--}}

    <!-- Jquery Magnifik -->
    <script type="text/javascript" src="js/magnifik.min.js"></script>

    <script>
      $( function() {
        $( "#q" ).autocomplete({
          source: "search",
          minLength: 2,
          select: function( event, ui ) {
            $('.page-loader').fadeIn();
                  window.location.replace('producto/'+ui.item.id+'/'+ui.item.label);
          },
          html: true, 
          open: function(event, ui) {
            $(".ui-autocomplete").css("z-index", 5000);
          }
        })
        .autocomplete( "instance" )._renderItem = function( ul, item ) {
          return $( "<li><div><img src='"+item.img+"'><span>"+item.value+"</span></div></li>" ).appendTo( ul );
        };
        @if(Carbon\Carbon::now('America/Argentina/Buenos_Aires') < Carbon\Carbon::create(2019, 11, 04, 0, 0, 0, 'America/Argentina/Buenos_Aires'))
        //contador
        var dthen1 = new Date("11/04/19 00:00:00 AM");
        //var dthen1 = new Date();
        start = new Date();
        start_date = Date.parse(start);
        var dnow1 = new Date(start_date);
        if(CountStepper>0)
        ddiff= new Date((dnow1)-(dthen1));
        else
        ddiff = new Date((dthen1)-(dnow1));
        gsecs1 = Math.floor(ddiff.valueOf()/1000);
        var iid1 = "countbox_1";
        CountBack_slider(gsecs1,"countbox_1", 1);
        @endif
      });

    /*  $(function(){

          $("#btnMenu").on("mouseover",function(e){
              $('#btnMenu').trigger( "click" );
          });

          $(".dropdown-menu > li > a.trigger").on("mouseenter",function(e){
              var current=$(this).next();
              var grandparent=$(this).parent().parent();
              if($(this).hasClass('left-caret')||$(this).hasClass('right-caret'))
                  $(this).toggleClass('right-caret left-caret');
              grandparent.find('.left-caret').not(this).toggleClass('right-caret left-caret');
              grandparent.find(".sub-menu:visible").not(current).hide();
              current.toggle();
              e.stopPropagation();
          });
     /!*     $(".dropdown-menu > li > a:not(.trigger)").on("mouseenter",function(){
              var root=$(this).closest('.dropdown');
              root.find('.left-caret').toggleClass('right-caret left-caret');
              root.find('.sub-menu:visible').hide();
          });*!/
      });*/
    </script>

@yield('scriptExtra')