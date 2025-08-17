@extends(backpack_view('blank'))
<?php
$amount_to_pay = \App\Helpers\Balance::get_balance_available();
$pending_Withdrawls = \App\Models\Withdrawl::getPending();

?>
@section('content')
<div id="app" class="container-fluid p-4">
    <div class="row mt-2 px-3">
        <div class="col-xl-6 col-lg-12 col-sm-12 d-flex align-items-stretch">
            <div class="card w-100 pt-4 px-4 py-4">

                <span class='row'>
                    <h4>Ventas de Tiendas</h4>
                </span>
                <div class='mb-3 card p-2'>
                    <Bar-Component :stats="{{ $objStats->get_stores_summary() }}" />
                </div>


                <div>
                    <stores-component :stores="{{ App\Models\Local::stores_summary() }}" />
                </div>

            </div>
        </div>

        <div class="col-xl-6 col-lg-12 col-sm-12 d-flex align-items-stretch container ">
            <div class="card w-100 mt-2 p-4">
                <div class=" row ">

                    <div class="col-xl-6 col-lg-6 col-sm-6 d-flex align-items-stretch">

                        <div class="card w-100  px-4 ">
                            <div class="row mt-2">
                                <div class="col-4 text-center">
                                    <i class="las la-3x la-money-bill-wave"></i>
                                </div>
                                <div class="col-8">
                                    <?php
                                    $data = $objStats->get_total_sales();
                                    ?>
                                    <h3>{{ $data['totals'] }} Ordenes</h3>
                                    <h3>{{env ('COINPAYMENT_SYMBOL') }} {{ $data['amount'] }} {{env('COINPAYMENT_CURRENCY')}}</h3>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6  col-sm-6  d-flex align-items-stretch">
                        <div class="card w-100  px-4 ">
                            <div class="row mt-2">
                                <div class="col-4 text-center">
                                    <i class="las la-3x la-file-invoice-dollar"></i>

                                </div>
                                <div class="col-8">
                                    @if ($amount_to_pay === 0)
                                    <h4>No dispone de saldo cobrable</h4>
                                    @else
                                    <h4>Saldo Disponible</h4>
                                    <h3>{{env('COINPAYMENT_SYMBOL')}} {{ $amount_to_pay }}</h3>
                                    </div><div class="col-12">
                                    <a href="{{ route('user_withdrawls') }}" class="btn">Solicitar Retiro</a>

                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($pending_Withdrawls->count()>0)
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="card ">
                                <div class="card-body">
                                    <h5 class="card-title">Retiros en Proceso</h5>

                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Solicitado</th>
                                                <th>Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($pending_Withdrawls as $pending_Withdrawl)

                                            <tr>

                                                <td><a href="/admin/withdrawl/{{ $pending_Withdrawl->id }}/show">{{ $pending_Withdrawl->created_at->format("d/m/Y") }}</a></td>
                                                <td><a href="/admin/withdrawl/{{ $pending_Withdrawl->id }}/show">{{ $pending_Withdrawl->amount }}</a></td>

                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>

                        </div>
                    </div>


                    @endif

                    <div class="col-xl-12 col-lg-12 col-sm-12   mt-3">
                        <span>
                            <h4>Ventas en los ultimos 10 dias</h4>
                        </span>
                        <div class='mt-3'>

                            <line-Component :stats="{{ $objStats->get_sales_period() }}" class='card p-5 ' />
                        </div>
                        <span class='mt-3 row'>
                            <h4>Categorias mas vendidas</h4>
                        </span>
                        <div class="card w-100 mt-3 pt-4 px-4 py-4">

                            <categories-component :items="{{ $objStats->most_categories_sold() }}" :currency_symbol="'{{env ('COINPAYMENT_SYMBOL')}}'" />
                        </div>

                        <span class='mt-3 row'>
                            <h4>Articulos mas vendidos</h4>
                        </span>

                        <div class="card w-100 mt-3 pt-4 px-4 py-4">

                            <div class="row mt-2">
                                <sold-component :items="{{ $objStats->get_most_sold_items() }}" :currency_symbol="'{{env ('COINPAYMENT_SYMBOL')}}'" />
                            </div>



                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>




</div>



</div>
@endsection
