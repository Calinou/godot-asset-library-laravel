@php
// If `$prototype` is `true`, the array index will be replaced with
// a placeholder value that must be replaced with JavaScript (see `$index`).
$prototype = $prototype ?? false;
$index = $prototype ? '__index__' : $loop->index;
@endphp

<div class="relative my-4 p-4 pb-2 bg-white rounded shadow">
  {{-- Only prototypes can be removed, as published versions cannot be removed --}}
  @if ($prototype)
  <button type="button" class="absolute top-0 right-0 mt-2 mr-2 opacity-50 hover:opacity-75" data-delete-version>
    <span class="fa fa-times fa-fw"></span>
  </button>
  @endif

  @if (!$prototype)
  {{--
    Used to associate the versions on the backend to update them correctly
    (instead of removing all of them and recreating them)
  --}}
  <input type="hidden" name="versions[{{ $index }}][id]" value="{{ $asset->versions[$index]->id }}">
  @endif

  <div class="sm:flex sm:justify-between">
    @component('components/form-input', [
      'name' => "versions[$index][version_string]",
      'value' => $prototype ? null : $asset->versions[$index]->version_string,
      'label' => __('Asset version'),
      'placeholder' => '1.0.0',
      'required' => true,
      'autocomplete' => 'off',
    ])
    @endcomponent

    @component('components/form-select', [
      'name' => "versions[$index][godot_version]",
      'value' => $prototype ? null : $asset->versions[$index]->godot_version,
      'label' => __('Godot version'),
      'placeholder' => __('Select a Godot version'),
      'required' => true,
      'choices' => [
        '3.2' => 'Godot 3.2',
        '3.1' => 'Godot 3.1',
        '3.0' => 'Godot 3.0',
      ],
    ])
    @endcomponent
  </div>

  @component('components/form-input', [
    'type' => 'url',
    'name' => "versions[$index][download_url]",
    'value' => $prototype ? null : $asset->versions[$index]->download_url,
    'label' => __('Download URL'),
    'placeholder' => 'https://github.com/user/asset/archive/v1.0.0.zip',
    'maxlength' => 2000,
    'autocomplete' => 'off',
  ])
  @endcomponent
</div>
