<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LocalRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Auth;


/**
 * Class LocalCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class LocalCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Traits\UserOwnership;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Local::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/local');
        CRUD::setEntityNameStrings('Local', 'Locales');

        CRUD::setEntityNameStrings(__('superlocales.store'), __('superlocales.stores'));

    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // CRUD::setFromDb();  // set columns from db columns.

        /*
         * 'name',
         * 'phone',
         * 'website',
         * 'email',
         * 'parking_limit',
         * 'description',
         * 'position_lng',
         * 'position_lat',
         * 'logo',
         */
        CRUD::column('thumb')->type('image')->label(__('superlocales.thumb'));
        CRUD::column('name')->label(__('superlocales.name'));
        CRUD::column('phone')->label(__('superlocales.phone'));
        CRUD::column('category')->label(__('superlocales.category'));

        CRUD::addButtonFromModelFunction('line', 'show_products', 'show_products', 'ending');
        CRUD::addButtonFromModelFunction('line', 'show_pos', 'show_pos', 'ending');
        CRUD::addButtonFromModelFunction('line', 'show_orders', 'show_orders', 'ending');

        if (isset($_GET['categories_id'])) {
            if (trim($_GET['categories_id'])!=""){
                CRUD::addClause('where', 'store_categories_id', $_GET['categories_id']);
            }
        }
        //CRUD::denyAccess('show');
        //CRUD::allowAccess('category');
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(LocalRequest::class);
        CRUD::setFromDb();  // set fields from db columns.
        $this->define_user_field();

        CRUD::field('parking_limit')->type('number')->label(__('superlocales.parking_limit'));;
        CRUD::field('name')->label(__('superlocales.name'));
        CRUD::field('phone')->label(__('superlocales.phone'));

        CRUD::field('logo')
            ->type('upload')
            ->withFiles([
                'disk' => 'store_folder',  // the disk where file will be stored
                // 'path' => '', // the path inside the disk where file will be stored
            ]);


        CRUD::field([                          // Switch
            'name'      => 'store_categories_id',
            'type'      => 'select',
            'label'     => __('superlocales.category'),
            'model'     => 'App\Models\LocalType',
            'attribute' => 'name'

        ]);

        CRUD::field([
            'name'       => 'position_lat',
            'type'       => 'hidden',
            'attributes' => [
                'readonly' => 'readonly',
                'id'       => 'position_lat',
            ]
        ]);

        CRUD::field([
            'name'       => 'position_lng',
            'type'       => 'hidden',
            'attributes' => [
                'readonly' => 'readonly',
                'id'       => 'position_lng',
            ]
        ]);

        CRUD::field([  // CustomHTML
            'name'  => 'separator',
            'type'  => 'custom_html',
            'value' => 'Location<hr><div id="map_locater" class="map_crud"></div>'
        ]);
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
        $current_user = backpack_user();

        if ($current_user->hasRole('admin')) {
            CRUD::field([                          // Switch
                'name'      => 'users_id',
                'type'      => 'select',
                'label'     => 'User Owner',
                'value'     => $this->crud->getCurrentEntry()->users_id,
                'model'     => 'App\Models\User',  // related model
                'attribute' => 'email',            // foreign key attribute that is shown to user
            ]);
        } else {
            CRUD::field([
                'name'       => 'users_id',
                'type'       => 'hidden',
                'value'      => $this->crud->getCurrentEntry()->users_id,
                'attributes' => [
                    'readonly' => 'readonly'
                ]
            ]);
        }

    }
}
