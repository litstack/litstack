
<a class="lit-topbar_link"
    href="{{route('lit.logout')}}"
    onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();"> logout <i class="fas fa-sign-out-alt"></i>
</a>
<form id="logout-form"
        action="{{route('lit.logout')}}"
        method="POST"
        style="display: none;">
    @csrf
</form>