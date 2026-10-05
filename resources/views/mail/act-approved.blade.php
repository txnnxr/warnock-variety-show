<x-mail::message>
# You're in the lineup!

Hi {{ Str::before(trim($application->name), ' ') ?: $application->name }}, **{{ $application->title }}** is part of **{{ $application->show->name }}** on {{ $application->show->date->format('l, F j') }}.

@if($application->exhibitor)
You're act **{{ $application->show->lineup->search(fn ($act) => $act->is($application->exhibitor)) + 1 }} of {{ $application->show->lineup->count() }}** for now. The running order can still change before the show.
@endif

<x-mail::panel>
**Be in the room before {{ $application->show->date->format('g:ia') }}.** That lets us lock the lineup and start on time.<br>
**Where:** {{ $application->show->address }}
</x-mail::panel>

Exhibitions run 15 minutes max, not counting setup. If you need anything (mics, amps, a projector) and didn't list it in your application, reply to this email.

Can't make it after all? Let us know at least 48 hours before the show.

<x-mail::button :url="route('applications.status', $application)">
View my application
</x-mail::button>

See you on stage,<br>
Warnock Variety Show
</x-mail::message>
