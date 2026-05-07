$(document).ready(function () {
    let isProgrammaticChange = false; // Flag

    function initializeChapters() {
        var courseId = $('#course_id').val();
        var selectedChapterId = $('#chapter_id').val();

        if (courseId) {
            $.ajax({
                url: '/admin/get-chapters/' + courseId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#chapter_id').empty();
                    $('#chapter_id').append('<option value="">-- Chose a chapter --</option>');

                    $.each(data, function (chap, value) {
                        var selected = (value.id == selectedChapterId) ? 'selected' : '';
                        $('#chapter_id').append('<option value="' + value.id + '" ' + selected + '>' + value.title + '</option>');
                    });
                }
            });
        }
    }

    if ($('#course_id').val()) {
        initializeChapters();
    }
    $('#course_id').on('change', function () {
        if (isProgrammaticChange) return;

        var courseId = $(this).val();
        $('#chapter_id').html('<option>LOADING...</option>');

        if (courseId) {
            $.ajax({
                url: '/admin/get-chapters/' + courseId,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#chapter_id').empty();
                    $('#chapter_id').append('<option value="">-- Chose a chapter --</option>');
                    $.each(data, function (chap, value) {
                        $('#chapter_id').append('<option value="' + value.id + '">' + value.title + '</option>');
                    });
                }
            });
        } else {
            $.ajax({
                url: '/admin/get-all-chapters',
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#chapter_id').empty();
                    $.each(data, function (chap, value) {
                        $('#chapter_id').append('<option value="' + value.id + '">' + value.title + '</option>');
                    });
                }
            });
        }
    });

    // When chapter is selected, auto-set the course
    $('#chapter_id').on('change', function () {
        let chapterId = $(this).val();
        if (chapterId) {
            $.ajax({
                url: '/admin/get-course-by-chapter/' + chapterId,
                type: 'GET',
                success: function (data) {
                    isProgrammaticChange = true; // Enable flag: preparing to programmatically change Course
                    $('#course_id').val(data.course_id).trigger('change');
                    if (window.touchedFields && window.validateLessonField) {
                        window.touchedFields.add('course_id');
                        window.validateLessonField({ id: 'course_id', name: 'Course' });
                    }
                    isProgrammaticChange = false; // After trigger, disable flag
                },
                error: function () {
                    alert("Can't Get Course Informations.");
                }
            });
        }
    });
});