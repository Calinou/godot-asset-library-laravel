@php
if (!isset($type)) {
  throw new \Exception('An alert "type" is required (must be "info", "warning", "error" or "success")');
}

switch ($type) {
  case 'info':
    $classes = 'bg-blue-100 border-blue-500 text-blue-700';
    break;
  case 'warning':
    $classes = 'bg-yellow-100 border-yellow-500 text-yellow-800';
    break;
  case 'error':
    $classes = 'bg-red-100 border-red-500 text-red-700';
    break;
  case 'success':
    $classes = 'bg-green-100 border-green-500 text-green-700';
    break;
  default:
    throw new \Exception('Invalid alert type "'.$type.'" (must be "info", "warning", "error" or "success")');
}
@endphp

<div class="px-5 py-3 mb-8 rounded border-l-4 {{ $classes }}" role="alert">
  {{ $slot }}
</div>
