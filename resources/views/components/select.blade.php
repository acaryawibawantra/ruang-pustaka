@aware(['name' => null, 'hint' => null])
@php
    $fieldName = $attributes->get('name', $name);
    $hasError = $fieldName && $errors->has($fieldName);
    $describedBy = trim(($hint ? $fieldName.'-hint ' : '').($hasError ? $fieldName.'-error' : ''));
@endphp
<select {{ $attributes->class(['form-control bg-white'])->merge(array_filter(['name' => $fieldName, 'id' => $fieldName, 'aria-invalid' => $hasError ? 'true' : null, 'aria-describedby' => $describedBy ?: null])) }}>{{ $slot }}</select>
