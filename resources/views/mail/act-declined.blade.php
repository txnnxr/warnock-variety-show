<x-mail::message>
# Thanks for applying

Hi {{ Str::before(trim($application->name), ' ') ?: $application->name }}, thank you for offering **{{ $application->title }}** for **{{ $application->show->name }}**. We can't fit it into this show's lineup.

That's usually about variety and timing, not the act. Please apply again for a future show.

<x-mail::button :url="route('home')">
See upcoming shows
</x-mail::button>

Warnock Variety Show
</x-mail::message>
