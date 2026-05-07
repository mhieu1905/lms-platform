<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

class StudentService
{
    /**
     * Delete a student by ID
     * @param mixed $id
     * @throws \Exception
     * @return bool
     * 
     * @author Ho Luu Duc
     * Date: 04-09-2025
     */
    public function deleteStudent($id)
    {
        try {
            $user = User::with([
                'roles',
                'courses',
                'enrolledCourses',
                'completedLessons',
                'completedCourses',
                'events',
                'majors'
            ])->findOrFail($id);

            if (!$user->roles->contains('name', 'student')) {
                throw new \Exception("This user is not a student.");
            }

            if ($user->id === Auth::user()->id) {
                throw new \Exception("You can not delete yourself!");
            }
            
            if ($user->roles->contains('name', 'super_admin')) {
                throw new \Exception("You cannot delete a this user!");
            }

            if ($user->courses()->exists()) {
                throw new \Exception("This user has created courses and cannot be deleted.");
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
     * Get a student by ID from database
     * @param mixed $id
     * @throws \Exception
     * @return User|\Illuminate\Database\Eloquent\Collection<int, User>
     * 
     * @author Ho Luu Duc
     * Date: 04-09-2025
     */
    public function getStudentById($id)
    {
        try {
            $user = User::with('roles', 'enrolledCourses')->withCount('enrolledCourses')->findOrFail($id);

            if (!$user->roles->contains('name', 'student')) {
                throw new \Exception("This user is not a student.");
            }

            return $user;
        } catch (ModelNotFoundException  $e) {
            throw new \Exception("This user was not found in the system.");
        }
        
    }
}
