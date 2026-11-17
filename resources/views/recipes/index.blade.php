<x-layout>
    
    <h1 class="text-2xl font-bold mb-4">My Recipes</h1>

    @foreach ($recipes as $key => $recipe)
        <p><a href="{{ route('recipes.show', $recipe->id) }}" class="underline text-blue-500 mb-50"> {{ $recipe->title }}</a></p>
    @endforeach
</x-layout>