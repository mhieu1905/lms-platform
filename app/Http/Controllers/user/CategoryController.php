<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\ChapterService;
use App\Services\FormValidationService;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Category::sortable(['id' => 'ASC'])->paginate(config('settings.pagination.per_page'));
        return view('admin.categories.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(FormValidationService::requiredCategoryName($request));
        $cleanData = CategoryService::cleanInput($request->name);

        $data = [
            'name' => $cleanData
        ];
        $submitToken = uniqid('submit_', true);
        if (Session::get('submit_token') === $submitToken) {
            return redirect()->back()->with('error', 'PROCESSING');
        }
        Session::put('submit_token', $submitToken);
        $category = Category::create($data);
        Session::forget('submit_token');
        if ($category) {
            LogService::log(
                'CREATE',
                'Category',
                $category->id,
                '',
                $data
            );
        }

        return redirect()->route('admin.categories.index')->with('success', 'Category Created Successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $request->validate(FormValidationService::requiredCategoryName($request));
        $dataToUpdate = CategoryService::updateCategory($category, $request);
        LogService::log(
            'Update',
            'Category',
            $category->id,
            '',
            $dataToUpdate
        );
        return redirect()->route('admin.categories.index')->with('success', 'Category Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        if ($category->courses()->exists()) {
            return redirect()->route('admin.categories.index')->with('error', 'Can Not Delete This Category Because It Has Related Courses.');
        }
        $data = [
            'name' => $category->name,
        ];

        $category->delete();
        LogService::log(
            'DELETE',
            'Category',
            $category->id,
            '',
            $data
        );
        return redirect()->route('admin.categories.index')->with('success', 'Category Deleted Successfully.');
    }
}
