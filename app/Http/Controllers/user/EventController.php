<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    /**
     * List events ordered by start time (newest first) with pagination.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public function index(Request $request)
    {
        $perPage = config('settings.pagination.per_page');
        $events = Event::sortable(['id' => 'desc'])
            ->paginate($perPage, ['*'], 'page', $request->get('page', 1));

        return response()
                ->view('admin.events.index', compact('events'))
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache');
    }

    /**
     * Show the form for creating a new event.
     *
     * @return \Illuminate\View\View
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public function create()
    {
        return view('admin.events.create');
    }

    /**
     * Validate and store a new event, including uploading an image if provided.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public function store(Request $request)
    {
        $validator = EventService::validate($request);
        $cleaned = $validator->validate();

        // Check duplicate information
        $duplicateCheck = Event::where([
            'title' => $cleaned['title'],
            'description' => $cleaned['description'],
            'content' => $cleaned['content_json'],
            'address' => $cleaned['address'],
            'total_slots' => $cleaned['total_slots'],
            'cost' => $cleaned['cost'],
            'status' => $cleaned['status'],
            'start_time' => $cleaned['start_time'],
            'finish_time' => $cleaned['finish_time'],
            'booked_slots' => 0,
        ])->exists();

        if ($duplicateCheck) {
            return back()->with('error', 'An event with the same information already exists.')->withInput();
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

            // Move file form temp folder to events folder
            $newName = uniqid() . '.' . pathinfo($tempPath, PATHINFO_EXTENSION);
            $disk->makeDirectory('uploads/events');
            $disk->move($tempPath, 'uploads/events/' . $newName);

            $imagePath = 'uploads/events/' . $newName;
        }

        $data = [
            'title' => $cleaned['title'],
            'description' => $cleaned['description'],
            'content' => $cleaned['content_json'],
            'address' => $cleaned['address'],
            'total_slots' => $cleaned['total_slots'],
            'cost' => $cleaned['cost'],
            'status' => $cleaned['status'],
            'start_time' => $cleaned['start_time'],
            'finish_time' => $cleaned['finish_time'],
            'booked_slots' => 0,
            'image' => $imagePath,
        ];

        $event = Event::create($data);

        if ($event && $request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/events', 'public');
            $event->update(['image' => $imagePath]);
        }

        if (!$event) {
            return back()->with('error', 'Failed to create event.')->withInput();
        }

        return redirect()->route('admin.events.index')->with('success', 'Event added successfully.');
    }

    /**
     * Show the form for editing an existing event.
     * @param mixed $id
     * @return \Illuminate\Contracts\View\View
     * 
     * @author Ho Luu Duc
     * Date: Date: 22-08-2025
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Handle the update request.
     * @param \Illuminate\Http\Request $request
     * @param mixed $id
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public function update(Request $request, $id)
    {
        $validator = EventService::validate($request, true);
        $validator->validate();

        $event = Event::findOrFail($id);

        $dataToUpdate = EventService::updateEvent($event, $request);

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully');
    }

    /**
     * Delete an existing event by its ID.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public function destroy(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return redirect()->route('admin.events.index')
                ->with('error', 'Event not found or has already been deleted.');
        }

        $event->delete();

        $perPage = config('settings.pagination.per_page');
        $page = (int) $request->get('page', 1);

        // Recalculate total number of records after deletion
        $total = Event::count();
        $maxPage = (int) ceil($total / $perPage);

        // If current page is larger than maxPage then go back to maxPage
        if ($page > $maxPage && $maxPage > 0) {
            $page = $maxPage;
        }

        $url = route('admin.events.index', ['page' => $page]);

        return redirect()->to($url)->with('success', 'Event deleted successfully.');
    }



    /**
     * Toggle the status of an event (active/inactive) and return the new status.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * 
     * @author Ho Luu Duc
     * Date: 22-08-2025
     */
    public function toggleStatus($id)
    {
        $event = Event::findOrFail($id);
        $event->status = !$event->status;
        $event->save();

        return response()->json(['status' => $event->status]);
    }

}
