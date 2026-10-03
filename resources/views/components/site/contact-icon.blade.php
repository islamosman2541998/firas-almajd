@props(['name'])
<span aria-hidden="true" class="cd-icon"><svg focusable="false" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
@switch($name)
@case('home')<path class="f" d="M12 3.2 2.6 11.4c-.4.35-.15 1 .37 1H5V20a1 1 0 0 0 1 1h4v-5.5h4V21h4a1 1 0 0 0 1-1v-7.6h2.03c.52 0 .77-.65.37-1z"/>@break
@case('pin')<path class="f" d="M12 2a7 7 0 0 0-7 7c0 5.1 6.1 12.2 6.36 12.5a.85.85 0 0 0 1.28 0C12.9 21.2 19 14.1 19 9a7 7 0 0 0-7-7Zm0 9.75A2.75 2.75 0 1 1 12 6.25a2.75 2.75 0 0 1 0 5.5Z"/>@break
@case('at')<circle class="s" cx="12" cy="12" r="3.8"/><path class="s" d="M15.8 8.2v5.1a2.6 2.6 0 0 0 5.2 0V12a9 9 0 1 0-3.6 7.2"/>@break
@case('globe')<circle class="s" cx="12" cy="12" r="9"/><path class="s" d="M3 12h18M12 3c2.5 2.6 3.8 5.6 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.6-3.8-9S9.5 5.6 12 3ZM4.6 7.5h14.8M4.6 16.5h14.8"/>@break
@case('phone')<path class="f" d="M6.62 10.79a15.05 15.05 0 0 0 6.59 6.59l2.2-2.2a1 1 0 0 1 1.02-.24c1.12.37 2.33.57 3.57.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1.02z"/>@break
@default<rect class="s" height="14" rx="1.2" width="18" x="3" y="5"/><path class="s" d="m3.5 6 8.5 7 8.5-7M3.5 18.5l6.4-6.2M20.5 18.5l-6.4-6.2"/>
@endswitch
</svg></span>
