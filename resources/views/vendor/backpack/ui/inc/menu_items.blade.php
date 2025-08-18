<?php
$user_role    = 'demo';
$current_user = backpack_user();
if (!is_null($current_user)) {
    $user_role = 'autenticated';
    if ($current_user->hasRole('admin')) {
        $user_role = 'admin';
    }
}
?>



<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i
            class="la la-home nav-icon"></i>{{ trans('backpack::base.dashboard') }}</a></li>
@if ($user_role != 'demo')

<li class="nav-item"><a class="nav-link" href="{{ route('deposit_crypto') }}"><i class="la la-file-invoice nav-icon"></i>
        Nuevo Deposito</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('order') }}"><i class="la la-file-invoice nav-icon"></i>
        {{__('telecripto.orders')}}</a></li>





<x-backpack::menu-item title="Wallets" icon="la la-wallet" :link="backpack_url('wallet')" />
<x-backpack::menu-item title="Crypto order payments" icon="la la-question"
    :link="backpack_url('crypto-order-payment')" />

<x-backpack::menu-item title="Pagos" icon="la  la-file-invoice-dollar" :link="backpack_url('payment')" />
<x-backpack::menu--item title="{{__('telecripto.withdrawls')}}" icon="la la-money-check-alt"
    :link="backpack_url('withdrawl')" />
<x-backpack::menu-item title="{{__('telecripto.banks')}}" icon="la la-university"
    :link="backpack_url('bank')" />






@if (backpack_user()->hasRole('admin'))
<x-backpack::menu-dropdown title="General" icon="la la-gear">
    <x-backpack::menu-dropdown title="{{__('telecripto.users')}}" icon="la la-users-cog" nested="true">

        <x-backpack::menu-dropdown-header title="Authentication" />
        <x-backpack::menu-dropdown-item title="Users" icon="la la-user" :link="backpack_url('user')" />
        <x-backpack::menu-dropdown-item title="Roles" icon="la la-group" :link="backpack_url('role')" />
        <x-backpack::menu-dropdown-item title="Permissions" icon="la la-key" :link="backpack_url('permission')" />

    </x-backpack::menu-dropdown>





    <x-backpack::menu-dropdown title="Procesamiento de Pagos" icon="la la-money-bill-wave" nested="true">

        <x-backpack::menu-dropdown-item title="Wallets" icon="la la-question" :link="backpack_url('wallet')" />
        <x-backpack::menu-dropdown-item title="Crypto order payments" icon="la la-question"
            :link="backpack_url('crypto-order-payment')" />
        <x-backpack::menu-dropdown-item title="Withdrawls" icon="la la-question" :link="backpack_url('withdrawl')" />

    </x-backpack::menu-dropdown>

    <x-backpack::menu-dropdown title="General" icon="la la-gear" nested="true">

        <x-backpack::menu-dropdown-item title='Logs' icon='la la-terminal' :link="backpack_url('log')" />
        <x-backpack::menu-dropdown-item title="Settings" icon="la la-cog" :link="backpack_url('setting')" />

    </x-backpack::menu-dropdown>




</x-backpack::menu-dropdown>
@endif
@endif



@include('ads.ad-side')
