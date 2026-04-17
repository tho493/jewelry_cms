<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        $members = TeamMember::orderBy('sort_order')->get();
        return view('admin.team-members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.team-members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:5120',
            'bio' => 'nullable|string',
            'custom_link' => 'nullable|url|max:255',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('avatar');

        if ($request->hasFile('avatar')) {
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        TeamMember::create($data);

        return redirect()->route('admin.team-members.index')->with('success', 'Đã thêm thành viên.');
    }

    public function edit(TeamMember $teamMember)
    {
        return view('admin.team-members.edit', compact('teamMember'));
    }

    public function update(Request $request, TeamMember $teamMember)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'avatar' => 'nullable|image|max:5120',
            'bio' => 'nullable|string',
            'custom_link' => 'nullable|url|max:255',
            'sort_order' => 'integer',
        ]);

        $data = $request->except('avatar');

        if ($request->hasFile('avatar')) {
            if ($teamMember->avatar_path) {
                Storage::disk('public')->delete($teamMember->avatar_path);
            }
            $data['avatar_path'] = $request->file('avatar')->store('avatars', 'public');
        }

        $teamMember->update($data);

        return redirect()->route('admin.team-members.index')->with('success', 'Đã cập nhật thành viên.');
    }

    public function destroy(TeamMember $teamMember)
    {
        if ($teamMember->avatar_path) {
            Storage::disk('public')->delete($teamMember->avatar_path);
        }
        $teamMember->delete();
        return back()->with('success', 'Đã xóa thành viên khỏi danh sách.');
    }
}
