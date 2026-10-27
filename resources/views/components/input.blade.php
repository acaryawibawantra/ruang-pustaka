@aware(['name' => null, 'hint' => null])
@php
    $fieldName = $attributes->get('name', $name);
    $hasError = $fieldName && $errors->has($fieldName);
    $describedBy = trim(($hint ? $fieldName.'-hint ' : '').($hasError ? $fieldName.'-error' : ''));
@endphp
<input {{ $attributes->class(['form-control'])->merge(array_filter(['type' => 'text', 'name' => $fieldName, 'id' => $fieldName, 'aria-invalid' => $hasError ? 'true' : null, 'aria-describedby' => $describedBy ?: null])) }}>
