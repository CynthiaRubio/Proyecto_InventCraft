@if ($errors->any())
<div class="container mt-4">
    <div class="alert alert-danger" role="alert">
        <h4 class="alert-heading">¡Error!</h4>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endif

