{{-- show exams --}}
<script>
    function showExams() {
        $('.loading_image').css('display', 'block');
        $.ajax({
            url: '{{ route('showExams') }}',
            method: 'post',
            data: {
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                $("#show-exams").html(data.data_generate_for_exams);
                $('.loading_image').css('display', 'none');
            }
        });
    }
    $(document).ready(function() {
        showExams();
    });
</script>
{{-- show exams --}}

{{-- save exam --}}
<script>
    $('#save_exam_button').on('click', function(e) {
        e.preventDefault();
        var exam_name = $('.exam_name').val();
        var instruction = $('.instruction').val();
        var exam_duration = $('.exam_duration').val();
        var no_of_attempts = $('.no_of_attempts').val();
        var exam_due_date = $('.exam_due_date').val();
        var exam_start_date = $('.exam_start_date').val();
        var exam_end_date = $('.exam_end_date').val();
        var result_display = $("input[name='result_display']:checked").val();
        var is_published = $("input[name='is_published']:checked").val();
        var exam_input_id = $('.exam_input_id').val();
        if (exam_name == "") {
            toastr.error("Exam Name Missing!");
            return false;
        } else if (exam_duration == "") {
            toastr.error("Exam Duration Missing!");
            return false;
        } else if (no_of_attempts == "") {
            toastr.error("No Of Attempts Missing!");
            return false;
        } else if (no_of_attempts == "") {
            toastr.error("No Of Attempts Missing!");
            return false;
        } else if (exam_due_date == "") {
            toastr.error("Due Date Missing!");
            return false;
        } else if (result_display == "") {
            toastr.error("Result Display Mode Missing!");
            return false;
        } else {

        }

        $.ajax({
            type: 'POST',
            url: '{{ route('saveExam') }}',
            data: {
                'exam_input_id': exam_input_id,
                'exam_name': exam_name,
                'instruction': instruction,
                'exam_duration': exam_duration,
                'no_of_attempts': no_of_attempts,
                'exam_due_date': exam_due_date,
                'exam_start_date': exam_start_date,
                'exam_end_date': exam_end_date,
                'result_display': result_display,
                'is_published': is_published,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                if (data.success) {
                    showExams();
                    toastr.success(data.message);
                    $(".resetAddExamModal").trigger("click");
                } else {
                    toastr.error("Something went wrong! Please try again later.");
                }
            },
        });
    });
</script>
{{-- save exam --}}


{{-- update exam data --}}
<script>
    function editExam(examID) {
        $.ajax({
            type: 'POST',
            url: '{{ route('getExamDataWithExamId') }}',
            data: {
                'exam_id': examID,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                if (data.success) {
                    $('#instruction').val(data.exam.instruction);
                    $('#exam_due_date').val(data.exam.exam_due_date);
                    $('#exam_start_date').val(data.exam.exam_start_date);
                    $('#exam_end_date').val(data.exam.exam_end_date);
                    $('#exam_name').val(data.exam.exam_name);
                    $('#exam_input_edit_id').val(data.exam.id);
                    $('#exam_duration').val(data.exam.exam_duration);
                    $('#no_of_attempts').val(data.exam.no_of_attempts);
                    $('input:radio[name=result_display]').filter('[value=' + data.exam.result_display + ']')
                        .prop('checked', true);
                    $('input:radio[name=is_published]').filter('[value=' + data.exam.is_published + ']')
                        .prop('checked', true);
                }
            },
        });
    }
    /* update assignment */
    $('#edit_exam_button').on('click', function(e) {
        e.preventDefault();
        var exam_name = $('#exam_name').val();
        var instruction = $('#instruction').val();
        var exam_duration = $('#exam_duration').val();
        var no_of_attempts = $('#no_of_attempts').val();
        var exam_due_date = $('#exam_due_date').val();
        var exam_start_date = $('#exam_start_date').val();
        var exam_end_date = $('#exam_end_date').val();
        var result_display = $("input[name='result_display_edit']:checked").val();
        var is_published = $("input[name='is_published_edit']:checked").val();
        var exam_input_edit_id = $('#exam_input_edit_id').val();

        if (exam_name == "") {
            toastr.error("Exam Name Missing!");
            return false;
        } else if (exam_duration == "") {
            toastr.error("Exam Duration Missing!");
            return false;
        } else if (no_of_attempts == "") {
            toastr.error("No Of Attempts Missing!");
            return false;
        } else if (no_of_attempts == "") {
            toastr.error("No Of Attempts Missing!");
            return false;
        } else if (exam_due_date == "") {
            toastr.error("Due Date Missing!");
            return false;
        } else if (result_display == "") {
            toastr.error("Result Display Mode Missing!");
            return false;
        } else {

        }

        $.ajax({
            type: 'POST',
            url: '{{ route('updateExam') }}',
            data: {
                'exam_input_edit_id': exam_input_edit_id,
                'exam_name': exam_name,
                'instruction': instruction,
                'exam_duration': exam_duration,
                'no_of_attempts': no_of_attempts,
                'exam_due_date': exam_due_date,
                'exam_start_date': exam_start_date,
                'exam_end_date': exam_end_date,
                'result_display': result_display,
                'is_published': is_published,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                if (data.success) {
                    showExams();
                    toastr.success(data.message);
                    $(".resetEditExamModal").trigger("click");
                } else {
                    toastr.error("Something went wrong! Please try again later.");
                }
            },
        });
    });
    /* update assignment */
</script>
{{-- update exam data --}}

{{-- delete exam --}}
<script>
    function deleteExam(examID) {
        $.ajax({
            type: 'POST',
            url: '{{ route('getExamDataWithExamId') }}',
            data: {
                'exam_id': examID,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                if (data.success) {
                    $('#delete_exam_id').val(data.exam.id);
                }
            },
        });
    }
    $('#delete_exam_button').on('click', function(e) {
        e.preventDefault();
        var delete_exam_id = $('#delete_exam_id').val();
        $.ajax({
            type: 'POST',
            url: '{{ route('deleteExam') }}',
            data: {
                'delete_exam_id': delete_exam_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                if (data.success) {
                    showExams();
                    $('#closeDeleteExamModal').trigger("click");
                    toastr.success(data.message);
                } else {
                    toastr.error("Something went wrong! Please try again later.");
                }
            },
        });
    });
</script>
{{-- delete exam --}}

{{-- reset add exam modal --}}
<script>
    $('.resetAddExamModal').on('click', function() {
        $('.exam_name').val("");
        $('.instruction').val("");
        $('.exam_duration').val("");
        $('.no_of_attempts').val("");
        $('.exam_due_date').val("");
        $('.exam_start_date').val("");
        $('.exam_end_date').val("");
    });
</script>
{{-- reset add exam modal --}}

{{-- reset edit exam modal --}}
<script>
    $('.resetEditExamModal').on('click', function() {
        $('.exam_name').val("");
        $('.instruction').val("");
        $('.exam_duration').val("");
        $('.no_of_attempts').val("");
        $('.exam_due_date').val("");
        $('.exam_start_date').val("");
        $('.exam_end_date').val("");
    });
</script>
{{-- reset edit exam modal --}}

{{-- question area --}}
{{-- add/edit question modal open  --}}
<script>
    function addOrEditQuestions(examID) {
        $.ajax({
            type: 'POST',
            url: '{{ route('getExamDataWithExamId') }}',
            data: {
                'exam_id': examID,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                if (data.success) {
                    $('#exam_id').val(data.exam.id);
                }
            },
        });
    }
</script>
{{-- add/edit question modal open  --}}

{{-- reset question area --}}
{{-- reset question area --}}

{{-- question area --}}
