{{-- show exams --}}
<script>
    function showExams() {
        $('.loading_image').css('display', 'block');
        $.ajax({
            url: '{{ route('showExams') }}'
            , method: 'post'
            , data: {
                "_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
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
        let exam_name = $('.exam_name').val();
        let instruction = $('.instruction').val();
        let exam_duration = $('.exam_duration').val();
        let no_of_attempts = $('.no_of_attempts').val();
        let exam_due_date = $('.exam_due_date').val();
        let exam_start_date = $('.exam_start_date').val();
        let exam_end_date = $('.exam_end_date').val();
        let result_display = $("input[name='result_display']:checked").val();
        let is_published = $("input[name='is_published']:checked").val();
        let exam_input_id = $('.exam_input_id').val();
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
            type: 'POST'
            , url: '{{ route('saveExam') }}'
            , data: {
                'exam_input_id': exam_input_id
                , 'exam_name': exam_name
                , 'instruction': instruction
                , 'exam_duration': exam_duration
                , 'no_of_attempts': no_of_attempts
                , 'exam_due_date': exam_due_date
                , 'exam_start_date': exam_start_date
                , 'exam_end_date': exam_end_date
                , 'result_display': result_display
                , 'is_published': is_published
                , "_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
                if (data.success) {
                    showExams();
                    toastr.success(data.message);
                    $(".resetAddExamModal").trigger("click");
                } else {
                    toastr.error("Something went wrong! Please try again later.");
                }
            }
        , });
    });

</script>
{{-- save exam --}}


{{-- update exam data --}}
<script>
    function editExam(examID) {
        $.ajax({
            type: 'POST'
            , url: '{{ route('getExamDataWithExamId') }}'
            , data: {
                'exam_id': examID
                , "_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
                if (data.success) {
                    $('#instruction').val(data.exam.instruction);
                    $('#exam_due_date').val(data.exam.exam_due_date);
                    $('#exam_start_date').val(data.exam.exam_start_date);
                    $('#exam_end_date').val(data.exam.exam_end_date);
                    $('#exam_name').val(data.exam.exam_name);
                    $('#exam_input_edit_id').val(data.exam.id);
                    $('#exam_duration').val(data.exam.exam_duration);
                    $('#no_of_attempts').val(data.exam.no_of_attempts);
                    $('input:radio[name=result_display_edit]').filter('[value=' + data.exam.result_display + ']')
                        .prop('checked', true);
                    $('input:radio[name=is_published_edit]').filter('[value=' + data.exam.is_published + ']')
                        .prop('checked', true);
                }
            }
        , });
    }
    /* update assignment */
    $('#edit_exam_button').on('click', function(e) {
        e.preventDefault();
        let exam_name = $('#exam_name').val();
        let instruction = $('#instruction').val();
        let exam_duration = $('#exam_duration').val();
        let no_of_attempts = $('#no_of_attempts').val();
        let exam_due_date = $('#exam_due_date').val();
        let exam_start_date = $('#exam_start_date').val();
        let exam_end_date = $('#exam_end_date').val();
        let result_display = $("input[name='result_display_edit']:checked").val();
        let is_published = $("input[name='is_published_edit']:checked").val();
        let exam_input_edit_id = $('#exam_input_edit_id').val();

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
            type: 'POST'
            , url: '{{ route('updateExam') }}'
            , data: {
                'exam_input_edit_id': exam_input_edit_id
                , 'exam_name': exam_name
                , 'instruction': instruction
                , 'exam_duration': exam_duration
                , 'no_of_attempts': no_of_attempts
                , 'exam_due_date': exam_due_date
                , 'exam_start_date': exam_start_date
                , 'exam_end_date': exam_end_date
                , 'result_display': result_display
                , 'is_published': is_published
                , "_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
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
            type: 'POST'
            , url: '{{ route('getExamDataWithExamId') }}'
            , data: {
                'exam_id': examID
                , "_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
                if (data.success) {
                    $('#delete_exam_id').val(data.exam.id);
                }
            }
        , });
    }
    $('#delete_exam_button').on('click', function(e) {
        e.preventDefault();
        let delete_exam_id = $('#delete_exam_id').val();
        $.ajax({
            type: 'POST'
            , url: '{{ route('deleteExam') }}'
            , data: {
                'delete_exam_id': delete_exam_id
                , "_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
                if (data.success) {
                    showExams();
                    $('#closeDeleteExamModal').trigger("click");
                    toastr.success(data.message);
                } else {
                    toastr.error("Something went wrong! Please try again later.");
                }
            }
        , });
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
    function addQuestions(examID) {
        $.ajax({
            type: 'POST'
            , url: '{{ route('getExamDataWithExamId') }}'
            , data: {
                'exam_id': examID
                , "_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
                if (data.success) {
                    $('#exam_id').val(data.exam.id);
                }
            }
        , });
    }
    $("#save_question_button").click(function(e) {
        e.preventDefault();
        let exam_id = $("#exam_id").val();
        let title = $("#title").val();
        let marks = $("#marks").val();
        let description = $("#description").val();
        let isCorrectChecked = $("input[name='is_correct[]']:checked").length > 0;
        let myAnswers = [];
        let check_existing_correct_answer = false;
        let allAnswersFilled = true;

        $('input[name="is_correct[]"]').each(function(index) {
            let isCorrect = $(this).is(':checked') ? 1 : 0;
            let answerField = $("input[name='answer[]']").eq(index);
            let answerValue = answerField.val().trim();

            if (isCorrect === 1) {
                check_existing_correct_answer = true;
            }

            if (answerValue === "") {
                allAnswersFilled = false;
            }

            myAnswers.push({
                ans_title: answerValue,
                ans_is_correct: isCorrect,
            });
        });

        if (title === "") {
            toastr.error('Please enter a question title');
        }
        else if (marks === "") {
            toastr.error('Please enter marks for the question');
        }
        else if (!allAnswersFilled) {
            toastr.error('Please fill in all answer fields');
        }
        else if (!check_existing_correct_answer) {
            toastr.error('Please select one of the options as correct');
        }
        else {
            toastr.success('Question added successfully');
        }

        if (title !== "" && marks !== "" && check_existing_correct_answer && allAnswersFilled) {
            $.ajax({
                type: 'POST'
                , url: '{{ route('addQuestionToExam') }}'
                , data: {
                    'exam_id': exam_id
                    , 'title': title
                    , 'marks': marks
                    , 'description': description
                    , 'myAnswers': myAnswers
                    , "_token": "{{ csrf_token() }}"
                , }
                , success: function(data) {
                    if (data.success) {
                        showExams();
                        toastr.success('Question added successfully');
                        $("#closeAddExamModal").trigger("click");
                    } else {
                        toastr.error("Something went wrong! Please try again later.");
                    }
                },
        });

        }
    });

</script>
{{-- add/edit question modal open  --}}

{{-- reset add question area --}}
<script>
    $('#closeAddExamModal').on('click', function() {
        $('#title').val("");
        $('#marks').val("");
        $('#description').val("");
        $('input[name="is_correct[]"]').prop('checked', false);
        $('input[name="answer[]"]').val("");
    });
</script>
{{-- reset add  question area --}}


{{-- view exam questions --}}
<script>
    function viewQuestions(examID) {
        $.ajax({
            type: 'POST'
            , url: '{{ route('viewExamQuestions') }}'
            , data: {
                'exam_id': examID
                , "_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
                $('#view_question_exam_id').val(data.exam_id);
                $('#view_questions').html(data.view_exam_questions);
            }
        , });
    }
</script>
{{-- view exam questions --}}

{{-- update question area --}}
<script>
    function updateQuestions(examID, questionID) {
        $.ajax({
            type: 'POST'
            , url: '{{ route('updateExamQuestionModalShow') }}'
            , data: {
                'exam_id': examID
                ,'question_id': questionID
                ,"_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
                $('.view_question_exam_id_for_edit').val(data.exam_id);
                $('.view_question_id_for_edit').val(data.question_id);
                $('#view_questions_for_update').html(data.view_question_for_update);
            }
        , });
    }
    $("#update_question_button").click(function(e) {
        e.preventDefault();
        let exam_id = $(".view_question_exam_id_for_edit").val();
        let question_id = $(".view_question_id_for_edit").val();
        let title = $(".title").val();
        let marks = $(".mark").val();
        let description = $(".description").val();
        let isCorrectChecked = $("input[name='is_correct_update[]']:checked").length > 0;
        let myAnswers = [];
        let check_existing_correct_answer = false;
        let allAnswersFilled = true;

        $('input[name="is_correct_update[]"]').each(function(index) {
            let isCorrect = $(this).is(':checked') ? 1 : 0;
            let answerField = $("input[name='answer_update[]']").eq(index);
            let answerValue = answerField.val().trim();

            if (isCorrect === 1) {
                check_existing_correct_answer = true;
            }

            if (answerValue === "") {
                allAnswersFilled = false;
            }

            myAnswers.push({
                ans_title: answerValue,
                ans_is_correct: isCorrect,
            });
        });

        if (title === "") {
            toastr.error('Please enter a question title');
        }
        else if (marks === "") {
            toastr.error('Please enter marks for the question');
        }
        else if (!allAnswersFilled) {
            toastr.error('Please fill in all answer fields');
        }
        else if (!check_existing_correct_answer) {
            toastr.error('Please select one of the options as correct');
        }
        else {
        }

        if (title !== "" && marks !== "" && check_existing_correct_answer && allAnswersFilled) {
            $.ajax({
                type: 'POST'
                , url: '{{ route('updateExamQuestion') }}'
                , data: {
                    'exam_id': exam_id
                    , 'question_id': question_id
                    , 'title': title
                    , 'marks': marks
                    , 'description': description
                    , 'myAnswers': myAnswers
                    , "_token": "{{ csrf_token() }}"
                , }
                , success: function(data) {
                    if (data.success) {
                        showExams();
                        toastr.success('Question updated successfully');
                        $("#closeUpdateQuestionModal").trigger("click");
                        viewQuestions(data.exam_id);
                    } else {
                        toastr.error("Something went wrong! Please try again later.");
                    }
                },
            });
        }
    });
</script>
{{-- update question area --}}

{{-- delete question area --}}
<script>
    function deleteQuestions(examID, questionID) {
        $('#delete_question_id').val(questionID);
        $('#get_exam_id').val(examID);
    }
    $('#delete_question_button').on('click', function(e) {
        e.preventDefault();
        let delete_question_id = $('#delete_question_id').val();
        let get_exam_id = $('#get_exam_id').val();
        $.ajax({
            type: 'POST'
            , url: '{{ route('deleteExamQuestion') }}'
            , data: {
                'delete_question_id': delete_question_id
                ,'exam_id': get_exam_id
                , "_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
                if (data.success) {
                    showExams();
                    $('#closeDeleteQuestionModal').trigger("click");
                    toastr.success(data.message);
                    viewQuestions(data.exam_id);
                } else {
                    toastr.error("Something went wrong! Please try again later.");
                }
            }
        , });
    });
</script>
{{-- delete question area --}}

{{-- question area --}}
