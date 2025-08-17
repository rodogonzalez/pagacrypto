@extends(backpack_view('errors.layout'))

@php
  $error_number ??= 400;
@endphp

@section('title')
  {{ trans('backpack::base.error_page.'.$error_number) }}
@endsection

@section('description')
  {!! $exception?->getMessage() && config('app.debug') ? e($exception->getMessage()) : trans('backpack::base.error_page.message_4xx') !!}
@endsection
