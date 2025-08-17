@extends(backpack_view('blank'))


@section('content')
<div id="app" class="container-fluid">
    <h1>
        Cobro de Comisiones
    </h1>

    <div>
        En este momento se encuentra a sus disposicion el siguiente balance para ser retirado

        <h1>Ordenes cobrables</h1>
        <span><i class="las la-info-circle la-3x"></i>
            Las órdenes que se detallan a continuación pueden ser consideradas cobrables y, por ende, seran incluidas en
            la contabilización de un pago a ser aplicado en una cuenta bancaria. Es necesario verificar que dichas
            órdenes cumplan con los criterios establecidos por el área de facturación para su correcto registro y
            conciliación en los sistemas contables.
        </span>
        <form class="row">
            <div class="col-xl-6 col-lg-6 col-sm-12">
                <div class="card">
                    <div class="row">
                        <div class="col-6">Banco</div>
                        <div class="col-6">
                            <select>
                                <option>nombre cuenta banco 1</option>
                                <option>nombre cuenta banco 2</option>
                                <option>nombre cuenta banco 3</option>

                            </select>
                        </div>
                    </div>


                </div>

            </div>

            <div class="col-xl-6 col-lg-6 col-sm-12">

                <div class="card " style="height: 200px; overflow-y: auto;">
                    <table class="table table-striped small table-responsive">
                        <tbody>
                            @foreach (\App\Helpers\Balance::get_orders_unprocessed() as $order)
                            <tr>
                                <td>{{$order->id}}</td>
                                <td>{{env('COINPAYMENT_SYMBOL') }}{{$order->amount}}</td>
                            </tr>

                            @endforeach
                        </tbody>
                    </table>

                </div>

            </div>

        </form>




    </div>


    <h2><a href="{{ route('user_withdrawls_step2') }}" class="btn btn-primary">Continuar</a></h2>



</div>









</div>
@endsection
