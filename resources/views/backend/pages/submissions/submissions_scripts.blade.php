<script>
    $(document).ready(function () {
        $(document).on("click", ".view_submission_button", function () {
            let examname = $(this).data('examname');
            let username = $(this).data('username');
            let userid = $(this).data('userid');
            let exam_id = $('.exam_id').val();
            //console.log(assignmentname, studentname, studentid);
            $('.dynamic_modal_loading_image').css('display', 'block');
            $('.dynamic_modal_title').html(examname + ' - ' + username);
            $('#submission_detail_dynamic_data').html('');
            $.ajax({
                type: 'POST',
                url: '{{ route('generateSubmissionDetails') }}',
                data: {
                    'user_id': userid,
                    'exam_id': exam_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('.dynamic_modal_loading_image').css('display', 'none');
                    $('#submission_detail_dynamic_data').html(data.data_generate);
                    $('.total_obtained_mark').html('Total Obtained Marks : ' + data.totalObtainedMarks);
                },
            });
        });
    });
</script>
