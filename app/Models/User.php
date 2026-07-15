<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'avatar', 'is_vip'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

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
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role !== 'customer';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isInventoryManager(): bool
    {
        return $this->role === 'inventory_manager';
    }

    public function isOpsStaff(): bool
    {
        return $this->role === 'ops_staff';
    }

    public function isCustomerSuccess(): bool
    {
        return $this->role === 'customer_success';
    }

    public function isFinanceManager(): bool
    {
        return $this->role === 'finance_manager';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
                return $this->avatar;
            }
            
            $disk = config('filesystems.default');
            if ($disk === 'local' || $disk === 'public') {
                return asset('storage/' . $this->avatar);
            }
            
            return \Illuminate\Support\Facades\Storage::disk($disk)->url($this->avatar);
        }
        
        // Default avatar via ui-avatars
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=000000&background=F3F4F6';
    }

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\QueuedVerifyEmail);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\QueuedResetPassword($token));
    }
}
