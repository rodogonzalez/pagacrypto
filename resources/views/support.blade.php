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
<div id="app" class="container-fluid">

    <div class="container">


        <h1>¿Necesitás ayuda? ¡Estamos para ayudarte!</h1>

        <h2>En <b>Super Locales</b> contamos con un equipo de soporte listo para responder tus consultas y brindarte asistencia personalizada. Podés contactarnos a través de los siguientes canales:
        </h2>
        <h3>
            <div class="row mt-4">

                📧 Correo electrónico:<br>
                soporte@superlocales.com
            </div>
            <div class="row mt-4">
                📞 Teléfono de atención al cliente:
                0800-123-SUPER (78737)<br>
                Lunes a viernes de 9:00 a 18:00 hs

            </div>
            <div class="row mt-4">
                💬 WhatsApp:
                +506 71000949<br>
                Disponible todos los días de 8:00 a 22:00 hs

            </div>
            <div class="row mt-4">

                🌐 Sitio web:
                www.superlocales.com

            </div>







        </h3>
        <h1>¡Gracias por confiar en Super Locales!</h1>


    </div>


</div>
@endsection
