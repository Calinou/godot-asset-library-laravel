import barba from '@barba/core';

/**
 * Initialize the navigation bar toggle button for mobile devices.
 * Note: Several areas can be toggled at once.
 */
function initNavbarToggle(): void {
  const $navbarToggle = document.getElementById('navbar-toggle') as HTMLButtonElement;
  $navbarToggle.addEventListener('click', () => {
    // eslint-disable-next-line
    const navbarItems = document.querySelectorAll('[data-navbar-collapse]') as NodeListOf<HTMLElement>;

    navbarItems.forEach(($navbarItem: HTMLElement) => {
      $navbarItem.classList.toggle('hidden');
    });
  });
}

/**
 * Initialize the `/` keyboard shortcut for the asset search field.
 */
function initAssetSearch(): void {
  const $assetSearch = document.getElementById('asset-search') as HTMLInputElement;

  document.addEventListener('keydown', (event) => {
    // Only focus on the search field if not already entering text in another input field
    if (
      document.activeElement?.tagName !== 'INPUT'
      && document.activeElement?.tagName !== 'TEXTAREA'
      && event.keyCode === 58
    ) {
      // Don't type a slash at the beginning of the field
      event.preventDefault();

      $assetSearch.focus();

      // Select all the text in the field. This must be done after focusing the field.
      $assetSearch.setSelectionRange(0, -1);
    }
  });
}

/**
 * Initialize interactivity for gallery images.
 */
function initGalleryImages(): void {
  const $galleryImageBig = document.getElementById('gallery-image-big') as HTMLImageElement;
  const $galleryImageAnchor = document.getElementById('gallery-image-anchor') as HTMLAnchorElement;
  const $galleryImageCaption = document.getElementById('gallery-image-caption') as HTMLDivElement;
  const galleryImagesSmall = document.getElementsByClassName('gallery-image-small') as HTMLCollectionOf<HTMLImageElement>;

  Array.prototype.forEach.call(galleryImagesSmall, ($galleryImageSmall: HTMLImageElement) => {
    $galleryImageSmall.addEventListener('click', (event: MouseEvent) => {
      event.preventDefault();

      // `$target.classList.replace()` isn't supported by Safari as of October 2019:
      // https://caniuse.com/#feat=mdn-api_element_classlist_replace&search=classlist%20replace

      // Remove active status from the old image
      const $oldActiveImage = (
          document.getElementsByClassName('gallery-image-small-active') as HTMLCollectionOf<HTMLImageElement>
      )[0];
      $oldActiveImage.classList.remove('gallery-image-small-active');
      $oldActiveImage.classList.add('gallery-image-small-inactive');

      // Add active status to new image
      const $target = event.target as HTMLImageElement;
      $target.classList.remove('gallery-image-small-inactive');
      $target.classList.add('gallery-image-small-active');

      // Use the full-size image if available, or the thumbnail
      // if the full-size image is missing for some reason
      $galleryImageBig.src = $target.dataset.fullSize ?? $target.src;
      $galleryImageAnchor.href = $target.dataset.fullSize ?? $target.src;
      // Use a non-breaking space to ensure consistent height if there's no caption
      $galleryImageCaption.innerText = $target.alt ?? ' ';
    });
  });
}

/**
 * Initialize interactivity for the review editing buttons.
 */
function initEditReviewButtons(): void {
  // eslint-disable-next-line
  const editReviewButtons = document.querySelectorAll('[data-review-edit]') as NodeListOf<HTMLButtonElement>;

  editReviewButtons.forEach(($editReviewButton) => {
    $editReviewButton.addEventListener('click', () => {
      // Make a reference to avoid modifying the function parameter directly
      const $editReviewButton2 = $editReviewButton;
      const $comment = $editReviewButton.parentElement?.parentElement?.querySelector('[data-review-comment]') as HTMLDivElement;
      const $editForm = $editReviewButton.parentElement?.parentElement?.querySelector('[data-review-edit-form]') as HTMLFormElement;

      $editReviewButton2.style.display = 'none';
      $comment.style.display = 'none';
      $editForm.style.display = 'block';

      const $cancelButton = $editForm.querySelector('[data-review-edit-cancel]') as HTMLButtonElement;
      $cancelButton.addEventListener('click', () => {
        $editReviewButton2.style.display = 'inline-block';
        $comment.style.display = 'block';
        $editForm.style.display = 'none';
      });
    });
  });
}

/**
 * Make it possible to submit forms while focused on a `textarea` element
 * by pressing Ctrl + Enter (or Cmd + Enter on macOS).
 */
function initTextAreas(): void {
  const textareas = document.querySelectorAll('textarea');

  textareas.forEach(($textarea: HTMLTextAreaElement) => {
    $textarea.addEventListener('keydown', (event) => {
      // <https://stackoverflow.com/questions/1684196/ctrlenter-jquery-in-textarea>
      if ((event.ctrlKey || event.metaKey) && (event.keyCode === 10 || event.keyCode === 13)) {
        const forms = document.querySelectorAll('form');
        // ESLint wants to remove the type casting, but we need it to compile the script
        // eslint-disable-next-line
        const buttonsLoading = document.querySelectorAll('[data-loading]') as NodeListOf<HTMLElement>;

        forms.forEach((form: HTMLFormElement) => {
          if (form.contains($textarea) && form.checkValidity()) {
            form.submit();

            // Make the form button display its "loading" state to confirm submission
            buttonsLoading.forEach(($buttonLoading: HTMLElement) => {
              if (form.contains($buttonLoading)) {
                $buttonLoading.classList.add('button-loading');
              }
            });
          }
        });
      }
    });
  });
}

/**
 * Initialize interactivity for buttons which trigger long operations.
 */
function initLoadingButtons(): void {
  // ESLint wants to remove the type casting, but we need it to compile the script
  // eslint-disable-next-line
  const buttonsLoading = document.querySelectorAll('[data-loading]') as NodeListOf<HTMLElement>;
  const forms = document.querySelectorAll('form');

  buttonsLoading.forEach(($buttonLoading: HTMLElement) => {
    $buttonLoading.addEventListener('click', () => {
      if ($buttonLoading instanceof HTMLButtonElement) {
        forms.forEach((form: HTMLFormElement) => {
          if (form.contains($buttonLoading) && form.checkValidity()) {
            $buttonLoading.classList.add('button-loading');
          }
        });
      } else {
        $buttonLoading.classList.add('button-loading');
      }
    });
  });
}

/**
 * Initialize interactivity for buttons which delete fields on the asset editing form.
 */
function initDeleteAssetFieldButtons(field: string): void {
  const deleteButtons = document.querySelectorAll(`[data-delete-${field}]`);

  deleteButtons.forEach(($deleteButton) => {
    $deleteButton.addEventListener('click', () => {
      $deleteButton.parentElement?.remove();
    });
  });
}

/**
 * Initialize interactivity for the "Add new <field>" button on the asset editing form.
 */
function initAddAssetFieldButton(field: string): void {
  const $addFieldButton = document.getElementById(`asset-add-${field}`) as HTMLButtonElement;
  const $assetFieldPrototype = document.getElementById(`asset-${field}-prototype`) as HTMLTemplateElement;
  const $assetFieldList = document.getElementById(`asset-${field}-list`) as HTMLDivElement;

  if ($addFieldButton) {
    $addFieldButton.addEventListener('click', () => {
      // Add a new field at the end
      $assetFieldList.appendChild($assetFieldPrototype.content.cloneNode(true));

      // Replace the index in the newly created copy
      const $newField = $assetFieldList.lastElementChild;
      if ($newField) {
        $newField.innerHTML = $newField.innerHTML.replace(
          /__index__/g, $assetFieldPrototype.dataset.index ?? '0',
        );
      }

      // Make the delete button functional
      initDeleteAssetFieldButtons(field);

      // Increment the counter (HTML data attributes are always strings)
      $assetFieldPrototype.dataset.index = (
        Number($assetFieldPrototype.dataset.index) + 1
      ).toString();
    });
  }
}

/**
 * Initialize interactivity for the asset list sorting options.
 */
function initAssetSortSelect(): void {
  const $assetSortSelect = document.getElementById('sort') as HTMLSelectElement;
  const $sortForm = document.getElementById('sort-form') as HTMLFormElement;

  if ($assetSortSelect && $sortForm) {
    $assetSortSelect.addEventListener('change', () => {
      $sortForm.submit();
    });
  }
}

/**
 * Make flash messages dismissable by clicking a button.
 */
function initFlashClose(): void {
  // eslint-disable-next-line
  const flashCloseButtons = document.querySelectorAll('[data-flash-close]') as NodeListOf<HTMLButtonElement>;

  flashCloseButtons.forEach(($button: HTMLButtonElement) => {
    $button.addEventListener('click', () => {
      $button.parentElement?.remove();
    });
  });
}

// Call functions that need to be called on every page change here.
// This is also called on the initial page load.
const initAll = (): void => {
  initGalleryImages();
  initEditReviewButtons();
  initTextAreas();
  initLoadingButtons();
  initAddAssetFieldButton('version');
  initAddAssetFieldButton('preview');
  initAssetSortSelect();
  initFlashClose();
};

window.addEventListener('DOMContentLoaded', () => {
  barba.init({
    transitions: [{
      leave(): void {
        // Make it clear the browser is waiting for another page to load
        document.body.classList.add('opacity-50');
      },
      after(): void {
        document.body.classList.remove('opacity-50');
        initAll();
      },
    }],
  });

  // Since the search field and navigation toggle buttons are part of the
  // navigation bar, they only need to be registered on the initial page load
  initAssetSearch();
  initNavbarToggle();

  initAll();
});
