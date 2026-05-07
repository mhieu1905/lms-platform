<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\StudentService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    protected $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->studentService = $studentService;
    }

    /**
     * Display a list of student
     * @return \Illuminate\Contracts\View\View
     * 
     * @author Ho Luu Duc
     * Date: 16-09-2025
     */
    public function index()
    {
        $students = User::sortable(['id' => 'DESC'])->with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'student');
        })->paginate(config('settings.pagination.per_page'));

        return view('admin.users.students.index', compact('students'));
    }

    /**
     * Display details of 1 student
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 16-09-2025
     */
    public function show($id)
    {
        try {
            $student = $this->studentService->getStudentById($id);
            return view('admin.users.students.details', compact('student'));
        } catch (\Exception $e) {
            return redirect()->route('admin.users.students.index')->with('error', $e->getMessage());
        }
    }

    /**
     * Delete a student
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 16-09-2025
     */
    public function destroy(Request $request, $id)
    {
        try {
            $this->studentService->deleteStudent($id);

            $perPage = config('settings.pagination.per_page');
            $page = (int) $request->get('page', 1);

            $total = User::with('roles')->whereHas('roles', function ($query) {
                $query->where('name', 'student');
            })->count();
            $maxPage = (int) ceil($total / $perPage);

            if ($page > $maxPage && $page > 1) {
                $page = $maxPage;
            }

            return redirect()->route('admin.users.students.index', ['page' => $page])->with('success', 'Student deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.students.index')->with('error', $e->getMessage());
        }
    }
}
