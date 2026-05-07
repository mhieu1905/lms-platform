<?php 
return [
    'required' => 'The :attribute field is required.',
    'unique'   => 'The :attribute has already been taken.',
    'min'      => [
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max'      => [
        'string' => 'The :attribute may not be greater than :max characters.',
    ],
    'exists'   => 'The selected :attribute is invalid.',
    'mimes'    => 'The :attribute must be a file of type: :values.',
    'regex'    => 'The :attribute format is invalid.',

    // Custom attributes
    'attributes' => [
        'title'      => 'title',
        'chapter_id' => 'chapter',
        'course_id'  => 'course',
        'video'      => 'video',
        'content'    => 'content',
        'duration'   => 'duration',
        'status'     => 'status',
    ],
];
?>