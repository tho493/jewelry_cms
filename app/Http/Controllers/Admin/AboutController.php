<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\About;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function edit()
    {
        $about = About::firstOrCreate(['id' => 1]);
        return view('admin.about.edit', compact('about'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $about = About::firstOrCreate(['id' => 1]);
        $about->update($request->only('title', 'content'));

        return redirect()->route('admin.about.edit')->with('success', 'Đã cập nhật thông tin giới thiệu thành công.');
    }
}
