import barba from '@barba/core';

/**
 * Initialize interactivity for gallery images.
 */
function initGalleryImages() {
  const $galleryImageBig = document.getElementById('gallery-image-big');
  const galleryImagesSmall = document.getElementsByClassName('gallery-image-small');

  Array.prototype.forEach.call(galleryImagesSmall, ($galleryImageSmall) => {
    $galleryImageSmall.addEventListener('click', (event) => {
      event.preventDefault();
      $galleryImageBig.src = event.target.src;
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
