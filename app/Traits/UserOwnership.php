<?php

namespace App\Traits;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
trait UserOwnership
{
    //
    public function define_user_field(){
        $current_user = backpack_user();

        if ($current_user->hasRole('admin')) {
            CRUD::field([                          // Switch
                'name'      => 'users_id',
                'type'      => 'select',
                'label'     => 'User Owner',
                'model'     => 'App\Models\User',  // related model
                'attribute' => 'email',            // foreign key attribute that is shown to user
            ]);
        } else {
            CRUD::field([
                'name'       => 'users_id',
                'type'       => 'text',
                'default'    => $current_user->id,
                'value'      => $current_user->id,
                'attributes' => [
                    'readonly' => 'readonly'
                ]
            ]);
        }


    }
}
