<x-mail::message>
# Hi {{ $invite->first_name }}!

You're invited to **{{ $invite->show->name }}**, the next Warnock Variety Show, on {{ $invite->show->date->format('l, F j, Y \a\t g:ia') }}.

Let us know if you can make it. The address is shared once you RSVP.

<x-mail::button :url="$invite->link">
RSVP
</x-mail::button>

See you there,<br>
Warnock Variety Show
</x-mail::message>
