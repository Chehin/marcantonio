<div class="py-2 px-3 cart cart_box">
    <div class="table-responsive" style="max-height: 50vh;">
        <table>
            <tbody id="cart-product-list" class="table table-hover table-borderless">

            </tbody>
        </table>
    </div>
    <div class="cart-total-actions text-center my-3">
        <hr width="100%">
        <div class="cart-total">
            <strong>Subtotal:</strong>
            <span class="total"></span>
        </div>
    </div>
    <div class="">
        <a href="{{route('cart')}}" class="btn btn-custom"><i class="fa fa-shopping-cart"></i> Ver Carrito</a>
        <a href="{{route('procesar_pedido',['id'=>1])}}" class="btn btn-custom2"><i class="fa fa-check"></i> Comprar</a>
    </div>
</div>