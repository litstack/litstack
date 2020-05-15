
<a class="fj-topbar_link"
    href="{{route('fjord.logout')}}"
    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"> logout <i class="fas fa-sign-out-alt"></i>
</a>
<form id="logout-form"
        action="{{route('fjord.logout')}}"
        method="POST"
        style="display: none;">
    @csrf
</form>