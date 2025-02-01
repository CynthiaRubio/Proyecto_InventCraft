@extends('layouts.full')

@section('title', 'Seleccionar Avatar')

@section('content')
<div class="container d-flex justify-content-center mt-5 mb-5">
    <div class="card text-center text-light border-0 rounded shadow-lg"
         style="background: url('{{ asset('images/avatars/profile-bg.webp') }}') center/cover no-repeat; width: 700px; min-height: 550px;">
        
        <div class="card-body p-5" style="background: rgba(0,0,0,0.6); border-radius: 10px;">
            <h2 class="fw-bold">Selecciona tu Avatar</h2>

            <form action="{{ route('users.avatar.update', $user->_id) }}" method="POST">
                @csrf
                <div class="d-flex flex-column align-items-center gap-4 mt-4">
                    <div class="d-flex justify-content-center gap-5">
                        @for ($i = 1; $i <= 3; $i++)
                            <label class="position-relative avatar-label">
                                <input type="radio" name="avatar" value="{{ $i }}" class="d-none" required>
                                <img src="{{ asset('images/avatars/'.$i.'.webp') }}" 
                                     class="rounded-circle border border-4 border-light shadow-lg avatar-option"
                                     width="160" height="160">
                            </label>
                        @endfor
                    </div>

                    <div class="d-flex justify-content-center gap-5">
                        @for ($i = 4; $i <= 6; $i++)
                            <label class="position-relative avatar-label">
                                <input type="radio" name="avatar" value="{{ $i }}" class="d-none" required>
                                <img src="{{ asset('images/avatars/'.$i.'.webp') }}" 
                                     class="rounded-circle border border-4 border-light shadow-lg avatar-option"
                                     width="160" height="160">
                            </label>
                        @endfor
                    </div>
                </div>

                <button type="submit" class="btn btn-warning mt-4 fw-bold px-4 py-2">✅ Confirmar Avatar</button>
            </form>

            <a href="{{ route('users.show', $user->_id) }}" class="btn btn-secondary mt-3 px-4 py-2">❌ Cancelar</a>
        </div>
    </div>
</div>

<style>
    .avatar-option {
        cursor: pointer;
        transition: transform 0.3s ease-in-out, box-shadow 0.3s;
    }

    .avatar-option:hover {
        transform: scale(1.2);
        box-shadow: 0 0 20px rgba(255, 255, 255, 0.4);
    }

    input[type="radio"]:checked + img {
        border-color: gold !important;
        box-shadow: 0 0 20px gold;
        transform: scale(1.1);
    }
</style>
@endsection