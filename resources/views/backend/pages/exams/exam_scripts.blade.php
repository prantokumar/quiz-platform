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
