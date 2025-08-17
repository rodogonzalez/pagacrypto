@extends(backpack_view('plain'))

@section('content')
    <div id="app" class="">
        <div class=" ">
            <pos-component class=""
            :currency="'USD'"
            :name="'{{ $store->name }}'"
            :logo="'{{ $store->logo }}'"
            :storeid="{{ $store->id }}"
            :products="{{ $products }}"
            :orderid="{{ $order->id }}"
            />
        </div>

    </div>
<div class="container pos-footer pt-2">
    @include('ads.ad-footer')
</div>



@endsection

