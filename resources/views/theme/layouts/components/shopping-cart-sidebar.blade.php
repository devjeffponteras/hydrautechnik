<div id="side-panel" class="bg-white">

	<!-- Cart Side Panel Close Icon
	============================================= -->
	<div id="side-panel-trigger-close" class="side-panel-trigger"><a href="#"><i class="icon-line-cross"></i></a></div>

	<div class="side-panel-wrap">

		<div class="top-cart d-flex flex-column h-100">
			<div class="top-cart-title">
				<h4>Shopping Cart</h4>
				{{-- <h4>Shopping Cart <small class="bg-color-light icon-stacked text-center rounded-circle color">{{ Setting::EcommerceCartTotalItems() }}</small></h4> --}}
			</div>

			<!-- Cart Items
			============================================= -->
			<div class="top-cart-items" id="top-cart-items">
				@php
					if(Auth::check()){
						$cartx = \App\Models\Ecommerce\Cart::where('user_id', auth()->user()->id)->where('qty', '>', 0)->get();
					} else {
						$cartx = session('cart', []);
					}
					
					$carttotal = 0;
					$totalsaved = 0;
				@endphp

				@foreach($cartx as $cart)

					@php
						$carttotal += $cart->price*$cart->qty;
						$totalsaved += $cart->product->price - $cart->product->discount_price;
					@endphp

					<div class="top-cart-item" data-product-id="{{ $cart->product_id }}">
						<div class="top-cart-item-image border-0">
							<a href="#"><img src="{{ asset('storage/products/'.$cart->product->photoPrimary) }}" alt="Cart Image 1" /></a>
						</div>
						<div class="top-cart-item-desc">
							<div class="top-cart-item-desc-title">
								<a href="#" class="fw-medium">{{$cart->product->name}}</a>
								<span class="top-cart-item-price d-block">₱{{number_format($cart->price,2)}}</span>

								<div class="d-flex mt-2">
									<div class="quantity">
										<input type="button" value="-" class="minus" onclick="minus_qty('{{$cart->id}}');">
										<input type="text" name="quantity[]" class="qty" value="{{$cart->qty}}" id="quantity{{$cart->id}}"/>
										<input type="button" value="+" class="plus" onclick="plus_qty('{{$cart->id}}');">

										<input type="hidden" id="orderID{{$cart->id}}" value="{{$cart->product_id}}">
										<input type="hidden" id="prevqty{{$cart->id}}" value="{{ $cart->qty }}">
										<input type="hidden" id="maxorder{{$cart->id}}" value="{{ $cart->product->Inventory }}">
										<input type="hidden" id="cartItemPrice{{$cart->id}}" value="{{ $cart->price }}">
									</div>
									

									<div class="cart-product-subtotal">
										<input type="hidden" id="product_name_{{$cart->id}}" value="{{$cart->product->name}}">
										<input type="hidden" name="product_price[]" id="input_order{{$cart->id}}_product_price" value="{{$cart->product->discountedprice}}">
	
	
										<input type="hidden" id="price{{$cart->id}}" value="{{number_format($cart->product->discountedprice,2,'.','')}}">
										<input type="hidden" class="input_product_total_price" data-id="{{$cart->id}}" data-productid="{{$cart->product_id}}" id="input_order{{$cart->id}}_product_total_price" value="{{$cart->product->discountedprice*$cart->qty}}">
	
										<!-- Coupon Inputs -->
										<input type="hidden" class="cart_product_reward" id="cart_product_reward{{$cart->id}}" value="0">
										<input type="hidden" class="cart_product_discount" id="cart_product_discount{{$cart->id}}" value="0">
										<span class="amount" id="order{{$cart->id}}_total_price" hidden>₱{{ number_format($cart->product->discountedprice*$cart->qty,2) }}</span>
									</div>
								</div>

								<div class="d-flex mt-2">
									{{-- <a href="javascript:void(0)" class="fw-normal text-black-50 text-smaller" onclick="toggleQuantityInput(this);">Edit</a> --}}
									<a href="javascript:;" class="fw-normal text-black-50 text-smaller" onclick="top_remove_product('{{$cart->id}}');"><u>Remove</u></a>
								</div>

								
								@if(Setting::isThreeDaysOnCart($cart->id))
									<div class="d-flex mt-2">
										<small class="badge bg-danger">For removal</small>
									</div>
								@endif
							</div>
							{{-- <div class="top-cart-item-quantity">x {{$cart->qty}}</div> --}}
						</div>
					</div>
				@endforeach
			</div>

			<div style="display: none;">
			    <form id="remove-top-product" method="post" action="{{route('cart.remove_product')}}">
			        @csrf
			        <input type="text" name="order_id" id="top-product-id" value="">
			    </form>
			</div>

			<!-- Cart Saved Text
			============================================= -->
			<div class="py-2 px-3 mt-auto text-black-50 text-smaller bg-color-light">
				<span><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="var(--themecolor)" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M54.46089,201.53911c-9.204-9.204-3.09935-28.52745-7.78412-39.85C41.82037,149.95168,24,140.50492,24,127.99963,24,115.4945,41.82047,106.048,46.67683,94.31079c4.68477-11.32253-1.41993-30.6459,7.78406-39.8499s28.52746-3.09935,39.85-7.78412C106.04832,41.82037,115.49508,24,128.00037,24c12.50513,0,21.95163,17.82047,33.68884,22.67683,11.32253,4.68477,30.6459-1.41993,39.8499,7.78406s3.09935,28.52746,7.78412,39.85C214.17963,106.04832,232,115.49508,232,128.00037c0,12.50513-17.82047,21.95163-22.67683,33.68884-4.68477,11.32253,1.41993,30.6459-7.78406,39.8499s-28.52745,3.09935-39.85,7.78412C149.95168,214.17963,140.50492,232,127.99963,232c-12.50513,0-21.95163-17.82047-33.68884-22.67683C82.98826,204.6384,63.66489,210.7431,54.46089,201.53911Z" opacity="0.2"></path><path d="M54.46089,201.53911c-9.204-9.204-3.09935-28.52745-7.78412-39.85C41.82037,149.95168,24,140.50492,24,127.99963,24,115.4945,41.82047,106.048,46.67683,94.31079c4.68477-11.32253-1.41993-30.6459,7.78406-39.8499s28.52746-3.09935,39.85-7.78412C106.04832,41.82037,115.49508,24,128.00037,24c12.50513,0,21.95163,17.82047,33.68884,22.67683,11.32253,4.68477,30.6459-1.41993,39.8499,7.78406s3.09935,28.52746,7.78412,39.85C214.17963,106.04832,232,115.49508,232,128.00037c0,12.50513-17.82047,21.95163-22.67683,33.68884-4.68477,11.32253,1.41993,30.6459-7.78406,39.8499s-28.52745,3.09935-39.85,7.78412C149.95168,214.17963,140.50492,232,127.99963,232c-12.50513,0-21.95163-17.82047-33.68884-22.67683C82.98826,204.6384,63.66489,210.7431,54.46089,201.53911Z" fill="none" stroke="var(--themecolor)" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></path><polyline points="172 104 113.333 160 84 132" fill="none" stroke="var(--themecolor)" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"></polyline></svg> You save ₱{{ number_format($totalsaved, 2) }} on this order.</span>
			</div>

			<!-- Cart Price and Button
			============================================= -->
			<div class="top-cart-action flex-column align-items-stretch">
				<div class="d-flex justify-content-between align-items-center mb-2">
					<small class="text-uppercase ls1">Total</small>
					<h4 class="fw-medium font-body mb-0"><span id="top-cart-total">₱{{number_format($carttotal,2)}}</span></h4>
					<input type="hidden" id="input-top-cart-total" value="{{$carttotal}}">
				</div>
				<a href="{{route('cart.front.show')}}" class="button btn-block text-center m-0 fw-normal"><i style="position: relative; top: -2px;"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#FFF" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"></rect><path d="M40,192a16,16,0,0,0,16,16H216a8,8,0,0,0,8-8V88a8,8,0,0,0-8-8H56A16,16,0,0,1,40,64Z" opacity="0.2"></path><path d="M40,64V192a16,16,0,0,0,16,16H216a8,8,0,0,0,8-8V88a8,8,0,0,0-8-8H56A16,16,0,0,1,40,64v0A16,16,0,0,1,56,48H192" fill="none" stroke="#FFF" stroke-linecap="round" stroke-linejoin="round" stroke-width="10"></path><circle cx="180" cy="144" r="12"></circle></svg></i> Checkout</a>
			</div>
		</div>

	</div>

</div>


@section('pagejs')
<script>
    
    // for edit quantity
	function FormatAmount(number, numberOfDigits) {
		var amount = parseFloat(number).toFixed(numberOfDigits);
		var num_parts = amount.toString().split(".");
		num_parts[0] = num_parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");

		return num_parts.join(".");
	}

	function addCommas(nStr){
		nStr += '';
		x = nStr.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1)) {
			x1 = x1.replace(rgx, '$1' + ',' + '$2');
		}
		return x1 + x2;
	}

    function plus_qty(id){
		var qty = parseFloat($('#quantity'+id).val())+1;

		if(parseInt($('#maxorder'+id).val()) < 1){
			swal({
				title: '',
				text: 'Sorry. Currently, there is no sufficient stocks for the item you wish to order.',
				icon: 'warning'
			});

			$('#quantity'+id).val($('#prevqty'+id).val()-1);
			return false;
		}

		order_qty(id,qty);
	}

	function minus_qty(id){
		var qty = parseFloat($('#quantity'+id).val())-1;
		order_qty(id,qty);
	}

	function order_qty(id,qty){

		if(qty == 0){
			$('#quantity'+id).val(1).val();
			return false;
		}
		
		var price = $('#cartItemPrice'+id).val();
		total_price  = parseFloat(price)*parseFloat(qty);

		$('#order'+id+'_total_price').html('₱'+FormatAmount(total_price,2));
		$('#input_order'+id+'_product_total_price').val(total_price);

		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			}
		});

		$.ajax({
			data: { 
				"quantity": qty, 
				"orderID": id, 
				"_token": "{{ csrf_token() }}",
			},
			type: "post",
			url: "{{route('cart.update')}}",
			
			success: function(returnData) {

				$('#maxorder'+id).val(returnData.maxOrder);
				$('.top-cart-number').html(returnData['totalItems']);
				$('#prevqty'+id).val(qty);
				// var promo_discount = parseFloat(returnData.total_promo_discount);

				// let subtotal = 0;
				// $(".input_product_total_price").each(function() {
				//     if(!isNaN(this.value) && this.value.length!=0) {
				//         subtotal += parseFloat(this.value);
				//     }
				// });

				// $('#subtotal').val(subtotal);


				// for the sidebar cart total
				// var cartotal = parseFloat($('#input-top-cart-total').val());
				// var productotal = price*qty;
				// var newtotal = cartotal+total_price;
				
				// alert(cartotal);

				// $('#input-top-cart-total').val(newtotal);
				// $('#top-cart-total').html('₱'+newtotal.toFixed(2));
				// 
				
				// resetCoupons();
				cart_total();
			}
		});
	}

	function cart_total(){
		var couponTotalDiscount = parseFloat($('#coupon_total_discount').val());
		var promoTotalDiscount = 0;
		var subtotal = 0;

		$(".input_product_total_price").each(function() {
			if(!isNaN(this.value) && this.value.length!=0) {
				subtotal += parseFloat(this.value);
			}
		});

		if(couponTotalDiscount == 0){
			$('#couponDiscountDiv').css('display','none');
		}

		// var totalDeduction = promoTotalDiscount + couponTotalDiscount;
		// var grandtotal = subtotal - totalDeduction;
		
		// $('#subtotal').html('₱'+FormatAmount(subtotal,2));

		$('#top-cart-total').val(subtotal);
		$('#top-cart-total').html('₱'+subtotal.toFixed(2));
	}
</script>
@endsection