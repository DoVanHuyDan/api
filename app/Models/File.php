<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $table ='files';

    public $timestamps = true;

    protected $fillable = [
        'name',
    ];
    // public function users(){
    //     return $this->belongsToMany(User::class,'tasks_users_relation','task_id','user_id');
    // }
    // public function creator() {
    //     return $this->belongsTo(User::class,'creator', 'id');
    // }
    // public function files(){
    //     return $this->hasMany(File::class,'task_id','id');
    // }
    // public function status(){
    //     return $this->hasOne(Status::class,'id','status_id');
    // }
}
