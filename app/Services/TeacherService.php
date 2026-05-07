<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class TeacherService
{
    /**
     * Delete a teacher by ID
     * @param mixed $id
     * @throws \Exception
     * @return bool
     * 
     * @author Ho Luu Duc
     * Date: 04-09-2025
     */
    public function deleteTeacher($id)
    {
        try {
            $user = User::with('roles')->findOrFail($id);

            if (!$user->roles->contains('name', 'teacher')) {
                throw new \Exception("This user is not a teacher.");
            }

            if ($user->id === Auth::user()->id) {
                throw new \Exception("You can not delete yourself!");
            }

            if ($user->roles->contains('name', 'super_admin')) {
                throw new \Exception("You cannot delete a this user!");
            }

            $manyToManyRelations = [
                'enrolledCourses' => 'enrolled courses',
                'completedLessons' => 'completed lessons',
                'completedCourses' => 'completed courses',
                'events' => 'events tickets',
                'majors' => 'majors'
            ];

            foreach ($manyToManyRelations as $relation => $label) {
                if ($user->$relation()->exists()) {
                    throw new \Exception("This user has $label and cannot be deleted.");
                }
            }

            $user->delete();

            return true;
        } catch (ModelNotFoundException $e) {
            throw new \Exception("This user was not found in the system.");
        }
    }

    /**
     * Get a teacher by ID from database
     * @param mixed $id
     * @throws \Exception
     * @return User|\Illuminate\Database\Eloquent\Collection<int, User>
     * 
     * @author Ho Luu Duc
     * Date: 04-09-2025
     */
    public function getTeacherById($id)
    {
        try {
            $user = User::with('roles', 'courses')->withCount('courses')->findOrFail($id);

            if (!$user->roles->contains('name', 'teacher')) {
                throw new \Exception("This user is not a teacher.");
            }

            return $user;
        } catch (ModelNotFoundException  $e) {
            throw new \Exception("This user was not found in the system.");
        }
        
    }
}
