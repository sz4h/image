# Changelog

All notable changes to `image` will be documented in this file.

## 1.1.5

- Serve the configured `not_found_image_path` fallback when no image path is
  supplied (e.g. `/resize/thumbnail?crop=1`) instead of throwing
  `CanNotHandleNonImageType`. A genuinely non-image extension still throws.
- Cast the optional route parameter before `explode()` so a bare `/resize`
  request no longer emits a PHP 8.1+ deprecation.

- First initial
