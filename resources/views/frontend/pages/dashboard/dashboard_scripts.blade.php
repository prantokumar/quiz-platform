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
            $('#start_quiz_button').off('click');
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
                var totalSeconds = data.exam_duration * 60;
                startCountdown(totalSeconds);
            }
        , });
    }
    let countdown;
    function startCountdown(totalSeconds) {
        var endTime = new Date().getTime() + totalSeconds * 1000;
        countdown = setInterval(function () {
            var currentTime = new Date().getTime();
            var remainingTime = endTime - currentTime;
            if (remainingTime <= 0) {
                clearInterval(countdown);
                $('#timer').text("0m 0s");
                $('#submit_exam_button').click();
                $('#closeViewQuestionsModal').click();
            } else {
                var minutes = Math.floor(remainingTime / (1000 * 60));
                var seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);
                $('#timer').text(minutes + "m " + seconds + "s");
            }
        }, 1000);
    }
    function resetTimer() {
        clearInterval(countdown);
    }
    function handleModalClose() {
        resetTimer();
    }
</script>
{{-- view exam questions for users--}}

{{-- submit exam --}}
<script>
    $('#view_questions_area_modal_for_user').on('hidden.bs.modal', function () {
        handleModalClose();
    });
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

        //console.log(question_answers);

        $.ajax({
            type: 'POST'
            , url: '{{ route('submitQuiz') }}'
            , data: {
                'exam_id': exam_id
                , "question_answers": question_answers
                , "_token": "{{ csrf_token() }}"
            , }
            , success: function(data) {
                if(data.error == 'no_question_answer')
                {
                    toastr.error('Oops! you submit the exam without answer any single questions.');
                    $("#closeViewQuestionsModal").trigger("click");
                    resetTimer();
                }else{
                    $("#closeViewQuestionsModal").trigger("click");
                    resetTimer();
                    showExams();
                    toastr.success('Quiz submitted successfully');
                }
            }
        , });
    });
</script>
{{-- submit exam --}}
