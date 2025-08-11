<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'permissions',
        'is_admin',
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Get the sales for the user.
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get all available permissions in the system organized by resource.
     */
    public static function getAvailablePermissions(): array
    {
        return [
            'sales' => [
                'create_sales' => 'Create Sales',
                'edit_sales' => 'Edit Sales',
                'delete_sales' => 'Delete Sales',
            ],
            'storage_units' => [
                'create_storage_units' => 'Create Storage Units',
                'edit_storage_units' => 'Edit Storage Units',
                'delete_storage_units' => 'Delete Storage Units',
            ],
            'categories' => [
                'create_categories' => 'Create Categories',
                'edit_categories' => 'Edit Categories',
                'delete_categories' => 'Delete Categories',
            ],
            'platforms' => [
                'create_platforms' => 'Create Platforms',
                'edit_platforms' => 'Edit Platforms',
                'delete_platforms' => 'Delete Platforms',
            ],
            'users' => [
                'create_users' => 'Create Users',
                'edit_users' => 'Edit Users',
                'delete_users' => 'Delete Users',
            ],
        ];
    }

    /**
     * Check if user is an administrator.
     */
    public function isAdmin(): bool
    {
        return $this->is_admin;
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        // Admins have all permissions
        if ($this->is_admin) {
            return true;
        }

        // Check in user's permissions array
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions, true);
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Grant a permission to the user.
     */
    public function grantPermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        
        if (!in_array($permission, $permissions, true)) {
            $permissions[] = $permission;
            $this->permissions = $permissions;
            $this->save();
        }
    }

    /**
     * Revoke a permission from the user.
     */
    public function revokePermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        $this->permissions = array_values(array_diff($permissions, [$permission]));
        $this->save();
    }

    /**
     * Sync user permissions (replace all permissions).
     */
    public function syncPermissions(array $permissions): void
    {
        $this->permissions = array_values($permissions);
        $this->save();
    }

    /**
     * Get user's permissions grouped by resource category for UI display.
     */
    public function getGroupedPermissions(): array
    {
        $userPermissions = $this->permissions ?? [];
        $availablePermissions = self::getAvailablePermissions();
        $grouped = [];

        foreach ($availablePermissions as $category => $categoryPermissions) {
            $grouped[$category] = [];
            foreach ($categoryPermissions as $permission => $label) {
                $grouped[$category][$permission] = [
                    'label' => $label,
                    'granted' => in_array($permission, $userPermissions, true),
                ];
            }
        }

        return $grouped;
    }
}
