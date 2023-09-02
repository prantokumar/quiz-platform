{{-- show exams --}}
<script>
    function showExams() {
        $('.loading_image').css('display', 'block');
        $.ajax({
            url: '{{ route('showExamsForUser') }}'
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

{{-- quiz start confirmation --}}
<script>
    function showQuizStartConfirmation(examID) {
        $('#start_quiz_button').on('click', function(e) {
            e.preventDefault();
            $("#closeQuizStartConfirmationModal").trigger("click");
            viewExamQuestionsForUser(examID);
        });
    }
</script>
{{-- quiz start confirmation --}}

{{-- view exam questions for users--}}
<script>
    function viewExamQuestionsForUser(examID) {
        $('#view_questions_area_modal_for_user').modal('show');
        $.ajax({
            type: 'POST'
            , url: '{{ route('viewExamQuestionsForUser') }}'
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
{{-- view exam questions for users--}}

{{-- submit exam --}}
<script>
    $('#submit_exam_button').on('click', function(e) {
        e.preventDefault();

        var question_answers = [];

        let exam_id = $('.exam_id').val();

        $('.form-group').each(function(index, element) {
            var question_id = $(element).find('.question_id').val();
            var answers = [];

            $(element).find('input[name^="answer_id_"]:checked').each(function() {
                var answer_id = $(this).val();
                answers.push(answer_id);
            });

            if (question_id && answers.length > 0) {
                var answer_id = (answers.length === 1) ? answers[0] : answers;
                question_answers.push({
                    'question_id': question_id,
                    'answer_id': answer_id
                });
            }
        });

        console.log(question_answers);

        $.ajax({
            type: 'POST'
            , url: '{{ route('submitQuiz') }}'
            , data: {
                'exam_id': exam_id
                , "question_answers": question_answers
                , "_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
                $("#closeViewQuestionsModal").trigger("click");
                showExams();
                toastr.success('Quiz submitted successfully');
            }
        , });


    });
</script>
{{-- submit exam --}}
