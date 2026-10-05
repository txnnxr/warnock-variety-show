<x-mail::message>
# Thanks for coming, {{ $invite->first_name }}!

**{{ $invite->show->name }}** wouldn't have been the same without you.

@if($lineup->isNotEmpty())
A round of applause for the night's performers:

<x-mail::table>
| Act | Performer |
|:----|:----------|
@foreach($lineup as $act)
| *{{ $act->exhibition_description }}* | {{ $act->person->name }} |
@endforeach
</x-mail::table>
@endif

@if($photos->isNotEmpty())
@foreach($photos as $photo)
[![{{ $photo->caption ?? $invite->show->name }}]({{ $photo->url }})]({{ route('shows.show', $invite->show) }})
@endforeach

<x-mail::button :url="route('shows.show', $invite->show)">
See all {{ $photoCount }} {{ Str::plural('photo', $photoCount) }}
</x-mail::button>
@endif

<x-mail::panel>
**What should we have more of next time?** Just reply to this email. Every answer gets read.
</x-mail::panel>

@if($nextShow)
**Next up:** {{ $nextShow->name }} on {{ $nextShow->date->format('l, F j') }}. [Save your seat]({{ route('shows.show', $nextShow) }}).
@endif

Warnock Variety Show
</x-mail::message>
