<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
  /** @use HasFactory<\Database\Factories\UserFactory> */
  use HasFactory, Notifiable, HasApiTokens, HasRoles;

  protected $appends = ['photo_url'];
  /**
   * The attributes that are mass assignable.
   *
   * @var list<string>
   */
  protected $fillable = [
    'name',
    'phone',
    'photo',
    'email',
    'address',
    'password',
    'role'
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
      'role' => UserRole::class
    ];
  }

  public function bookings()
  {
    return $this->HasMany(Booking::class);
  }

  public function payment()
  {
    return $this->hasManyThrough(Payment::class, Booking::class );
  }

  public function hasRole($role)
  {
    return $this->role === UserRole::from($role);
  }

  public function getPhotoUrlAttribute()
  {
    $path = $this->attributes['photo'] ?? null;
    if (empty($path)) {
      return asset('avatars/default_avatar.png');
    }

    if (filter_var($path, FILTER_VALIDATE_URL)) {
      return $path;
    }
    return Storage::disk('public')->url($path);
  }

  // protected function photoUrl(): Attribute
  // {
  //   return Attribute::make(
  //     get: function (mixed $value, array $attributes) {

  //       if (empty($attributes['photo'])) {
  //         return 'https://ui-avatars.com/api/?name=' . urlencode($this->name);
  //       }

  //       // Cceck if this external URL (e.g via Google login) or local storage
  //       if (filter_var($attributes['photo'], FILTER_VALIDATE_URL)) {
  //         return $attributes['photo'];
  //       }

  //       return Storage::url($attributes['photo']);
  //     },
  //   );
  // }
}
