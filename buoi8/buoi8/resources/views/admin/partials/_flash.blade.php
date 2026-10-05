@if(session('ok'))
    <div class="alert alert-success d-flex align-items-center alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>
        <div>{{ session('ok') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(isset($errors) && $errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="fw-semibold mb-1">
            <i class="bi bi-exclamation-triangle me-2"></i>Lỗi
        </div>
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
