<script>
    window.Journal = {
        // CSRF Token
        csrfToken : '{{ csrf_token() }}',
        // Current User ID
        userId : {!! auth_user() ? auth_user()->id : 'null' !!},
        // Settings
        settings : {}
    }
</script>
