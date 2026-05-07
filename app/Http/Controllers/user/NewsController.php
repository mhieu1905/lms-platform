<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\News;
use App\Services\FormValidationService;
use App\Services\NewsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    /**
     * Display a list of news
     * 
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $news = News::sortable(['id' => 'desc'])->paginate(config('settings.pagination.per_page'));
        return view('admin.news.index', compact('news'));
    }
    /**
     * Display view to create a news
     * 
     * @return \Illuminate\Contracts\View\View
     * 
     * Author: Minh Hieu
     * Date:25/08/2025

     */
    public function create()
    {
        $categories = Category::orderByDesc('id')->get();
        return view('admin.news.create', compact('categories'));
    }

    /**
     * Store news
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * Author: Minh Hieu
     * 
     * Date:25/08/2025
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $filePath = null;
        try {
            $cleanData = NewsService::cleanRequestData($request->all());
            $validator = Validator::make($cleanData, FormValidationService::newsRule($request));

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            if ($request->hasFile('image')) {
                if (!Storage::disk('public')->exists('uploads/news')) {
                    (Storage::disk('public')->makeDirectory('uploads/news'));
                }

                $filePath = $request->file('image')->store('uploads/news', 'public');

                $fileName = basename($filePath);
            } else {
                $fileName = null;
            }

            $data = [
                'title' => $cleanData['title'],
                'date' => $cleanData['date'],
                'category_id' => $cleanData['category_id'],
                'description' => $cleanData['description'],
                'image' => $fileName,
                'user_id' => $user->id,
            ];
            News::create($data);
            return redirect()->route('admin.news.index')->with('success', "Created successfully");
        } catch (\Exception $e) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            return back()->with('error', 'Something Wrong: ' . $e->getMessage());
        }
    }

    /**
     * Return view to edit a news
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View
     * 
     * Author: Minh Hieu
     */
    public function edit($id)
    {
        $categories = Category::orderByDesc('id')->get();
        $newsEdit = News::findOrFail($id);
        return view('admin.news.edit', compact('newsEdit', 'categories'));
    }

    /**
     * Update news
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     * 
     * Author: Minh Hieu
     */
    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $news = News::findOrFail($id);
        $cleanData = NewsService::cleanRequestData($request->all());
        $validator = Validator::make($cleanData, FormValidationService::newsRule($request, false));

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $dataUpdate = NewsService::updateNews($news, $request);
        return redirect()->route('admin.news.index')->with('success', 'Updated successfully');
    }

    /**
     * Delete news with $id
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $news = News::findOrFail($id);

        // Delete associated image from storage if exists
        if ($news->image && Storage::disk('public')->exists('uploads/news/' . $news->image)) {
            Storage::disk('public')->delete('uploads/news/' . $news->image);
        }

        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Delete success.');
    }
    /**
     * Show details of a news in home page
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View
     * 
     * Author: Minh Hieu
     */
    public function show($id)
    {
        $news = News::findOrFail($id);

        $latestNews = News::orderByDesc('date')->limit(config("settings.popular_courses.limits.home.news-details") ?? 3)->get();

        $youMayLike = News::where('category_id', '=', $news->category_id)
            ->where('id', '!=', $news->id)
            ->orderByDesc('date')
            ->get();

        $nextPost = News::where('date', '>', $news->date)
            ->orderBy('date', 'asc')
            ->first();

        $prevPost = News::where('date', '<', $news->date)
            ->orderBy('date', 'desc')
            ->first();

        return view('home.news-details', compact('news', 'latestNews', 'youMayLike', 'nextPost', 'prevPost'));
    }

    /**
     * Toggle button to change status view or hidden of news
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * Author: Minh Hieu
     */
    public function toggleStatus($id)
    {
        $news = News::findOrFail($id);
        $news->status = !$news->status;
        $news->save();

        return response()->json(['status' => $news->status]);
    }

    /**
     * Show the list of news on hơm page
     * @return \Illuminate\Contracts\View\View
     * 
     * @author Ho Luu Duc
     * Date: 08-09-2025
     */
    public function showHome()
    {
        $news = News::with('user', 'category')->orderByDesc('id')->paginate(config('settings.news_home.per_page'));
        return view('home.news', compact('news'));
    }
}
