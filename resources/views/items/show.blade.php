@extends('layouts.app')

@section('page_title', 'Item Images - #' . $item->ItemCode)

@section('content')
<div class="container-fluid">
    @php
        $placeholderImage = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800" viewBox="0 0 1200 800"><rect width="1200" height="800" fill="#f2f4f7"/><rect x="60" y="60" width="1080" height="680" rx="24" fill="#ffffff" stroke="#d0d7de" stroke-dasharray="18 14"/><text x="600" y="380" text-anchor="middle" font-family="Arial, sans-serif" font-size="42" fill="#98a2b3">No image available</text></svg>');

        $resolveImageUrl = function ($path) use ($placeholderImage) {
            $path = trim((string) $path);

            if ($path === '') {
                return $placeholderImage;
            }

            if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
                return $path;
            }

            return route('items.image', ['path' => ltrim($path, '/')]);
        };

        $imagePaths = $images->map(function ($image) use ($resolveImageUrl) {
            return $resolveImageUrl($image->image_path ?? '');
        })->filter()->values();
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-0">Item #{{ $item->ItemCode }} - {{ $item->ItemName }}</h4>
            <small class="text-muted">Manage item photos, replace existing images, or remove ones you no longer need.</small>
        </div>
        <div class="d-flex gap-2">
            <button type="button"
                    class="btn btn-outline-secondary"
                    data-share-url="{{ route('items.share', $item->ItemCode) }}"
                    onclick="(function(btn){var url=btn.getAttribute('data-share-url');var w=window.open(url,'_blank');if(!w){window.location.href=url;}})(this);">
                <i class="fas fa-share-alt"></i> Share
            </button>
            <a href="{{ route('items.edit', $item->ItemCode) }}" class="btn btn-outline-primary">Edit Item</a>
            <a href="{{ route('items.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3"><strong>Barcode:</strong><br>{{ $item->Barcode ?? '-' }}</div>
                <div class="col-md-3"><strong>Category:</strong><br>{{ $item->Category ?? '-' }}</div>
                <div class="col-md-3"><strong>Company:</strong><br>{{ $item->CompanyName ?? '-' }}</div>
                <div class="col-md-3"><strong>Packet Size:</strong><br>{{ $item->PacketSize ?? '-' }}</div>
                <div class="col-md-3"><strong>Unit Purchase:</strong><br>{{ $item->UPurprice ?? 0 }}</div>
                <div class="col-md-3"><strong>Unit Sale:</strong><br>{{ $item->USalprice ?? 0 }}</div>
                <div class="col-md-3"><strong>Retail:</strong><br>{{ $item->RPprice ?? 0 }}</div>
                <div class="col-md-3"><strong>Description:</strong><br>{{ $item->ItemDescription ?? '-' }}</div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Add More Images</span>
        </div>
        <div class="card-body">
            <form action="{{ route('items.images.store', $item->ItemCode) }}" method="POST" enctype="multipart/form-data" class="row g-3 align-items-end" id="itemImagesForm">
                @csrf
                <div class="col-md-9">
                    <label for="images" class="form-label">Select one or more images</label>
                    <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" required>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary w-100">Upload Images</button>
                </div>
                <div class="col-12">
                    <div class="border rounded-3 p-3 bg-light">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button type="button" class="btn btn-dark btn-sm" id="openCameraBtn">
                                <i class="fas fa-camera"></i> Open Camera
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" id="capturePhotoBtn" disabled>
                                <i class="fas fa-circle-dot"></i> Capture Photo
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" id="stopCameraBtn" disabled>
                                <i class="fas fa-stop"></i> Stop Camera
                            </button>
                        </div>

                        <video id="cameraPreview" class="w-100 rounded d-none" autoplay playsinline muted style="max-height: 240px; background: #000;"></video>
                        <canvas id="cameraCanvas" class="d-none"></canvas>

                        <div id="cameraStatus" class="small text-muted mt-2">
                            Use the camera buttons to capture a live photo and add it to the upload list.
                        </div>

                        <div id="capturedPhotos" class="d-flex flex-wrap gap-2 mt-3"></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <span>Current Images</span>
        </div>
        <div class="card-body">
            @if($images->isEmpty())
                <div class="alert alert-info mb-0">No images uploaded yet.</div>
            @else
                <div class="mb-4">
                    <div class="d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-outline-secondary" id="prevImage">Prev</button>
                        <div class="flex-grow-1 bg-light d-flex align-items-center justify-content-center" style="min-height: 360px;">
                            <img id="currentImage" src="" alt="Item image preview" style="max-height: 520px; width: 100%; object-fit: contain;">
                        </div>
                        <button type="button" class="btn btn-outline-secondary" id="nextImage">Next</button>
                    </div>
                    <div class="text-center small text-muted mt-2" id="imageCounter"></div>
                </div>
                <div class="row g-3">
                    @foreach($images as $image)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                @php
                                    $imgUrl = $imagePaths[$loop->index] ?? $placeholderImage;
                                @endphp
                                <img src="{{ $imgUrl }}" class="card-img-top" alt="Item image {{ $image->id }}" style="height: 240px; object-fit: contain; background: #f8f9fa;" onerror="this.src='{{ $placeholderImage }}'">
                                <div class="card-body">
                                    <form action="{{ route('items.images.update', $image->id) }}" method="POST" enctype="multipart/form-data" class="mb-2">
                                        @csrf
                                        @method('PUT')
                                        <label class="form-label small mb-1">Replace image</label>
                                        <input type="file" class="form-control form-control-sm mb-2" name="image" accept="image/*" required>
                                        <button type="submit" class="btn btn-sm btn-outline-primary w-100">Change Image</button>
                                    </form>
                                    <a href="{{ route('items.share', ['id' => $item->ItemCode, 'image' => $image->id]) }}"
                                       class="btn btn-sm btn-outline-success w-100 mb-2"
                                       target="_blank"
                                       rel="noopener">
                                        Share This Image
                                    </a>
                                    <form action="{{ route('items.images.destroy', $image->id) }}" method="POST" onsubmit="return confirm('Delete this image?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100">Remove Image</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function () {
                var images = @json($imagePaths);
                if (!images || !images.length) {
                        return;
                }

                var current = 0;
                var imgEl = document.getElementById('currentImage');
                var counterEl = document.getElementById('imageCounter');
                var prevBtn = document.getElementById('prevImage');
                var nextBtn = document.getElementById('nextImage');

                function render() {
                        imgEl.src = images[current];
                        counterEl.textContent = (current + 1) + ' / ' + images.length;
                }

                imgEl.onerror = function () {
                        this.onerror = null;
                        this.src = "{{ $placeholderImage }}";
                };

                prevBtn.addEventListener('click', function () {
                        current = (current - 1 + images.length) % images.length;
                        render();
                });

                nextBtn.addEventListener('click', function () {
                        current = (current + 1) % images.length;
                        render();
                });

                render();
        });
</script>
<script>
(function () {
    const openCameraBtn = document.getElementById('openCameraBtn');
    const capturePhotoBtn = document.getElementById('capturePhotoBtn');
    const stopCameraBtn = document.getElementById('stopCameraBtn');
    const cameraPreview = document.getElementById('cameraPreview');
    const cameraCanvas = document.getElementById('cameraCanvas');
    const cameraStatus = document.getElementById('cameraStatus');
    const capturedPhotos = document.getElementById('capturedPhotos');
    const imageInput = document.getElementById('images');

    if (!openCameraBtn || !capturePhotoBtn || !stopCameraBtn || !cameraPreview || !cameraCanvas || !cameraStatus || !capturedPhotos || !imageInput) {
        return;
    }

    let mediaStream = null;
    let capturedCount = 0;

    function setStatus(message, type = 'muted') {
        cameraStatus.className = `small text-${type} mt-2`;
        cameraStatus.textContent = message;
    }

    function updateButtons(isCameraActive) {
        capturePhotoBtn.disabled = !isCameraActive;
        stopCameraBtn.disabled = !isCameraActive;
        openCameraBtn.disabled = isCameraActive;
    }

    function appendCapturedFile(file) {
        const dataTransfer = new DataTransfer();
        Array.from(imageInput.files || []).forEach((existingFile) => dataTransfer.items.add(existingFile));
        dataTransfer.items.add(file);
        imageInput.files = dataTransfer.files;
    }

    async function openCamera() {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            setStatus('Camera access is not supported in this browser. Use the file picker instead.', 'danger');
            return;
        }

        try {
            mediaStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: { ideal: 'environment' } },
                audio: false,
            });

            cameraPreview.srcObject = mediaStream;
            cameraPreview.classList.remove('d-none');
            updateButtons(true);
            setStatus('Camera is open. Frame the item, then click Capture Photo.', 'success');
        } catch (error) {
            console.error(error);
            setStatus('Unable to open the camera. Check browser permissions and try again.', 'danger');
        }
    }

    function stopCamera() {
        if (mediaStream) {
            mediaStream.getTracks().forEach((track) => track.stop());
            mediaStream = null;
        }

        cameraPreview.srcObject = null;
        cameraPreview.classList.add('d-none');
        updateButtons(false);
        setStatus('Camera stopped.', 'muted');
    }

    async function capturePhoto() {
        if (!mediaStream) {
            setStatus('Open the camera first.', 'warning');
            return;
        }

        const [videoTrack] = mediaStream.getVideoTracks();
        const settings = videoTrack ? videoTrack.getSettings() : {};
        const width = cameraPreview.videoWidth || settings.width || 1280;
        const height = cameraPreview.videoHeight || settings.height || 720;

        cameraCanvas.width = width;
        cameraCanvas.height = height;

        const context = cameraCanvas.getContext('2d');
        context.drawImage(cameraPreview, 0, 0, width, height);

        cameraCanvas.toBlob((blob) => {
            if (!blob) {
                setStatus('Could not capture the photo. Please try again.', 'danger');
                return;
            }

            capturedCount += 1;
            const fileName = `camera-photo-${capturedCount}.jpg`;
            const file = new File([blob], fileName, { type: 'image/jpeg' });
            appendCapturedFile(file);

            const previewUrl = URL.createObjectURL(file);
            const wrapper = document.createElement('div');
            wrapper.className = 'border rounded bg-white p-1';
            wrapper.innerHTML = `
                <img src="${previewUrl}" alt="Captured photo ${capturedCount}" style="width: 90px; height: 90px; object-fit: cover;" class="rounded">
            `;
            capturedPhotos.appendChild(wrapper);

            setStatus('Photo captured and added to the upload list.', 'success');
        }, 'image/jpeg', 0.92);
    }

    openCameraBtn.addEventListener('click', openCamera);
    capturePhotoBtn.addEventListener('click', capturePhoto);
    stopCameraBtn.addEventListener('click', stopCamera);

    window.addEventListener('beforeunload', stopCamera);
})();
</script>
@endpush