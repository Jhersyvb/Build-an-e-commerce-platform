<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'name',
        'address_1',
        'city',
        'postal_code',
        'country_id',
        'default',
    ];

    protected $casts = [
        'default' => 'boolean',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($address) {
            if ($address->default) {
                $address->user->addresses()->update([
                    'default' => false,
                ]);
            }
        });
    }

    public function setDefaultAttribute($value)
    {
        $this->attributes['default'] = ($value === 'true' || $value ? true : false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->hasOne(Country::class, 'id', 'country_id');
    }
}
