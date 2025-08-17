<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BankRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BankCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BankCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }

    use \App\Traits\UserOwnership;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Bank::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/bank');


        CRUD::setEntityNameStrings(__('telecripto.bank'), __('telecripto.banks'));
        CRUD::setOperationSetting('showEntryCount', false);
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.
        CRUD::column('name')->label(__('telecripto.name'));
        CRUD::column('owner_name')->label(__('telecripto.owner_name'));
        CRUD::column('owner_id')->label(__('telecripto.owner_id'));
        CRUD::column('owner_id_picture_front')->label(__('telecripto.owner_id_picture_front'));
        CRUD::column('owner_id_picture_back')->label(__('telecripto.owner_id_picture_back'));
        CRUD::column('owner_phone')->label(__('telecripto.phone'));
        CRUD::column('iban_account')->label(__('telecripto.iban_account'));
        CRUD::column('status')->label(__('telecripto.status'));


    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(BankRequest::class);
       // CRUD::setFromDb(); // set fields from db columns.



        CRUD::field('name')->label(__('telecripto.name'));
        CRUD::field('owner_name')->label(__('telecripto.owner_name'));
        CRUD::field('owner_id')->label(__('telecripto.owner_id'));
        CRUD::field('status')->label(__('telecripto.status'))
            ->type('select_from_array')
            ->options( ['non-validated'=> 'Sin Validar', 'canceled'=> 'Cancelada', 'validated'=> 'Validada']) ;



        //CRUD::field('owner_id_picture_front');
        CRUD::field('owner_id_picture_front')
            ->label(__('telecripto.owner_id_picture_front'))
            ->type('upload')
            ->withFiles([
                'disk' => 'back_customer_folder',  // the disk where file will be stored

            ]);

        CRUD::field('owner_id_picture_back')
            ->label(__('telecripto.owner_id_picture_back'))
            ->type('upload')
            ->withFiles([
                'disk' => 'back_customer_folder',  // the disk where file will be stored

            ]);

        CRUD::field('owner_phone')->label(__('telecripto.phone'));
        CRUD::field('iban_account')->label(__('telecripto.iban_account'));

        $this->define_user_field();


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

      public function store()
    {
      // do something before validation, before save, before everything
      $response = $this->traitStore();
      //dd($response);
      // do something after save
      return $response;
    }

}
