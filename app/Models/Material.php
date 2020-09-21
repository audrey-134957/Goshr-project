<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Material extends Model
{

    protected $fillable = [
        'name',
        'project_id'
    ];


    // protected static function boot()
    // {
    //     parent::boot();

    //     self::creating(function ($material) {
    //         $material->project_id = auth()->id;
    //     });

    //     self::saving(function ($material){
    //         $material->project_id = auth()->id;
    //     });
    // }


    // un materiel appartient  à un utilisateur.
    public function project()
    {

        return $this->belongsTo(Project::class);
    }

}
