<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table ='files';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'path',
        'size',
        'user_id',
        'category_id',
        'tenant_id'
    ];
    public function creator() {
        return $this->belongsTo(User::class,'user_id', 'id');
    }
    public function category() {
        return $this->belongsTo(Category::class,'category_id', 'id');
    }
}
