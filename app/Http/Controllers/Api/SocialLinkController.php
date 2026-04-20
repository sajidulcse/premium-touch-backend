<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;

class SocialLinkController extends Controller
{
    public function index()
    {
        return response()->json(
            SocialLink::where('status', 1)
                ->orderBy('position')
                ->get()
        );
    }
}
