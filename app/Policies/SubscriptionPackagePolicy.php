<?php

namespace App\Policies;

use App\Models\SubscriptionPackage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubscriptionPackagePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_subscriptionpackage') || $user->hasRole('super-admin');
    }

    public function view(User $user, SubscriptionPackage $subscriptionPackage): bool
    {
        return $user->can('view_any_subscriptionpackage') || $user->hasRole('super-admin');
    }

    public function create(User $user): bool
    {
        return $user->can('create_subscriptionpackage') || $user->hasRole('super-admin');
    }

    public function update(User $user, SubscriptionPackage $subscriptionPackage): bool
    {
        return $user->can('update_subscriptionpackage') || $user->hasRole('super-admin');
    }

    public function delete(User $user, SubscriptionPackage $subscriptionPackage): bool
    {
        return $user->can('delete_subscriptionpackage') || $user->hasRole('super-admin');
    }

    public function restore(User $user, SubscriptionPackage $subscriptionPackage): bool
    {
        return $user->can('delete_any_subscriptionpackage') || $user->hasRole('super-admin');
    }

    public function forceDelete(User $user, SubscriptionPackage $subscriptionPackage): bool
    {
        return $user->can('delete_any_subscriptionpackage') || $user->hasRole('super-admin');
    }
}
