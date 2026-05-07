<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    /**
     * Display the list of admin account
     * @return \Illuminate\Contracts\View\View
     * 
     * @author Ho Luu Duc
     * Date: 16-09-2025
     */
    public function index()
    {
        $admins = User::sortable(['id' => 'DESC'])->with('roles')->whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })
        ->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'super_admin');
        })
        ->paginate(config('settings.pagination.per_page'));

        return view('admin.users.admins.index', compact('admins'));
    }

    /**
     * Delete an admin account
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 16-09-2025
     */
    public function destroy(Request $request, $id)
    {
        try {
            $this->adminService->deleteAdmin($id);

            $perPage = config('settings.pagination.per_page');
            $page = (int) $request->get('page', 1);

            $total = User::with('roles')->whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->count();
            $maxPage = (int) ceil($total / $perPage);

            if ($page > $maxPage && $page > 1) {
                $page = $maxPage;
            }

            return redirect()->route('admin.users.admins.index', ['page' => $page])->with('success', 'Admin deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.admins.index')->with('error', $e->getMessage());
        }
    }
}
