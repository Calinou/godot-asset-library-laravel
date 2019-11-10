{{--
  Display the exact date as a tooltip when hovering a relative date.
--}}

@php
if (!isset($date)) {
  throw new \Exception('A Carbon date instance must be passed as "date"');
}
@endphp

<abbr title="{{ $date }}">
  {{ $date->diffForHumans() }}
</abbr>
