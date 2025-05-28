<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Employee;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
// Remove unused imports if they were only for the old role system
// use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// use App\Models\Role;
// use App\Models\Permission;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role' // This is correct for the direct column usage
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        // 'role' => RoleEnum::class, // If you create a RoleEnum for type safety
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $roleName The name of the role to check.
     * @return bool
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role === $roleName;
    }

    /**
     * Check if the user has any of the given roles.
     *
     * @param array $roles An array of role names to check.
     * @return bool
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles, true);
    }

    // Add other role-related helper methods if needed, for example:
    public function isAdmin(): bool
    {
        return $this->hasRole('orionAdmin'); // Assuming 'orionAdmin' is one of your enum values
    }

    // If you are using Laratrust, remove its trait here. e.g. use LaratrustUserTrait;
}
