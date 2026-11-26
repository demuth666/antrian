@props(['question'])

<details {{ $attributes->class('faq-item') }}>
    <summary>{{ $question }}</summary>
    <p>{{ $slot }}</p>
</details>
