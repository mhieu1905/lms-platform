<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Services\FormValidationService;
use App\Services\LevelService;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Level::sortable(['id' => 'ASC'])->paginate(config('settings.pagination.per_page'));
        return view('admin.levels.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.levels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(FormValidationService::requiredLevelName($request));
        $cleanData = LevelService::cleanInput($request->name);

        $data = [
            'name' => $cleanData,
        ];

        $level = Level::create($data);

        if ($level) {
            LogService::log(
                'CREATE',
                'Level',
                $level->id,
                '',
                $data
            );
        }

        return redirect()->route('admin.levels.index')->with('success', 'Level Created Successfully.');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $level = Level::findOrFail($id);
        return view('admin.levels.edit', compact('level'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Level $level)
    {
        $request->validate(FormValidationService::requiredLevelName($request));
        $dataToUpdate = LevelService::updateLevel($level, $request);
        LogService::log(
            'Update',
            'Level',
            $level->id,
            '',
            $dataToUpdate
        );
        return redirect()->route('admin.levels.index')->with('success', 'Level Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        if ($level->courses()->exists()) {
            return redirect()->route('admin.levels.index')->with('error', 'Can Not Delete This Level Because It Has Related Courses.');
        } {
            $data = [
                'name' => $level->name,
            ];

            $level->delete();
            LogService::log(
                'DELETE',
                'Level',
                $level->id,
                '',
                $data
            );
            return redirect()->route('admin.levels.index')->with('success', 'Level Deleted Successfully.');
        }
    }
}
