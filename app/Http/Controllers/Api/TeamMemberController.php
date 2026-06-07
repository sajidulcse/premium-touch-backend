<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeamMemberController extends Controller
{
    public function index()
    {
        return response()->json(
            TeamMember::orderBy('position', 'asc')->orderBy('id', 'asc')->get()
        );
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'role' => 'required|string|max:255',
                'desc' => 'required|string',
                'quote' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'linkedin' => 'nullable|string|max:255',
                'instagram' => 'nullable|string|max:255',
                'facebook' => 'nullable|string|max:255',
                'email' => 'nullable|string|max:255',
                'website' => 'nullable|string|max:255',
                'is_executive' => 'nullable|string|in:true,false,1,0',
                'position' => 'nullable|integer'
            ]);

            $data = $request->only([
                'name', 'role', 'desc', 'quote', 'linkedin', 'instagram', 'facebook', 'email', 'website'
            ]);

            $isExecutive = $request->input('is_executive');
            $data['is_executive'] = ($isExecutive === 'true' || $isExecutive === '1');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/team'), $fileName);
                $data['image'] = 'team/' . $fileName;
            }

            if (!$request->filled('position')) {
                $maxPosition = TeamMember::max('position') ?? 0;
                $data['position'] = $maxPosition + 1;
            } else {
                $data['position'] = (int)$request->input('position');
            }

            $member = TeamMember::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Team member added successfully.',
                'member' => $member
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save team member: ' . $e->getMessage(),
                'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null
            ], 500);
        }
    }

    public function show($id)
    {
        return response()->json(TeamMember::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        try {
            $member = TeamMember::findOrFail($id);

            $request->validate([
                'name' => 'required|string|max:255',
                'role' => 'required|string|max:255',
                'desc' => 'required|string',
                'quote' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
                'clear_image' => 'nullable|string|in:true,false,1,0',
                'linkedin' => 'nullable|string|max:255',
                'instagram' => 'nullable|string|max:255',
                'facebook' => 'nullable|string|max:255',
                'email' => 'nullable|string|max:255',
                'website' => 'nullable|string|max:255',
                'is_executive' => 'nullable|string|in:true,false,1,0',
                'position' => 'nullable|integer'
            ]);

            $data = $request->only([
                'name', 'role', 'desc', 'quote', 'linkedin', 'instagram', 'facebook', 'email', 'website'
            ]);

            if ($request->has('is_executive')) {
                $isExecutive = $request->input('is_executive');
                $data['is_executive'] = ($isExecutive === 'true' || $isExecutive === '1');
            }

            if ($request->has('position')) {
                $data['position'] = (int)$request->input('position');
            }

            if ($request->input('clear_image') === 'true' || $request->input('clear_image') === '1') {
                if ($member->image) {
                    Storage::disk('public')->delete($member->image);
                }
                $data['image'] = null;
            } elseif ($request->hasFile('image')) {
                // Delete old image if it exists
                if ($member->image) {
                    Storage::disk('public')->delete($member->image);
                }

                $file = $request->file('image');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(storage_path('app/public/team'), $fileName);
                $data['image'] = 'team/' . $fileName;
            }

            $member->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Team member updated successfully.',
                'member' => $member
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update team member: ' . $e->getMessage(),
                'errors' => ($e instanceof \Illuminate\Validation\ValidationException) ? $e->errors() : null
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $member = TeamMember::findOrFail($id);
            if ($member->image) {
                Storage::disk('public')->delete($member->image);
            }
            $member->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Team member deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete team member: ' . $e->getMessage()
            ], 500);
        }
    }
}
