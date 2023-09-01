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

{{-- view exam questions for users--}}
<script>
    function viewExamQuestionsForUser(examID) {
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


        var question_answer = []; // Initialize an empty array named question_answer

        // Iterate through each question group
        $('.form-group').each(function(index, element) {
            var question_id = $(element).find('.question_id').val();
            var answers = [];

            // Iterate through each checked answer in this question group
            $(element).find('input[name^="answer_id_"]:checked').each(function() {
                var answer_id = $(this).val();
                answers.push(answer_id);
            });

            // If there's only one answer, extract it as a single value
            if (question_id && answers.length > 0) {
                var answer_ids = (answers.length === 1) ? answers[0] : answers;
                question_answer.push({
                    'question_id': question_id,
                    'answer_ids': answer_ids
                });
            }
        });

        // Now, question_answer contains the desired array of objects
        console.log(question_answer);
    });
</script>
{{-- submit exam --}}
