<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('order')->get();
        return response()->json($pages);
    }

    public function active()
    {
        $pages = Page::where('active', true)->orderBy('order')->get();
        return response()->json($pages);
    }

    public function show($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        return response()->json($page);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_en' => 'required|string|max:255',
            'title_ar' => 'required|string|max:255',
            'content_en' => 'required|string',
            'content_ar' => 'required|string',
            'meta_description_en' => 'nullable|string|max:255',
            'meta_description_ar' => 'nullable|string|max:255',
            'slug' => 'nullable|string|unique:pages,slug',
            'active' => 'boolean',
            'order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->all();
        if (!isset($data['slug']) || empty($data['slug'])) {
            $data['slug'] = Str::slug($request->title_en);
        }

        $page = Page::create($data);

        return response()->json($page, 201);
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'title_en' => 'sometimes|string|max:255',
            'title_ar' => 'sometimes|string|max:255',
            'content_en' => 'sometimes|string',
            'content_ar' => 'sometimes|string',
            'meta_description_en' => 'nullable|string|max:255',
            'meta_description_ar' => 'nullable|string|max:255',
            'slug' => 'sometimes|string|unique:pages,slug,' . $id,
            'active' => 'boolean',
            'order' => 'integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $page->update($request->all());

        return response()->json($page);
    }

    public function destroy($id)
    {
        $page = Page::findOrFail($id);
        $page->delete();
        return response()->json(['message' => 'Page deleted successfully']);
    }
}
