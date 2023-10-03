<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="font-bold text-xl mb-6">Categories</h2>
                <ul class="space-y-3">
                    @foreach ($categories as $category)
                        <x-category-list :category="$category" />
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
