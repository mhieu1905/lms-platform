<?php

namespace App\Http\Controllers\cms\home_page;

use App\Http\Controllers\Controller;
use App\Models\HomePage\Slider;
use App\Services\SliderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Show the form for creating a new slider.
     *
     * @return \Illuminate\View\View
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function create ()
    {
        return view('cms.home-page.slider-create');
    }

    /**
     * Store a newly created slider in the database.
     *
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing slider data.
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function store(Request $request)
    {
        $validator = SliderService::validate($request);
        $validator->validate();
        $cleaned = SliderService::cleanRequestData($request->all());

        // Check duplicate information
        $exists = Slider::where([
            'title'       => $cleaned['title'],
            'subtitle'    => $cleaned['subtitle'],
            'button_text' => $cleaned['button_text'],
            'button_link' => $cleaned['button_link'],
            'status'      => $request->status,
        ])->exists();

        if ($exists) {
            return back()->with('error', 'This slider already exists.')->withInput();
        }

        $imagePath = null;

        // Handle temporary file
        if (!empty($cleaned['image_path'])) {
            $tempPath = str_replace('/storage/', '', $cleaned['image_path']);
            $disk = Storage::disk('public');

            if (!$disk->exists($tempPath)) {
                return back()->withErrors(['image' => 'File not found'])->withInput();
            }

            // Validate mime type & size
            /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
            $mime = $disk->mimeType($tempPath);
            $validTypes = ['image/jpeg','image/png','image/jpg','image/gif','image/webp'];
            if (!in_array($mime, $validTypes)) {
                return back()->withErrors(['image' => 'File type is not allowed'])->withInput();
            }
            if ($disk->size($tempPath) > 2*1024*1024) {
                return back()->withErrors(['image' => 'File must be smaller than 2MB'])->withInput();
            }

            // Move file form temp folder to sliders folder
            $newName = uniqid() . '.' . pathinfo($tempPath, PATHINFO_EXTENSION);
            $disk->makeDirectory('uploads/sliders');
            $disk->move($tempPath, 'uploads/sliders/' . $newName);

            $imagePath = 'uploads/sliders/' . $newName;
        }

        $data = [
            'title' => $cleaned['title'],
            'subtitle' => $cleaned['subtitle'],
            'image' => $imagePath,
            'button_text' => $cleaned['button_text'],
            'button_link' => $cleaned['button_link'],
            'status' => $request->status,
        ];

        $slider = Slider::create($data);

        if (!$slider) {
            return back()->with('error', 'Failed to create slider.')->withInput();
        }

        return redirect()->route('cms.home-page.slider.index')->with('success', 'Slider added successfully.');
    }

    /**
     * Display a list of sliders.
     * @return \Illuminate\Http\Response
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function index()
    {
        $sliders = Slider::sortable(['id' => 'DESC'])->paginate(config('settings.pagination.per_page'));

        return response()
                ->view('cms.home-page.slider', compact('sliders'))
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache');
    }
    /**
     * Toggle the status (active/inactive) of a slider.
     *
     * @param int $id The ID of the slider to toggle.
     * @return \Illuminate\Http\JsonResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function toggleStatus($id)
    {
        $slider = Slider::findOrFail($id);
        $slider->status = !$slider->status;
        $slider->save();

        return response()->json(['status' => $slider->status]);
    }

    /**
     * Delete a slider from the database.
     *
     * @param int $id The ID of the slider to delete.
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function destroy(Request $request, $id)
    {
        $slider = Slider::find($id);

        if (!$slider) {
            return redirect()->route('cms.home-page.slider.index')
                ->with('error', 'Slider not found or has already been deleted.');
        }

        $slider->delete();

        // Recalculate total number of records after deletion
        $perPage = config('settings.pagination.per_page');
        $page = (int) $request->get('page', 1);

        // If current page is larger than maxPage then go back to maxPage
        $total = Slider::count();
        $maxPage = (int) ceil($total / $perPage);

        if ($page > $maxPage && $page > 1) {
            $page = $maxPage;
        }

        return redirect()->route('cms.home-page.slider.index', ['page' => $page])->with('success', 'Slider deleted successfully.');
    }

    /**
     * Show the form for editing a slider.
     *
     * @param int $id The ID of the slider to edit.
     * @return \Illuminate\View\View
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function edit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('cms.home-page.slider-edit', compact('slider'));
    }

    /**
     * Update an existing slider in the database.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing slider data.
     * @param int $id The ID of the slider to update.
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 21-08-2025
     */
    public function update(Request $request, $id)
    {
        $validator = SliderService::validate($request, true);
        $validator->validate();

        $slider = Slider::findOrFail($id);
        
        $dataToUpdate = SliderService::updateSlider($slider, $request);

        return redirect()->route('cms.home-page.slider.index')->with('success', 'Slider updated successfully.');
    }

}
