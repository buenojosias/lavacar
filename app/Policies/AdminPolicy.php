<?php

namespace App\Policies;

use App\Models\User;

class AdminPolicy
{
    /**
     * Verifica se o usuário é administrador.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isAdmin(User $user): bool
    {
        return $user->role === 'ADMIN';
    }

    /**
     * Verifica se o usuário é proprietário de uma empresa.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isOwner(User $user): bool
    {
        return $user->selected_company_role === 'OWNER';
    }

    /**
     * Verifica se o usuário é gerente ou proprietário.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function isManagerOrOwner(User $user): bool
    {
        return in_array($user->selected_company_role, ['MANAGER', 'OWNER']);
    }
}
