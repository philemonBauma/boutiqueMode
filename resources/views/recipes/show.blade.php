<x-layout>
    <h1 class="font-bold text-xl nb-50">{{ $recipe->title }}</h1>
    <h2>Ingredients:</h2>
    <ul class="list-disc list-outside space-y-2 pl-5 text-gray-700 font-medium">
        @foreach ($recipe->ingredients as $ingredient)
        
            <li class="text-gray-500">{{ $ingredient }}</li>
        @endforeach
    </ul>
    <h2>Instructions:</h2>
    <ol class="list-decimal list-outside space-y-3 pl-5 text-gray-700 font-medium">
        @foreach ($recipe->instructions as $instruction)
            <li class="text-gray-500">{{ $instruction }}</li>
        @endforeach
    </ol>
</x-layout>