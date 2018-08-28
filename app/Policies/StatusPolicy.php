<?php

namespace App\Policies;

use App\User;
use App\Status;
use Illuminate\Auth\Access\HandlesAuthorization;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * 验证当前用户的id 是否等于要删除微博的外键 user_id
     */
    public function destroy(User $user, Status $status)
    {
        return $user->id === $status->user_id;
    }
}
