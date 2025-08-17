@extends(backpack_view('blank'))
<?php

$user_role = 'demo';
$show_pos = false;
$current_user = backpack_user();

if (!is_null($current_user)) {
    $user_role = 'authenticated';
    if (backpack_user()->hasRole('admin')) {
        $user_role = 'admin';
    }
    $show_pos = true;
}

?>

@section('content')

<div id="app" class="container">


        <h1>¡Bienvenido a <b>Telecripto</b>!</h1>

        <h3>Estamos emocionados de tenerte aquí. Has llegado al lugar perfecto para dar el primer paso hacia tu propio
            negocio en línea. Con nuestra poderosa herramienta de punto de ventas, hemos hecho que crear tu tienda en línea
            sea más fácil, rápido y eficiente que nunca.

            Diseñada para emprendedores como tú, nuestra plataforma te ofrece todo lo que necesitas para vender tus
            productos, gestionar tu inventario y atender a tus clientes desde cualquier lugar.

        </h3>
        <h1>Empieza hoy mismo y crea tu primera tienda en línea con solo unos clics.</h1>



</div>

@endsection
