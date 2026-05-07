<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Models\FooterSection;

use App\Services\FormValidationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class FooterController extends Controller
{
    public function index()
    {
        $footers = FooterSection::sortable(['id' => 'desc'])->paginate(config('settings.pagination.per_page'));
        $footers->getCollection()->transform(function ($footer) {
            $footer->content = json_decode($footer->content);
            return $footer;
        });
        return view('cms.footers.index', ['data' => $footers]);
    }
    public function createCopyright()
    {
        return view('cms.footers.create-copyright');
    }

    public function createMain()
    {
        return view('cms.footers.create-main');
    }
    public function createLogo()
    {
        return view('cms.footers.create-logo');
    }
    public function createSocial()
    {
        return view('cms.footers.create-social');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $data = json_decode($request->input('contentInput'), true);
        $request->merge($data);
        $validator = FormValidationService::footerRules($request, false, $request->key_id);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first())->withInput();
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $destinationPath = public_path('assets/images/home/logo');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $filename);

            $data['logo'] = $filename;
        }

        $footer = FooterSection::create([
            'key_id'  => $request->key_id,
            'content' => json_encode($data),
        ]);
        return redirect()->route('cms.footers.index')->with('success', 'Footer Section Created Successfully');
    }

    public function edit($id)
    {
        $footers = FooterSection::findOrFail($id);
        $footers->content = json_decode($footers->content);
        return view('cms.footers.edit', [
            'footers' => $footers,
            'key' => $footers->key_id
        ]);
    }

    public function update(Request $request, $id)
    {
        $footer = FooterSection::findOrFail($id);
        $items = $request->input('items');

        $itemsArray = [];
        if (!empty($items)) {
            foreach ($items as $item) {
                if (isset($item['label']) && (isset($item['link']) || isset($item['text']))) {
                    $itemsArray[] = [
                        'label' => $item['label'],
                        'link' => $item['link'] ?? $item['text']
                    ];
                }
            }
        }
        
        $newData = [
            'items' => $itemsArray
        ];
        $validator = FormValidationService::footerRules($request, true, $request->key_id);
        if ($validator->fails()) {
            return back()->with('error', $validator->errors()->first())->withInput();
        }
        if ($request->key_id === '2') {
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $destinationPath = public_path('assets/images/home/logo');

                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }

                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move($destinationPath, $filename);

                $oldData = json_decode($footer->content, true);
                if (!empty($oldData['logo'])) {
                    $oldpath = $destinationPath . '/' . $oldData['logo'];
                    if (File::exists($oldpath)) {
                        File::delete($oldpath);
                    }
                }

                $newData['logo'] = $filename;
            } else {
                $oldData = json_decode($footer->content, true);
                if (!empty($oldData['logo'])) {
                    $newData['logo'] = $oldData['logo'];
                }
            }
        } elseif ($request->key_id === '3') {
            $newData['copyright'] = $request->copyright;
        } elseif ($request->key_id === '1') {
            $newData['title'] = $request->title;
        } else {
        }

        $oldContent = json_encode(json_decode($footer->content, true), JSON_UNESCAPED_UNICODE);
        $newContent = json_encode($newData, JSON_UNESCAPED_UNICODE);
        if ($oldContent === $newContent) {
            return back()->with('success', 'No Change');
        }

        $updateData = ['content' => $newContent];
        $updateData['key_id'] = $request->input('key_id');

        $footer->update($updateData);


        return redirect()->route('cms.footers.index')->with('success', 'Footer Section Updated Successfully');
    }
    public function destroy($id)
    {
        $footer = FooterSection::findOrFail($id);
        $footer->delete();
        return redirect()->route('cms.footers.index')->with('success', 'Footer Section Deleted Successfully');
    }
}
