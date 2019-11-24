@extends('layouts.app')
@inject('assetClass', 'App\Asset')

@if ($editing)
@section('title', __('Edit “:asset”', ['asset' => $asset->title]))
@else
@section('title', __('Submit an asset'))
@endif

@section('content')
<div class="container">
    <form method="POST" action="{{ $editing ? route('asset.update', ['asset' => $asset]) : route('asset.store') }}">
      @csrf

      @if ($editing)
      @method('PUT')
      @endif

      <div class="text-center text-xl font-medium">
        @if ($editing)
        {{ __('Edit “:asset”', ['asset' => $asset->title]) }}
        @else
        {{ __('Submit an asset to :appName', ['appName' => config('app.name')]) }}
        @endif
      </div>

      <section class="w-full max-w-md mx-auto mt-8">
        @component('components/form-input', [
          'name' => 'title',
          'value' => $editing ? $asset->title : null,
          'label' => __('Asset name'),
          'placeholder' => __('My Own Asset'),
          'required' => true,
          'autofocus' => true,
          'maxlength' => 50,
          'autocomplete' => 'off',
        ])
        @endcomponent

        @component('components/form-input', [
          'name' => 'blurb',
          'value' => $editing ? $asset->blurb : null,
          'label' => __('Blurb'),
          'placeholder' => __('One-line description of the asset'),
          'maxlength' => 60,
          'autocomplete' => 'off',
        ])
        @endcomponent


        @component('components/form-input', [
          'type' => 'textarea',
          'value' => $editing ? $asset->description : null,
          'name' => 'description',
          'label' => __('Description'),
          'placeholder' => __('Full description that spans multiple lines…'),
          'required' => true,
          'autocomplete' => 'off',
          'class' => 'h-64',
        ])
        {{ __('Supports') }}
        <a
          class="link"
          href="https://guides.github.com/features/mastering-markdown/"
          target="_blank"
          rel="nofollow noopener noreferrer"
        >GitHub Flavored Markdown</a>.
        @endcomponent

        @component('components/form-input', [
          'name' => 'tags',
          'value' => $editing ? $asset->getOriginal('tags') : null,
          'label' => __('Tags'),
          'placeholder' => 'platformer, 2d, pixel-art, gdnative',
          'autocomplete' => 'off',
        ])
        {{ __('A comma-separated list of tags (up to :maxTags). Only lowercase characters, numbers and dashes are allowed in tag names.',
            ['maxTags' => $assetClass::MAX_TAGS]) }}
        @endcomponent

        <div class="sm:flex sm:justify-between">
          @php
          $categories = [];
          foreach (range(0, $assetClass::CATEGORY_MAX - 1) as $categoryId) {
            $categories[] = $assetClass::getCategoryName($categoryId);
          }
          @endphp

          @component('components/form-select', [
            'name' => 'category_id',
            'value' => $editing ? $asset->category_id : null,
            'label' => __('Category'),
            'placeholder' => __('Select a category'),
            'required' => true,
            'choices' =>  $categories,
          ])
          @endcomponent

          @component('components/form-select', [
            'name' => 'cost',
            'value' => $editing ? $asset->license : null,
            'label' => __('License'),
            'placeholder' => __('Select a license'),
            'required' => true,
            'choices' =>  $assetClass::LICENSES,
          ])
          @endcomponent
        </div>

        @if (!$editing)
        <div class="sm:flex sm:justify-between">
          @component('components/form-input', [
            'name' => 'versions[0][version_string]',
            'value' => old('versions.0.version_string'),
            'label' => __('Asset version'),
            'placeholder' => '1.0.0',
            'required' => true,
            'autocomplete' => 'off',
          ])
          @endcomponent

          @component('components/form-select', [
            'name' => 'versions[0][godot_version]',
            'value' => old('versions.0.godot_version'),
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
        @endif

        @component('components/form-input', [
          'type' => 'url',
          'name' => 'browse_url',
          'value' => $editing ? $asset->browse_url : null,
          'label' => __('Repository URL'),
          'placeholder' => 'https://github.com/user/asset',
          'required' => true,
          'maxlength' => 2000,
          'autocomplete' => 'off',
        ])
        {{ __('This must be a URL to a public GitHub, GitLab.com or Bitbucket repository.') }}
        @endcomponent

        @if (!$editing)
        @component('components/form-input', [
          'type' => 'url',
          'name' => 'versions[0][download_url]',
          'value' => old('versions.0.download_url'),
          'label' => __('Download URL'),
          'placeholder' => 'https://github.com/user/asset/archive/v1.0.0.zip',
          'maxlength' => 2000,
          'autocomplete' => 'off',
        ])
        {{ __('If you leave this field empty, the download URL will be inferred from the repository URL and the asset version.') }}<br>
        {{ __('For example, if the asset version is "1.0.0", the ZIP archive corresponding to the Git tag "v1.0.0" will be used (note the leading "v").') }}
        @endcomponent
        @endif

        @component('components/form-input', [
          'type' => 'url',
          'name' => 'issues_url',
          'value' => $editing ? $asset->issues_url : null,
          'label' => __('Issues URL'),
          'placeholder' => 'https://github.com/user/asset/issues',
          'maxlength' => 2000,
          'autocomplete' => 'off',
        ])
        {{ __('If you leave this field empty, the issue reporting URL will be inferred from the repository URL.') }}
        @endcomponent

        @component('components/form-input', [
          'type' => 'url',
          'name' => 'icon_url',
          'value' => $editing ? $asset->icon_url : null,
          'label' => __('Icon URL'),
          'placeholder' => 'https://raw.githubusercontent.com/user/asset/master/icon.png',
          'maxlength' => 2000,
          'autocomplete' => 'off',
        ])
        {{ __('The recommended size is 256×256, but lower sizes are allowed.') }}<br>
        {{ __('Only PNG or JPEG images are allowed.') }}
        {{ __('If you leave this field empty, the icon must be committed to the repository as "icon.png" in the root directory.') }}
        @endcomponent

        @if ($editing)
        <h2 class="text-center text-xl font-medium my-8">
          {{ __('Manage versions') }}
        </h2>
        <div class="mt-2 text-sm text-gray-600 my-8">
          {{ __('For each version, if you leave the download URL field empty, it will be inferred from the repository URL and the asset version.') }}<br>
          {{ __('For example, if the asset version is "1.0.0", the ZIP archive corresponding to the Git tag "v1.0.0" will be used (note the leading "v").') }}
        </div>

        @error('versions')
        <div role="alert" class="form-error">
          {{ $message }}
        </div>
        @enderror

        <button type="button" id="asset-add-version" class="link">
          <span class="fa fa-plus mr-1"></span>
          {{ __('Add a new version') }}
        </button>

        {{-- Contains the HTML that will be copied when creating a new version --}}
        <template id="asset-version-prototype" data-index="{{ count($asset->versions) }}">
          @include('asset.version-form', ['prototype' => true])
        </template>

        <div id="asset-version-list">
          @foreach ($asset->versions as $version)
          @include('asset.version-form')
          @endforeach
        </div>
        @endif

        <h2 class="text-center text-xl font-medium my-8">
          {{ __('Manage previews') }}
        </h2>
        <div class="mt-2 text-sm text-gray-600 my-8">
          {{ __('Previews are optional, but help people become more interested in your asset.') }}
          {{ __('You can have up to 4 images or videos per asset.') }}
        </div>

        @error('previews')
        <div role="alert" class="form-error">
          {{ $message }}
        </div>
        @enderror

        {{-- Contains the HTML that will be copied when creating a new preview --}}
        <template id="asset-preview-prototype" data-index="{{ $editing ? count($asset->previews) : 0 }}">
          @include('asset.preview-form', ['prototype' => true])
        </template>

        <div id="asset-preview-list">
          @if ($editing)
          @foreach ($asset->previews as $preview)
          @include('asset.preview-form')
          @endforeach
          @endif
        </div>

        <button type="button" id="asset-add-preview" class="link">
          <span class="fa fa-plus mr-1"></span>
          {{ __('Add a new preview') }}
        </button>

        <button class="button button-primary w-full mt-6" type="submit" data-loading>
          @if ($editing)
          {{ __('Save') }}
          @else
          {{ __('Submit asset') }}
          @endif
        </button>
      </section>
    </form>
  </div>
@endsection
