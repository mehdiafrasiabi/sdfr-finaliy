<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    
    use HasFactory;//
    use HasFactory, softDeletes;

    protected $guarded = [];

    public function submit($formData, $categoryId)
    {
        if ($formData['parentId'] == "") {
            $formData['parentId'] = null;
        }
        Category::query()->updateOrCreate(
            [
                'id' => $categoryId
            ],
            [
                'name' => $formData['name'],
                'category_id' => $formData['parentId'],
            ]
        );
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');

    }

    public function children()
    {
        return $this->hasMany(Category::class, 'category_id', 'id');

    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

}
