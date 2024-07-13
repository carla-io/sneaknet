<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Product extends Model implements Searchable
{
    use HasFactory;

    protected $fillable = ['product_name', 'price', 'category_id', 'image'];

    // Define the relationship to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the search result for the model.
     *
     * @return \Spatie\Searchable\SearchResult
     */
    public function getSearchResult(): SearchResult
    {
        // Return the SearchResult object with the product's name and a URL or other relevant information.
        return new SearchResult(
            $this,
            $this->product_name, // This could be any searchable attribute
            $this->category->name ?? '' // Optional: You can also include related model data
        );
    }
}
