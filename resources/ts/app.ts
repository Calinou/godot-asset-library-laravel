import barba from '@barba/core';

/**
 * Initialize interactivity for gallery images.
 */
function initGalleryImages(): void {
  const $galleryImageBig = document.getElementById('gallery-image-big') as HTMLImageElement;
  const $galleryImageAnchor = document.getElementById('gallery-image-anchor') as HTMLAnchorElement;
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
      const $target = (event.target as HTMLImageElement);
      $target.classList.remove('gallery-image-small-inactive');
      $target.classList.add('gallery-image-small-active');

      // Use the full-size image if available, or the thumbnail
      // if the full-size image is missing for some reason
      $galleryImageBig.src = $target.dataset.fullSize || $target.src;
      $galleryImageAnchor.href = $target.dataset.fullSize || $target.src;
    });
  });
}

// Call functions that need to be called on every page change here,
// in addition to the `window.addEventListener` call below
// (so it works on the initial page load as well)
barba.hooks.after(() => {
  initGalleryImages();
});

window.addEventListener('DOMContentLoaded', () => {
  barba.init();
  initGalleryImages();
});
