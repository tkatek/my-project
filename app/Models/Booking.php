<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Package;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'email',
        'phone',
        'package_id',
        'package_name',
        'visit_date',
        'guests',
        'notes',
        'status',
    ];

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
