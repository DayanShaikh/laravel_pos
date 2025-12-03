<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = ['profile_photo_url', 'given_permissions', 'permissions'];
    protected function getGivenPermissionsAttribute()
    {
        $permissions = [];
        foreach ($this->permissions as $permission) {
            if ($permission->action == "view") {
                $permissions[] = $permission->action = "view" . '-' . $permission->model;
                $permissions[] = "viewAny" . '-' . $permission->model;
            }
            if ($permission->action == "add") {
                $permissions[] = $permission->action = "create" . '-' . $permission->model;
            }
            if ($permission->action == "edit") {
                $permissions[] = $permission->action = "updateAny" . '-' . $permission->model;
                $permissions[] = $permission->action = "update" . '-' . $permission->model;
            }
            if ($permission->action == "delete") {
                $permissions[] = $permission->action = "deleteAny" . '-' . $permission->model;
                $permissions[] = $permission->action = "delete" . '-' . $permission->model;
                $permissions[] = $permission->action = "restoreAny" . '-' . $permission->model;
                $permissions[] = $permission->action = "restore" . '-' . $permission->model;
                $permissions[] = $permission->action = "forceDeleteAny" . '-' . $permission->model;
                $permissions[] = $permission->action = "forceDelete" . '-' . $permission->model;
            }
        }
        return $permissions;
    }

    public function userRoles()
    {
        return $this->belongsToMany(Roles::class, 'user_roles', 'user_id', 'roles_id');
    }

    public function getPermissionsAttribute()
    {
        return $this->userRoles()->with('permissions')->get()->pluck('permissions')->flatten();
    }

    public function hasRole($roleName)
    {
        foreach ($this->userRoles as $role) {
            if ($role->title == $roleName)
                return true;
        }
        return false;
    }

    public function hasAnyRole($roles)
    {
        return $this->userRoles()->whereIn('title', $roles)->exists();
    }
}
