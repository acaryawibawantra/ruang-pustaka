@aware(['name' => null, 'hint' => null])
@php
    $fieldName = $attributes->get('name', $name);
    $hasError = $fieldName && $errors->has($fieldName);
    $describedBy = trim(($hint ? $fieldName.'-hint ' : '').($hasError ? $fieldName.'-error' : ''));
@endphp
<textarea {{ $attributes->class(['form-control py-3'])->merge(array_filter(['rows' => 4, 'name' => $fieldName, 'id' => $fieldName, 'aria-invalid' => $hasError ? 'true' : null, 'aria-describedby' => $describedBy ?: null])) }}>{{ $slot }}</textarea>
