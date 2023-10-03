@props(['category'])

<div class="ml-3">
    <li class="{{ $category->depth === 0 ? 'font-bold' : '' }}">{{ $category->title }}</li>

    @foreach ($category->children as $child)
        <x-category-list :category="$child" />
    @endforeach
</div>
