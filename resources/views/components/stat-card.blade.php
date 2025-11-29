<div class="stat-card p-4 d-flex flex-column align-items-center text-center shadow-lg h-100" 
     style="background: {{ $gradient ?? 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)' }}; border-radius: 12px;">
    <h5 class="card-title text-white fw-bold mb-2">{{ $name }}</h5>
    @if(!empty($description))
        <p class="text-white mb-3" style="font-size: 0.85rem; line-height: 1.3; opacity: 0.95;">{{ $description }}</p>
    @endif
    <span class="badge rounded-pill bg-light text-dark fs-5 px-4 py-2 mt-auto">{{ $value }}</span>
</div>

