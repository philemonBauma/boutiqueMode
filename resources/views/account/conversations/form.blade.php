@if ($errors->any())
    <div class="form-errors" role="alert">@foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>
@endif

<form class="admin-form product-edit-form" action="{{ $action }}" method="POST">
    @csrf
    @isset($conversation)
        <label for="body">Votre message</label>
    @else
        <label for="subject">Sujet</label>
        <input id="subject" name="subject" value="{{ old('subject') }}" maxlength="150" required>
        <label for="body">Votre message</label>
    @endisset
    <textarea id="body" name="body" rows="6" maxlength="5000" required>{{ old('body') }}</textarea>
    <button class="button button-dark" type="submit">{{ $buttonLabel }} <span aria-hidden="true">→</span></button>
</form>
