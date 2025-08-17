<div class="row">

    {{-- Show the inputs --}}
    @foreach ($fields as $field)
        @if (isset($field['class']))

            <div class="{{$field['class']}}">
                @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
            </div>

        @else

            @switch($field['type'])
                @case('hidden')
                    @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
                    @break

                @case('switch')
                @case('summernote')
                @case('custom_html')


                    <div class="col-xl-12 col-lg-12 col-sm-12 align-top">
                        @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
                    </div>
                    @break

                @default
                    <div class="col-xl-6 col-lg-6 col-sm-12 align-top">
                        @include($crud->getFirstFieldView($field['type'], $field['view_namespace'] ?? false), $field)
                    </div>
                    @break
            @endswitch



        @endif





    @endforeach

</div>
