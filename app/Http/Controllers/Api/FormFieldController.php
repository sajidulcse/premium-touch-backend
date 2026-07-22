<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormField;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FormFieldController extends Controller
{
    /**
     * Display a listing of all fields (for Admin).
     */
    public function index()
    {
        $fields = FormField::orderBy('order', 'asc')->get();
        return response()->json($fields);
    }

    /**
     * Display a listing of active fields (for Public form).
     */
    public function activeFields()
    {
        $fields = FormField::where('is_enabled', true)->orderBy('order', 'asc')->get();
        return response()->json($fields);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255',
            'type' => 'required|string|in:text,email,tel,number,select,textarea',
            'placeholder' => 'nullable|string|max:255',
            'options' => 'nullable|array',
            'is_required' => 'boolean',
            'is_enabled' => 'boolean',
            'order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Generate unique snake_case field_name from label
        $fieldName = Str::snake(strtolower(trim($request->label)));
        
        // Ensure uniqueness
        $count = FormField::where('field_name', 'like', $fieldName . '%')->count();
        if ($count > 0) {
            $fieldName = $fieldName . '_' . ($count + 1);
        }

        $fieldData = $request->all();
        $fieldData['field_name'] = $fieldName;

        $field = FormField::create($fieldData);

        return response()->json([
            'message' => 'Custom field created successfully.',
            'data' => $field
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $field = FormField::find($id);

        if (!$field) {
            return response()->json(['message' => 'Field not found'], 404);
        }

        return response()->json($field);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $field = FormField::find($id);

        if (!$field) {
            return response()->json(['message' => 'Field not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'label' => 'required|string|max:255',
            'placeholder' => 'nullable|string|max:255',
            'options' => 'nullable|array',
            'is_required' => 'boolean',
            'is_enabled' => 'boolean',
            'order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Prevent modification of type and field_name for core fields
        $isCoreField = in_array($field->field_name, ['full_name', 'phone', 'email']);
        
        $updateData = $request->only(['label', 'placeholder', 'options', 'is_required', 'is_enabled', 'order']);
        
        if ($isCoreField) {
            // Core fields must always be enabled and required
            $updateData['is_required'] = true;
            $updateData['is_enabled'] = true;
        }

        $field->update($updateData);

        return response()->json([
            'message' => 'Field updated successfully.',
            'data' => $field
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $field = FormField::find($id);

        if (!$field) {
            return response()->json(['message' => 'Field not found'], 404);
        }

        // Prevent deletion of core fields
        $isCoreField = in_array($field->field_name, ['full_name', 'phone', 'email']);
        if ($isCoreField) {
            return response()->json(['message' => 'Cannot delete core form fields (Name, Phone, Email).'], 403);
        }

        $field->delete();

        return response()->json(['message' => 'Field deleted successfully.']);
    }
}
