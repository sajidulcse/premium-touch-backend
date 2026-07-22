<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class ClientReview extends Model
{
    use LogsActivity;

    protected $table = 'client_reviews';

    protected $fillable = ['quote', 'author', 'location', 'image'];
}
