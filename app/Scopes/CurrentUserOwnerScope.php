<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

class CurrentUserOwnerScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        $table_user_id = $model->getTable() . ".users_id";
        if (Auth::check()) {
            //$current_user = backpack_user();
                if (!backpack_user()->hasRole('admin')) {
                    $builder->where($table_user_id, '=', Auth::user()->id);
                }


        }
    }
}
