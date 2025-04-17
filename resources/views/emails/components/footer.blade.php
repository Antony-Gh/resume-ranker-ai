<div class="footer">
    &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    
    <div class="security-note">
        For security reasons, this email was sent to {{ $email }}.
        <br>
        If this wasn't you, please <a href="{{ config('app.url') }}/contact">contact us</a> immediately.
    </div>
</div>