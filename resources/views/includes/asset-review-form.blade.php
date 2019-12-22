<form method="POST" action="{{ $action }}">
  @csrf
  @if ($editing)
  @method('PUT')
  @endif

  @component('components/form-select', [
    'name' => 'is_positive',
    'label' => __('Your rating'),
    'placeholder' => __('Select a rating'),
    'required' => true,
    'choices' => [
      1 => __('Recommended'),
      0 => __('Not recommended'),
    ],
  ])
  @endcomponent

  @component('components/form-input', [
    'type' => 'textarea',
    'name' => 'comment',
    'value' => $value ?? null,
    'label' => __('Comment'),
    'placeholder' => __('Optional. If you leave a comment, it will be displayed in the list of reviews.'),
    'maxlength' => 2000,
    'autocomplete' => 'off',
    'class' => 'h-32',
  ])
  {{ __('Supports') }}
  <a
    class="link"
    href="https://guides.github.com/features/mastering-markdown/"
    target="_blank"
    rel="nofollow noopener noreferrer"
  >GitHub Flavored Markdown</a>.
  {{ __('Please follow the') }}
  <a
    class="link"
    href="https://godotengine.org/code-of-conduct"
    target="_blank"
    rel="nofollow noopener noreferrer"
  >{{ __('Code of Conduct') }}</a>
  {{ __('when writing your review.') }}<br>
  {{ __("Don't use this form for support requests. Instead, report issues with the asset") }}
  <a
    class="link"
    href="{{ $asset->issues_url }}"
    target="_blank"
    rel="nofollow noopener noreferrer"
  >{{ __('here') }}</a>.
  @endcomponent

  <button class="button button-primary mt-4" type="submit" data-loading>
    {{ $editing ? __('Update review') : __('Submit review') }}
  </button>

  @if ($editing)
  <button type="button" class="button mt-4 text-gray-600 dark:text-gray-500" data-review-edit-cancel>
    {{ __('Cancel') }}
  </button>
  @endif
</form>
