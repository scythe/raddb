<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tester extends Model
{
    use SoftDeletes;

    /**
     * Attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['name', 'initials'];

    /**
     * Attribute casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'deleted_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function testdate()
    {
        return $this->hasMany(TestDate::class);
    }
}
