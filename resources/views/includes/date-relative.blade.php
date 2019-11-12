{{--
  Display the exact date as a tooltip when hovering a relative date.
--}}

@php
if (!isset($date)) {
  throw new \Exception('A Carbon date instance must be passed as "date"');
}
@endphp

{{-- Force 24-hour format --}}
<abbr title="{{ $date->isoFormat('MMMM D, YYYY, HH:MM') }}">
  {{ $date->diffForHumans() }}
</abbr>
