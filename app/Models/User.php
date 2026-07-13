<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'role',
        'admin_type',
        'nip',
        'phone_number',
    ];

    /**
     * Reservasi milik dosen ini.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDosen(): bool
    {
        return $this->role === 'dosen';
    }

    public function isAdmin1(): bool
    {
        return $this->isAdmin() && $this->admin_type === 1;
    }

    public function isAdmin2(): bool
    {
        return $this->isAdmin() && $this->admin_type === 2;
    }

    public function canManageRoom(Room $room): bool
    {
        if ($this->isAdmin1()) {
            return (string)$room->lantai !== '19';
        }
        if ($this->isAdmin2()) {
            return (string)$room->lantai === '19';
        }
        return false;
    }

    public function canManageReservation(Reservation $reservation): bool
    {
        return $reservation->room ? $this->canManageRoom($reservation->room) : false;
    }

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
        ];
    }
}
