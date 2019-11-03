import barba from '@barba/core';

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
      $galleryImageBig.src = $target.dataset.fullSize || $target.src;
      $galleryImageAnchor.href = $target.dataset.fullSize || $target.src;
      // Use a non-breaking space to ensure consistent height if there's no caption
      $galleryImageCaption.innerText = $target.alt || ' ';
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
 * Initialize interactivity for the "Add new version" button on the asset editing form.
 */
function initAddAssetVersionButton(): void {
  const $addVersionButton = document.getElementById('asset-add-version') as HTMLButtonElement;
  const $assetVersionPrototype = document.getElementById('asset-version-prototype') as HTMLTemplateElement;
  const $assetVersionList = document.getElementById('asset-version-list') as HTMLDivElement;

  if ($addVersionButton) {
    $addVersionButton.addEventListener('click', () => {
      // Add a new version at the end
      $assetVersionList.appendChild($assetVersionPrototype.content.cloneNode(true));

      // Replace the index in the newly created copy
      const $newVersion = $assetVersionList.lastElementChild;
      if ($newVersion) {
        $newVersion.innerHTML = $newVersion.innerHTML.replace(
          /__index__/g, $assetVersionPrototype.dataset.index || '0',
        );
      }

      // Increment the counter (HTML data attributes are always strings)
      $assetVersionPrototype.dataset.index = (
        Number($assetVersionPrototype.dataset.index) + 1
      ).toString();
    });
  }
}

// Call functions that need to be called on every page change here,
// in addition to the `window.addEventListener` call below
// (so it works on the initial page load as well)
barba.hooks.after(() => {
  initGalleryImages();
  initLoadingButtons();
  initAddAssetVersionButton();
});

window.addEventListener('DOMContentLoaded', () => {
  barba.init();
  initGalleryImages();
  initLoadingButtons();
  initAddAssetVersionButton();
});
