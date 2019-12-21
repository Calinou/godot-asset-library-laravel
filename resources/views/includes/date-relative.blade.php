{{--
  Display the exact date as a tooltip when hovering a relative date.
--}}

@php
if (!isset($date)) {
  throw new \Exception('A Carbon date instance must be passed as "date"');
}
@endphp

<span
  {{-- Make the tooltip easier to target --}}
  class="has-tooltip p-1 -m-1"
  {{-- Force 24-hour format --}}
  aria-label="{{ $date->isoFormat('MMMM D, YYYY, HH:MM') }}"
  data-balloon-pos="up"
  data-balloon-blunt
>{{ $date->diffForHumans() }}</span>
