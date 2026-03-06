<?php

namespace App\Policies;

use App\StaticPage;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class StaticPagePolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        // 管理權限
        if ($user->hasPermission(['modify-static-page', 'resultiii-manage'])) {
            return true;
        }
        // 非縣市帳號
        if ($user->type != 'county') {
            return false;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\User $user
     * @param \App\StaticPage $staticPage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, StaticPage $staticPage)
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\User $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\User $user
     * @param \App\StaticPage $staticPage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, StaticPage $staticPage)
    {
        return $user->owns($staticPage);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\User $user
     * @param \App\StaticPage $staticPage
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, StaticPage $staticPage)
    {
        return false;
    }
}
