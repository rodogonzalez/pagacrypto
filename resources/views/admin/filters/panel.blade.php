@php
$fields = [];
@endphp

@switch($crud->model->getTable())
@case("orders")
@php
$stores = \App\Models\Local::all()->toArray();
$fields = ['status'=> ['label'=> 'Estado','data'=> [
                        ['id'=>'process','name'=>'Proceso'],
                        ['id'=>'canceled','name'=>'Cancelada'],
                        ['id'=>'completed','name'=>'Completada']
                        ]],

            'store_id'=> ['label'=> 'Tienda','data'=>$stores]
            ];
@endphp
@break

@case("stores")
@php
$categories = \App\Models\LocalType::all()->toArray();

$fields = ['categories_id'=> ['label'=> 'Categoria','data'=>$categories]];
@endphp
@break

@case("products")
@php
$categories = \App\Models\Category::all()->toArray();



$stores = \App\Models\Local::all()->toArray();


$fields = [
    'categories_id'=> ['label'=> 'Categoria','data'=>$categories],
    'store_id'=> ['label'=> 'Tienda','data'=>$stores]

    ];

@endphp
@break

@endswitch

<script src="/js/multiselect-dropdown.js"></script>

<form>
<div class="container  p-4 row">
    @foreach($fields as $field=>$options)
    <div class="col-3">

    @php
        $label = $options['label'];
        $data = $options['data'];

    @endphp
    {{$label}} <select id="{{$field}}" name="{{$field}}" >
                    <option value="">Seleccione una {{$label}}</option>
                    @foreach($data as $option)
                        <option

                        @php
                            $selected = "";
                            if (isset($_GET[$field])){

                                if ($_GET[$field]==$option['id']){
                                    $selected = " selected ";
                                }

                            }
                        @endphp
                        {{$selected}}
                        value="{{$option['id']}}">{{$option['name']}}</option>
                    @endforeach

                </select>
    </div>
@endforeach
<div class="col-1 text-end"><button>Filtrar</button></div>
</form>

</div>


