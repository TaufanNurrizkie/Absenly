guru
<x-dropdown-link :href="route('logout')"
        onclick="event.preventDefault();
                    this.closest('form').submit();">
    {{ __('Log Out') }}
</x-dropdown-link>