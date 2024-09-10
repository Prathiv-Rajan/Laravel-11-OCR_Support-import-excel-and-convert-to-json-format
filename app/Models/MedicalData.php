<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalData extends Model
{
    use HasFactory;

    protected $table = "medical_data";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'diagnosis_code',
        'description',
        'label',
    ];
}
