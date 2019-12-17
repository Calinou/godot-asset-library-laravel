@php
// If `$prototype` is `true`, the array index will be replaced with
// a placeholder value that must be replaced with JavaScript (see `$index`).
$prototype = $prototype ?? false;
$index = $prototype ? '__index__' : $loop->index;
@endphp

<div class="relative my-4 p-4 pb-2 bg-white dark:bg-gray-800 rounded shadow">
  {{-- Only prototypes can be removed, as published previews cannot be removed yet --}}
  @if ($prototype)
  <button type="button" class="absolute top-0 right-0 mt-2 mr-2 opacity-50 hover:opacity-75" data-delete-preview>
    <span class="fa fa-times fa-fw"></span>
  </button>
  @endif

  @if (!$prototype)
  {{--
    Used to associate the previews on the backend to update them correctly
    (instead of removing all of them and recreating them)
  --}}
  <input type="hidden" name="previews[{{ $index }}][id]" value="{{ $asset->previews[$index]->preview_id }}">
  @endif

  {{-- TODO: Allow adding video previews --}}
  <input type="hidden" name="previews[{{ $index }}][type_id]" value="0">

  @component('components/form-input', [
    'type' => 'url',
    'name' => "previews[$index][link]",
    'value' => $prototype ? null : $asset->previews[$index]->link,
    'label' => __('Preview URL'),
    'required' => true,
    'maxlength' => 2000,
    'autocomplete' => 'off',
  ])
  {{ __('For images, a 16:9 aspect ratio is recommended.') }}
  {{ __('Only PNG or JPEG images are allowed.') }}
  @endcomponent

  @component('components/form-input', [
    'type' => 'url',
    'name' => "previews[$index][thumbnail]",
    'value' => $prototype ? null : $asset->previews[$index]->thumbnail,
    'label' => __('Thumbnail URL'),
    'maxlength' => 2000,
    'autocomplete' => 'off',
  ])
  {{ __('If present, the thumbnail should have the same aspect ratio as the preview.') }}
  {{ __('Only PNG or JPEG images are allowed.') }}
  @endcomponent

  @component('components/form-input', [
    'name' => "previews[$index][caption]",
    'value' => $prototype ? null : $asset->previews[$index]->caption,
    'label' => __('Caption'),
    'placeholder' => 'A short sentence to accompany the image',
    'maxlength' => 60,
    'autocomplete' => 'off',
  ])
  @endcomponent
</div>
