<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
    'id', 'menu_id', 'name', 'description', 'price',
    'discount_pct', 'operational_cost', 'is_best_seller', 
    'emoji', 'image_path', 'is_active'
    ];
}