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
