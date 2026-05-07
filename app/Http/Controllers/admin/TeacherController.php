<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TeacherService;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    /**
     * Display a list of teacher
     * @return \Illuminate\Contracts\View\View
     * 
     * @author Ho Luu Duc
     * Date: 16-09-2025
     */
    public function index()
    {
        $teachers = User::sortable(['id' => 'DESC'])->with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'teacher');
        })->paginate(config('settings.pagination.per_page'));

        return view('admin.users.teachers.index', compact('teachers'));
    }

    /**
     * Display a details of 1 teacher
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 16-09-2025
     */
    public function show($id)
    {
        try {
            $teacher = $this->teacherService->getTeacherById($id);
            return view('admin.users.teachers.details', compact('teacher'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users.teachers.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Summary of destroy
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        try {
            $this->teacherService->deleteTeacher($id);

            $perPage = config('settings.pagination.per_page');
            $page = (int) $request->get('page', 1);

            $total = User::with('roles')->whereHas('roles', function ($query) {
                $query->where('name', 'teacher');
            })->count();
            $maxPage = (int) ceil($total / $perPage);

            if ($page > $maxPage && $page > 1) {
                $page = $maxPage;
            }

            return redirect()->route('admin.users.teachers.index', ['page' => $page])->with('success', 'Teacher deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.teachers.index')->with('error', $e->getMessage());
        }
    }
}
