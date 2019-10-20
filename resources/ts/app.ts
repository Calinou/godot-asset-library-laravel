import barba from '@barba/core';

/**
 * Initialize interactivity for gallery images.
 */
function initGalleryImages(): void {
  const $galleryImageBig = document.getElementById('gallery-image-big') as HTMLImageElement;
  const galleryImagesSmall = document.getElementsByClassName('gallery-image-small') as HTMLCollectionOf<HTMLImageElement>;

  Array.prototype.forEach.call(galleryImagesSmall, ($galleryImageSmall: HTMLImageElement) => {
    $galleryImageSmall.addEventListener('click', (event: MouseEvent) => {
      event.preventDefault();
      $galleryImageBig.src = (event.target as HTMLImageElement).src;
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
