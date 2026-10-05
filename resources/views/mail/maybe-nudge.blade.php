<x-mail::message>
# Hi {{ $invite->first_name }},

You said "maybe" to **{{ $invite->show->name }}** on {{ $invite->show->date->format('l, F j') }}. It's coming up soon. Can you commit?

<x-mail::button :url="$invite->link . '?change=1'">
Update my RSVP
</x-mail::button>

Warnock Variety Show
</x-mail::message>
