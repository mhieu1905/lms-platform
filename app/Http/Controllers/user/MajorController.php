<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Services\MajorService;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    /**
     * Display a paginated list of majors.
     * 
     * @return \Illuminate\Http\Response
     * 
     * @author Ho Luu Duc
     * Date: 28-08-2025
     */
    public function index()
    {
        $majors = Major::sortable(['id' => 'DESC'])->paginate(config('settings.pagination.per_page'));

        return response()
                ->view('admin.majors.index', compact('majors'))
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache');
    }

    /**
     * Show the form for creating a new major.
     * @return \Illuminate\Contracts\View\View
     * 
     * @author Ho Luu Duc
     * Date: 28-08-2025
     */
    public function create()
    {
        return view('admin.majors.create');
    }

    /**
     * Store a newly created major in the database.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 28-08-2025
     */
    public function store(Request $request)
    {
        $validator = MajorService::validate($request);
        $validator->validate();
        $cleaned = $validator->getData();

        $data = ['name' => $cleaned['name']];

        $major = Major::create($data);

        if (!$major) {
            return back()->with('error', 'Failed to create new major.');
        }

        return redirect()->route('admin.majors.index')->with('success', 'Major added successfully.');
    }

    /**
     * Show a form for editing a major
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View
     * 
     * @author Ho Luu Duc
     * Date: 28-08-2025
     */
    public function edit($id)
    {
        $major = Major::findOrFail($id);

        return view('admin.majors.edit', compact('major'));
    }

    /**
     * Update an existing major in the database.
     * 
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 28-08-2025
     */
    public function update(Request $request, $id)
    {
        $major = Major::findOrFail($id);

        $dataToUpdate = MajorService::updateMajor($major, $request);

        return redirect()->route('admin.majors.index')->with('success', 'Major updated successfully.');
    }

    /**
     * Delete a major from the database.
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 28-08-2025
     */
    public function destroy(Request $request, $id)
    {
        $major = Major::find($id);

        if (!$major) {
            return redirect()->route('admin.majors.index')
                ->with('error', 'Major not found or has already been deleted.');
        }

        $major->delete();

        $perPage = config('settings.pagination.per_page');
        $page = (int) $request->get('page', 1);

        $total = Major::count();
        $maxPage = (int) ceil($total / $perPage);

        if ($page > $maxPage && $page > 1) {
            $page = $maxPage;
        }

        return redirect()->route('admin.majors.index', ['page' => $page])->with('success', 'Major deleted successfully.');
    }
}
