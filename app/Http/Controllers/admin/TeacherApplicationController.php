<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TeacherApplicationController extends Controller
{
    /**
     * Display a paginated list of teacher applications.
     * @return \Illuminate\Contracts\View\View
     * 
     * @author Ho Luu Duc
     * Date: 02-09-2025
     */
    public function index()
    {

        $applications = User::with(['majors'])
            ->sortable(['id' => 'DESC'])
            ->whereHas('roles', function ($query) {
                $query->where('name', 'student');
            })
            ->whereDoesntHave('roles', function ($query) {
                $query->whereIn('name', ['teacher', 'admin', 'super_admin']);
            })
            ->where('status', 1)
            ->paginate(config('settings.pagination.per_page'));

        return view('admin.users.teacher_applications.index', compact('applications'));
    }

    /**
     * Display a details of teacher application.
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 02-09-2025
     */
    public function showDetails($id)
    {
        try {
            $user = User::where('id', $id)
                ->whereHas('roles', function ($query) {
                    $query->where('name', 'student');
                })
                ->whereDoesntHave('roles', function ($query) {
                    $query->whereIn('name', ['teacher', 'admin', 'super_admin']);
                })
                ->where('status', 1)
                ->firstOrFail();
                
            return view('admin.users.teacher_applications.details', compact('user'));
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.users.teacher.applications.index')->with('error', 'This user has not applied to be a teacher.');
        }
    }

    /**
     * Approve a user as teacher.
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 02-09-2025
     */
    public function approve(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if (!$user->hasRole('student') || $user->status != 1) {
                return redirect()->back()->with('error', 'This user has not applied to be a teacher.');
            }

            $user->status = 2;
            $user->reviewed_at = Carbon::now();
            $user->save();

            $teacherRole = Role::where('name', 'teacher')->first();

            if ($teacherRole) {
                $user->roles()->syncWithoutDetaching([$teacherRole->id]);
            }

            $perPage = config('settings.pagination.per_page');
            $page = (int) $request->get('page', 1);

            $total = User::where('status', 1)->count();
            $maxPage = (int) ceil($total / $perPage);

            if ($page > $maxPage && $page > 1) {
                $page = $maxPage;
            }

            return redirect()->route('admin.users.teacher.applications.index', ['page' => $page])->with('success', 'User has been approved as teacher.');
        } catch (ModelNotFoundException $e) {
            return redirect()->route('admin.users.teacher.applications.index')->with('error', 'This user was not found in the system.');
        }
    }

    /**
     * Reject a teacher application
     * 
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 02-09-2025
     */
    public function reject(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            if (!$user->hasRole('student') || $user->status != 1) {
                return redirect()
                ->route('admin.users.teacher.applications.index')
                ->with('error', 'The user has not applied to be a teacher.');
            }

            $request->validate([
                'reason' => 'required|string|max:500'
            ]);

            $user->status = 3;
            $user->reject_reason = $request->reason;
            $user->reviewed_at = Carbon::now();
            $user->save();

            return redirect()
            ->route('admin.users.teacher.applications.index')
            ->with('success', 'The request has been rejected');
        } catch (ModelNotFoundException $e) {
            return redirect()
            ->route('admin.users.teacher.applications.index')
            ->with('error', 'This user was not found in the system.');
        }
    }
}
