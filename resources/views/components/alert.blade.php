@php
if (!isset($type)) {
  throw new \Exception('An alert "type" is required (must be "info", "warning", "error" or "success")');
}

switch ($type) {
  case 'info':
    $classes = 'bg-blue-100 border-blue-500 text-blue-700 dark:bg-blue-1000 dark:border-blue-500 dark:text-blue-200';
    break;
  case 'warning':
    $classes = 'bg-yellow-100 border-yellow-500 text-yellow-800 dark:bg-yellow-1000 dark:border-yellow-500 dark:text-yellow-200';
    break;
  case 'error':
    $classes = 'bg-red-100 border-red-500 text-red-700 dark:bg-red-1000 dark:border-red-500 dark:text-red-200';
    break;
  case 'success':
    $classes = 'bg-green-100 border-green-500 text-green-700 dark:bg-green-1000 dark:border-green-500 dark:text-green-200';
    break;
  default:
    throw new \Exception('Invalid alert type "'.$type.'" (must be "info", "warning", "error" or "success")');
}
@endphp

<div class="px-5 py-3 mb-8 rounded border-l-4 {{ $classes }}" role="alert">
  {{ $slot }}
</div>
