<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceTiming extends Model
{
    use HasFactory;

    protected $table = 'servicetimings';

    protected $fillable = [
        'name',
        'arabic_name',
        'description',
        'arabic_description',
        'duration',
        'type',
        'arabic_type',
        'image',
        'service_id',
    ];

    public function service()
    {
        return $this->hasOne(Service::class, 'id', 'service_id');
    }
}