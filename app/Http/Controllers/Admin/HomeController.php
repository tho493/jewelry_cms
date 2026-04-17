<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSetting;
use App\Models\HomeSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function edit()
    {
        $setting = HomeSetting::instance();
        $slides  = HomeSlide::orderBy('sort_order')->get();

        return view('admin.home.edit', compact('setting', 'slides'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_label'              => 'required|string|max:100',
            'hero_title_line1'        => 'required|string|max:100',
            'hero_title_line2'        => 'required|string|max:100',
            'hero_description'        => 'nullable|string',
            'hero_btn_primary_text'   => 'nullable|string|max:60',
            'hero_btn_secondary_text' => 'nullable|string|max:60',
            'featured_title'          => 'nullable|string|max:120',
            'featured_subtitle'       => 'nullable|string|max:200',
        ]);

        $setting = HomeSetting::instance();
        $setting->update($request->only([
            'hero_label', 'hero_title_line1', 'hero_title_line2',
            'hero_description', 'hero_btn_primary_text', 'hero_btn_secondary_text',
            'featured_title', 'featured_subtitle',
        ]));

        return redirect()->back()->with('success', 'Đã lưu cài đặt trang chủ.');
    }

    // ── Slide API ────────────────────────────────────────────────────

    public function slideUpload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $path  = $request->file('image')->store('home/slides', 'public');
        $count = HomeSlide::count();
        $slide = HomeSlide::create([
            'image_path' => $path,
            'caption'    => '',
            'sort_order' => $count,
            'is_active'  => true,
        ]);

        return response()->json(['success' => true, 'slide' => [
            'id'        => $slide->id,
            'image_url' => $slide->image_url,
            'caption'   => $slide->caption,
        ]]);
    }

    public function slideUpdate(Request $request, HomeSlide $slide)
    {
        $request->validate(['caption' => 'nullable|string|max:255']);
        $slide->update(['caption' => $request->caption]);

        return response()->json(['success' => true]);
    }

    public function slideDestroy(HomeSlide $slide)
    {
        Storage::disk('public')->delete($slide->image_path);
        $slide->delete();

        return response()->json(['success' => true]);
    }

    public function slideReorder(Request $request)
    {
        $request->validate(['ids' => 'required|array', 'ids.*' => 'integer|exists:home_slides,id']);
        foreach ($request->ids as $order => $id) {
            HomeSlide::where('id', $id)->update(['sort_order' => $order]);
        }

        return response()->json(['success' => true]);
    }
}
