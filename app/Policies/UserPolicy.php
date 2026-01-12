<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        //Only admins and managers can view user lists
        return($user->isAdmin() || $user->isManager())&&
               $user->hasPermissionTo('manage-users');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): bool
    {
        //Users can view their own profile
        if($user->id === $model->id){
            return true;
        }    

        //Admins and managers can view other users
        return($user->isAdmin() || $user->isManager()) &&
               $user->hasPermissionTo('manage-users');

    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        //Only admins can create users
        return $user->isAdmin() && $user->hasPermissionTo('manage-users');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        //users can update their own profile -- this is for scalability, for now we will not focus on user profiles.
        if($user->id === $model->id){
            return true;
        }

        //Only admins can update other users
        return $user->isAdmin() && $user->hasPermissionTo('manage-users');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        //users cannot delete themselves.
        if($user->id === $model->id){
            return false;
        }

        //Only admins can update other users
        return $user->isAdmin() && $user->hasPermissionTo('manage-users');

    }

      /**
     * Determine if the user can assign roles.
     */
    public function assignrole(User $user, User $model): bool
    {
        //Only admins can update other users
        return $user->isAdmin() && $user->hasPermissionTo('manage-roles');

    }

}
