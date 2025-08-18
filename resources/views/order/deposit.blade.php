@extends(backpack_view('blank'))

@section('content')
    <div id="app" class="">
        <div class=" ">
            <deposit-component class=""
                :order_id={{$order->id}}

            />
        </div>

    </div>
@endsection
