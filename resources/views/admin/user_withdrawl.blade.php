@extends(backpack_view('blank'))




@section('content')
<div id="app" class="container-fluid">
<h1>Cobro de Comisiones
</h1>

<h3>
    A continuación, podrás iniciar el proceso de cobro de tus comisiones generadas por las ventas realizadas a través de nuestro sistema en línea. Este procedimiento te permitirá revisar los detalles de tus ganancias, seleccionar el método de pago disponible y confirmar la solicitud de retiro de fondos.

Te recomendamos tener a mano la información necesaria para completar el proceso de manera rápida y segura.
</h3>
Empieza hoy mismo y crea tu primera tienda en línea con solo unos clics.

<h2><a href="{{ route('user_withdrawls_step1') }}" class="btn btn-primary">Continuar</a></h2>



</div>









</div>
@endsection
