
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

        <div class="container-fluid">
            <map-component :is_auth="{{$show_pos}}" :pos="false"  :is_role="'{{$user_role}}'"  :locales="{{ App\Models\Local::all()->toJSON() }}" />
        </div>


    </div>
@endsection
