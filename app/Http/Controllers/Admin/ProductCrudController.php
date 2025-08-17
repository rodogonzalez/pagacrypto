<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    private $current_store_id = null;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        $request = new Request;
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        //CRUD::setEntityNameStrings('product', 'products');

        CRUD::setEntityNameStrings(__('superlocales.product'), __('superlocales.products'));

        $current_user = backpack_user();
        if (!$current_user->hasRole('admin')) {
            $stores        = \App\Models\Local::where('users_id', $current_user->id)->get();
            $store_filters = [];
            foreach ($stores as $store) {
                $store_filters[] = 'stores_id = ' . $store->id;
            }
            $this->crud->setQuery(\App\Models\Product::whereRaw(implode(' or ', $store_filters)));
        }




    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {

        $current_user = backpack_user();

        if (isset($_GET['store_id'])) {
            if (trim($_GET['store_id'])!=""){

                $store = \App\Models\Local::where('id', $_GET['store_id'])->first();
                if ($store->users_id != $current_user->id && !$current_user->hasRole('admin')) {
                    abort(403);
                }
                $this->current_store_id = $_GET['store_id'];
                // $this->crud->setQuery(\App\Models\Product::whereRaw('stores_id  in [' . implode(',',$store_filters) . ']'));
                CRUD::addClause('where', 'stores_id', $this->current_store_id);
            }
        }

        if (isset($_GET['categories_id'])) {
            if (trim($_GET['categories_id'])!=""){

                CRUD::addClause('where', 'product_categories_id', $_GET['categories_id']);
            }

        }


        CRUD::disablePersistentTable();
        CRUD::denyAccess('show');


        CRUD::column('thumb')->type('image');
        CRUD::column('name');
        CRUD::column('categoryname');
        CRUD::column('storename');
        CRUD::column('price');
        CRUD::column('quantity');
        CRUD::column('status');


        // build custom URL using closure
        CRUD::button('category')->stack('top')->view('crud::buttons.quick')->meta([
            'access'  => true,
            'label'   => __('superlocales.categories'),
            'icon'    => 'la la-layer-group',
            'wrapper' => [
                'href'  => backpack_url('category'),
                'class' => 'btn mx-4'
            ],
        ]);

        // build custom URL using closure
        CRUD::button('import')->stack('top')->view('crud::buttons.quick')->meta([
            'access'  => true,
            'label'   => __('superlocales.import_products'),
            'icon'    => 'la la-layer-group',
            'wrapper' => [
                'href'  => '#',
                'class' => 'btn mx-4',
                'id'    => 'btn_import_xls',
                'onClick'    => 'btn_import_xls()',
            ],
        ]);


    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);

        CRUD::field([
            'name'     => 'status',
            'type'     => 'switch',
            'label'    => 'Active',
            'color'    => 'primary',  // May be any bootstrap color class or an hex color
            'onLabel'  => '✓',
            'offLabel' => '✕',
        ]);

        CRUD::field([
            'name'      => 'stores_id',
            'type'      => 'select',
            'label'     => __('superlocales.store'),
            'model'     => 'App\Models\Local',
            'attribute' => 'name',
        ]);

        CRUD::field([
            'name'      => 'product_categories_id',
            'type'      => 'select',
            'label'     => __('superlocales.category'),
            'model'     => 'App\Models\Category',
            'attribute' => 'name',
        ]);

        CRUD::field('name')->type('text')->label(__('superlocales.name'));

        CRUD::field([
            'name'       => 'price',
            'type'       => 'number',
            'label'      => __('superlocales.price'),
            'attributes' => [
                'step'       => 'any',
                'aria-label' => 'Participant Age (as a decimal number)',
            ]
        ]);


        CRUD::field('quantity')->type('number')->label(__('superlocales.quantity'));

        CRUD::field([
            'name'       => 'barcode',
            'type'       => 'text',
            //'class' => 'textdsdsd',
            'label'      => 'Bar Code',
            'attributes' => [
                'placeholder' => 'Scan the Bar Code',
                'id'          => 'barcode',
            ]
        ]);

        CRUD::field('image')
            ->type('upload')
            ->withFiles([
                'disk' => 'products_folder',  // the disk where file will be stored
                // 'path' => '', // the path inside the disk where file will be stored
            ]);

        CRUD::field([  // CustomHTML
            'name'  => 'separator',
            'type'  => 'custom_html',
            'value' => '<hr><div id="camera_preview"></div>'
        ]);

        CRUD::field('description')->type('summernote');
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();


    }
}
