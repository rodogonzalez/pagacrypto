<?php
$user_role = 'demo';
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
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('local') }}"><i class="la la-store nav-icon"></i>
           {{ __('superlocales.stores')}}</a></li>
    @if (backpack_user()->hasRole('admin'))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('product') }}"><i
                    class="la la-box nav-icon"></i>{{__('superlocales.products')}} </a></li>
    @endif
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('order') }}"><i
                class="la la-file-invoice nav-icon"></i> {{__('superlocales.orders')}}</a></li>
    <li class="nav-item"><a class="nav-link" href="{{ backpack_url('customer') }}"><i
                class="la la-user-edit nav-icon"></i> {{__('superlocales.clients')}}</a></li>


        <x-backpack::menu-dropdown title="{{__('superlocales.store_setup')}}" icon="la la-puzzle-piece">
            <x-backpack::menu-dropdown-item title="{{__('superlocales.categories')}}" icon="la la-file-alt" :link="backpack_url('category')" />
            @if (backpack_user()->hasRole('admin-disable'))
                <x-backpack::menu-dropdown-item title="Wallets" icon="la la-wallet" :link="backpack_url('wallet')" />
                <x-backpack::menu-dropdown-item title="Crypto order payments" icon="la la-question" :link="backpack_url('crypto-order-payment')" />
            @endif
            <x-backpack::menu-dropdown-item title="Pagos" icon="la  la-file-invoice-dollar" :link="backpack_url('payment')" />
            <x-backpack::menu-dropdown-item  title="{{__('superlocales.withdrawls')}}" icon="la la-money-check-alt" :link="backpack_url('withdrawl')" />
            <x-backpack::menu-dropdown-item  title="{{__('superlocales.banks')}}" icon="la la-university" :link="backpack_url('bank')" />

        </x-backpack::menu-dropdown>




    @if (backpack_user()->hasRole('admin'))
        <x-backpack::menu-dropdown title="General" icon="la la-gear">
            <x-backpack::menu-dropdown title="{{__('superlocales.users')}}" icon="la la-users-cog"  nested="true" >

                <x-backpack::menu-dropdown-header title="Authentication" />
                <x-backpack::menu-dropdown-item title="Users" icon="la la-user" :link="backpack_url('user')" />
                <x-backpack::menu-dropdown-item title="Roles" icon="la la-group" :link="backpack_url('role')" />
                <x-backpack::menu-dropdown-item title="Permissions" icon="la la-key" :link="backpack_url('permission')" />

            </x-backpack::menu-dropdown>

            <x-backpack::menu-dropdown title="{{__('superlocales.stores')}}" icon="la la-store-alt"  nested="true" >


                <x-backpack::menu-dropdown-item title="{{__('superlocales.commerce_types')}}" icon="la la-question" :link="backpack_url('local-type')" />

            </x-backpack::menu-dropdown>



            <x-backpack::menu-dropdown title="Procesamiento de Pagos" icon="la la-money-bill-wave"  nested="true" >

                <x-backpack::menu-dropdown-item title="Wallets" icon="la la-question" :link="backpack_url('wallet')" />
                <x-backpack::menu-dropdown-item title="Crypto order payments" icon="la la-question" :link="backpack_url('crypto-order-payment')" />
                <x-backpack::menu-dropdown-item title="Withdrawls" icon="la la-question" :link="backpack_url('withdrawl')" />

            </x-backpack::menu-dropdown>

            <x-backpack::menu-dropdown title="General" icon="la la-gear"  nested="true" >

                <x-backpack::menu-dropdown-item title='Logs' icon='la la-terminal' :link="backpack_url('log')" />
                <x-backpack::menu-dropdown-item title="Settings" icon="la la-cog" :link="backpack_url('setting')" />

            </x-backpack::menu-dropdown>




        </x-backpack::menu-dropdown>
    @endif
@endif



@include('ads.ad-side')
