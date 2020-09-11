@extends('layouts.app')

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

      {{-- Prevent horizontal scrolling due to the negative margins used for column gaps --}}
      <div class="overflow-hidden">
        {{-- Use a two-column display on wide screens to reduce the need for scrolling --}}
        <section class="flex flex-wrap mt-8 -mx-6">
          <section class="w-full lg:w-1/2 px-6">
            <div class="lg:flex">
              <div class="lg:mr-2 lg:w-1/2">
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
              </div>

              <div class="lg:ml-2 lg:w-1/2">
                @component('components/form-input', [
                  'name' => 'blurb',
                  'value' => $editing ? $asset->blurb : null,
                  'label' => __('Blurb'),
                  'placeholder' => __('One-line description of the asset'),
                  'maxlength' => 60,
                  'autocomplete' => 'off',
                ])
                @endcomponent
              </div>
            </div>

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
              'value' => $editing ? $asset->getRawOriginal('tags') : null,
              'label' => __('Tags'),
              'placeholder' => 'platformer, 2d, pixel-art, gdnative',
              'autocomplete' => 'off',
            ])
            {{ __('A comma-separated list of tags (up to :maxTags). Only lowercase characters, numbers and dashes are allowed in tag names.',
                ['maxTags' => App\Asset::MAX_TAGS]) }}
            @endcomponent

            <div class="sm:flex sm:justify-between">
              @php
              $categories = [];
              foreach (range(0, App\Asset::CATEGORY_MAX - 1) as $categoryId) {
                $categories[] = App\Asset::getCategoryName($categoryId);
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
                'choices' =>  App\Asset::LICENSES,
              ])
              {{ __('See') }}
              <a
                class="link"
                href="https://choosealicense.com/"
                target="_blank"
                rel="nofollow noopener noreferrer"
              >Choose a License</a>
              {{ __('for guidance.') }}
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
                'choices' => App\AssetVersion::GODOT_VERSIONS,
              ])
              @endcomponent
            </div>

            <div class="-mt-4 mb-8 text-sm text-gray-600">
              {{ __('The "Any" version should only be used for assets that do not contain code (such as engine-agnostic art assets). If in doubt, choose the minor Godot version used to develop the asset.') }}
            </div>
            @endif

            @component('components/form-input', [
              'type' => 'url',
              'name' => 'browse_url',
              'value' => $editing ? $asset->browse_url : null,
              'label' => __('Git repository URL'),
              'placeholder' => 'https://github.com/user/asset',
              'required' => true,
              'maxlength' => 2000,
              'autocomplete' => 'off',
            ])
            {{ __('This must be a URL to a public GitHub, GitLab.com or Bitbucket repository.') }}
            @endcomponent

            @component('components/form-input', [
              'type' => 'url',
              'name' => 'changelog_url',
              'value' => $editing ? $asset->changelog_url : null,
              'label' => __('Changelog URL'),
              'placeholder' => 'https://github.com/user/asset/blob/master/CHANGELOG.md',
              'maxlength' => 2000,
              'autocomplete' => 'off',
            ])
            {{ __('Optional. This URL should point to a changelog documenting user-facing changes (i.e. not an automatically generated commit log).') }}
            @endcomponent

            @component('components/form-input', [
              'type' => 'url',
              'name' => 'donate_url',
              'value' => $editing ? $asset->donate_url : null,
              'label' => __('Donate URL'),
              'placeholder' => 'https://patreon.com/user',
              'maxlength' => 2000,
              'autocomplete' => 'off',
            ])
            {{ __('Optional. This URL should point to a page to be used for donations (such as Patreon or GitHub Sponsors).') }}
            @endcomponent

            @if (!$editing)
            @component('components/form-input', [
              'type' => 'url',
              'name' => 'versions[0][download_url]',
              'value' => old('versions.0.download_url'),
              'label' => __('Custom download URL'),
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
              'name' => 'icon_url',
              'value' => $editing ? $asset->icon_url : null,
              'label' => __('Custom icon URL'),
              'placeholder' => 'https://raw.githubusercontent.com/user/asset/master/icon.png',
              'maxlength' => 2000,
              'autocomplete' => 'off',
            ])
            {{ __('If you leave this field empty, the icon must be committed to the repository as "icon.png" in the repository\'s root directory.') }}
            {{ __('Only PNG or JPEG images are allowed.') }}
            {{ __('The recommended size is 256×256, but lower sizes are allowed.') }}<br>
            @endcomponent

            @component('components/form-input', [
              'type' => 'url',
              'name' => 'issues_url',
              'value' => $editing ? $asset->issues_url : null,
              'label' => __('Custom issue reporting URL'),
              'placeholder' => 'https://github.com/user/asset/issues',
              'maxlength' => 2000,
              'autocomplete' => 'off',
            ])
            {{ __('If you leave this field empty, the issue reporting URL will be inferred from the repository URL.') }}
            @endcomponent
          </section>

          <section class="w-full lg:w-1/2 px-6">
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

            {{-- Contains the HTML that will be copied when creating a new version --}}
            <template id="asset-version-prototype" data-index="{{ count($asset->versions) }}">
              @include('asset.version-form', ['prototype' => true])
            </template>

            <div id="asset-version-list">
              @foreach ($asset->versions as $version)
              @include('asset.version-form')
              @endforeach
            </div>

            <button type="button" id="asset-add-version" class="px-2 link">
              <span class="fa fa-plus mr-1"></span>
              {{ __('Add a new version') }}
            </button>
            @endif

            <h2 class="text-center text-xl font-medium my-8">
              {{ __('Manage previews') }}
            </h2>
            <div class="mt-2 text-sm text-gray-600 my-8">
              {{ __('Previews are optional, but help people become more interested in your asset.') }}
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

            <button type="button" id="asset-add-preview" class="px-2 link">
              <span class="fa fa-plus mr-1"></span>
              {{ __('Add a new preview') }}
            </button>
          </section>
        </section>
        <div class="flex justify-center">
          <button class="button button-primary px-8 mt-6" type="submit" data-loading>
            @if ($editing)
            {{ __('Save changes') }}
            @else
            {{ __('Submit asset') }}
            @endif
          </button>
        </div>
      </div>
    </form>
  </div>
@endsection
