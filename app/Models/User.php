<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'email',
        'first_name',
        'last_name',
        'patronymic',
        'password',
        'active',
        'updated_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * @param mixed $value
     * @return void
     */
    public function setPasswordAttribute(mixed $value): void
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * @param array{
     *     name: string,
     *     email: string,
     *     password: string,
     * } $payload
     * @return User
     */
    public static function createWriter(array $payload): User
    {
        return User::create($payload)->assignRole('writer');
    }

    /**
     * @return HasManyThrough
     */
    public function articles(): HasManyThrough
    {
        return $this->hasManyThrough(Article::class,Publication::class, 'article_id', 'id');
    }

    /**
     * @return HasManyThrough
     */
    public function rubrics(): HasManyThrough
    {
        return $this->hasManyThrough(Rubric::class,Publication::class, 'rubric_id', 'id');
    }

    /**
     * @param User $model
     * @return bool
     */
    public function same(User $model): bool
    {
        return $this->id === $model->id;
    }

    public function deleteWithPublications(): void
    {
        Publication::where('user_id', $this->id)
            ->delete();

        $this->delete();
    }
}
