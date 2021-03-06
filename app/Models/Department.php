<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table ='tenants';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address'
    ];

    public function files(){
        return $this->hasMany(File::class,'tenant_id','id');
    }
}
