<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\TeamMember;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index()
    {
        $about = About::firstOrCreate(['id' => 1]);
        $members = TeamMember::orderBy('sort_order')->get();
        
        return view('public.about.index', compact('about', 'members'));
    }
}
