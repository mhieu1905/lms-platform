<?php

namespace App\Jobs;

use App\Mail\InactiveUserReminderMail;
use App\Models\User;
use App\Models\UserStudyInsight;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class UpdateUserStudyInsights implements ShouldQueue
{
    use Queueable;

    public function handle(): void
    {
        Log::info('--- UpdateUserStudyInsights job started ---');

        $users = User::whereHas('activityLogs')
            ->with(['enrolledCourses.lessons', 'completedLessons', 'completedCourses', 'activityLogs'])
            ->get();

        foreach ($users as $user) {
            $enrolledCourses = $user->enrolledCourses;
            $completedCourses = $user->completedCourses;

            $enrolledCount = $enrolledCourses->count();
            $completedCount = $completedCourses->count();

            // Calculate average progress
            $progressSum = 0;
            foreach ($enrolledCourses as $course) {
                $totalLessons = $course->lessons->count();
                $completedLessons = $user->completedLessons()
                    ->whereIn('lessons.id', $course->lessons->pluck('id'))
                    ->count();

                $progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
                $progressSum += $progress;
            }

            $avgProgress = $enrolledCount > 0 ? round($progressSum / $enrolledCount, 2) : 0;

            // Calculate inactivity
            $lastActivity = $user->activityLogs->max('created_at');
            $inactiveDays = $lastActivity ? Carbon::parse($lastActivity)->diffInDays(now()) : 999;
            $riskScore = $this->calculateRiskScore($avgProgress, $inactiveDays);

            // Save insight
            $insight = UserStudyInsight::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'total_courses' => $enrolledCount,
                    'completed_courses' => $completedCount,
                    'avg_progress' => $avgProgress,
                    'last_activity_at' => $lastActivity,
                    'inactive_days' => $inactiveDays,
                    'risk_score' => $riskScore,
                ]
            );

            $canSend = !$insight->last_reminder_sent_at || now()->diffInDays($insight->last_reminder_sent_at) >= 7;
            if (!$canSend) continue;

            // Determine type of email
            $type = null;
            if ($inactiveDays > 14) {
                $type = 'inactive'; // User hasn't logged in for a long time
            } elseif ($inactiveDays <= 14 && $avgProgress < 30 && $enrolledCount >= 3) {
                $type = 'low_progress'; // User has many courses but low learning progress
            }

            if ($type) {
                try {
                    Mail::to($user->email)->queue(new InactiveUserReminderMail($user, $insight, $type));
                    $insight->update([
                        'last_reminder_sent_at' => now(),
                        'email_status' => 'sent'
                    ]);
                    Log::info("[$type] Reminder email sent to {$user->email}", [
                        'inactive_days' => $inactiveDays,
                        'avg_progress' => $avgProgress,
                    ]);
                } catch (\Exception $e) {
                    $insight->update(['email_status' => 'failed']);
                    Log::error("Failed to send reminder to {$user->email}: " . $e->getMessage());
                }
            }
        }

        Log::info('--- UpdateUserStudyInsights job completed ---');
    }

    private function calculateRiskScore($avgProgress, $inactiveDays)
    {
        $score = 100 - ($avgProgress * 0.7) - ($inactiveDays * 1.3);
        return max(0, min(100, $score));
    }
}
