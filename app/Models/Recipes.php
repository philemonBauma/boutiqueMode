<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Recipes extends Model
{
    
    protected function  casts(): array
    {
        return [
            'ingredients' => 'array',
            'instructions' => 'array'
        ];
    }
}
{}
?>