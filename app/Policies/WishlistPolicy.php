<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class WishlistPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Wishlist $wishlist)
    {
        return $user->id === $wishlist->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to view this wishlist.', 403);
    }

    public function update(User $user, Wishlist $wishlist)
    {
        return $user->id === $wishlist->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to update this wishlist.', 403);
    }

    public function delete(User $user, Wishlist $wishlist)
    {
        return $user->id === $wishlist->user_id
            ? Response::allow()
            : Response::deny('You do not have permission to delete this wishlist.', 403);
    }
}