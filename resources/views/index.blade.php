
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

            @include('admin.welcome-content-guest')

        </div>
    </div>

@endsection
