<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $primaryKey;
    protected $images;
    protected $zip;
    protected $body;

    public function __construct(string $url)
    {


    }
}
