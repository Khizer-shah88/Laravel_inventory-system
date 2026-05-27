@extends('layouts.app')

@section('page_title', 'Item Registration')

@section('content')

@if($errors->any())
  <div class="alert alert-danger">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" id="itemCreateForm">
    @csrf

<div class="row mb-3">
  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="Barcode">Barcode</span>
      <input type="text" class="form-control" id="Barcode" name="Barcode"
             value="{{ old('Barcode') }}" placeholder="Enter barcode" aria-describedby="Barcode">
    </div>
  </div>

  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="Category">Category</span>
      <input type="text" class="form-control" id="Category" name="Category"
             value="{{ old('Category') }}" placeholder="Enter category" aria-describedby="Category">
    </div>
  </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="ItemName">ItemName</span>
      <input type="text" class="form-control" id="ItemName" name="ItemName"
             value="{{ old('ItemName') }}" placeholder="Enter item name" aria-describedby="ItemName" required>
    </div>
  </div>

  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="CompanyName">CompanyName</span>
      <input type="text" class="form-control" id="CompanyName" name="CompanyName"
             value="{{ old('CompanyName') }}" placeholder="Enter company name" aria-describedby="CompanyName">
    </div>
  </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="ItemNameUrdu">ItemNameUrdu</span>
      <input type="text" class="form-control" id="ItemNameUrdu" name="ItemNameUrdu"
             value="{{ old('ItemNameUrdu') }}" placeholder="اردو نام درج کریں" aria-describedby="ItemNameUrdu">
    </div>
  </div>
  
  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="PacketSize">PacketSize</span>
      <input type="text" class="form-control" id="PacketSize" name="PacketSize"
             value="{{ old('PacketSize') }}" placeholder="Enter packet size" aria-describedby="PacketSize">
    </div>
  </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="PriceCode">PriceCode</span>
      <input type="text" class="form-control" id="PriceCode" name="PriceCode"
             value="{{ old('PriceCode') }}" placeholder="Enter price code" aria-describedby="PriceCode">
    </div>
  </div>

  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="UPurprice">UPurprice</span>
      <input type="number" class="form-control" id="UPurprice" name="UPurprice"
             value="{{ old('UPurprice') }}" step="0.01" min="0" aria-describedby="UPurprice">
    </div>
  </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="USalprice">USalprice</span>
      <input type="number" class="form-control" id="USalprice" name="USalprice"
             value="{{ old('USalprice') }}" step="0.01" min="0" aria-describedby="USalprice">
    </div>
  </div>

  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="PPurprice">PPurprice</span>
      <input type="number" class="form-control" id="PPurprice" name="PPurprice"
             value="{{ old('PPurprice') }}" step="0.01" min="0" aria-describedby="PPurprice">
    </div>
  </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="WUprice">WUprice</span>
      <input type="number" class="form-control" id="WUprice" name="WUprice"
             value="{{ old('WUprice') }}" step="0.01" min="0" aria-describedby="WUprice">
    </div>
  </div>

  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="WPprice">WPprice</span>
      <input type="number" class="form-control" id="WPprice" name="WPprice"
             value="{{ old('WPprice') }}" step="0.01" min="0" aria-describedby="WPprice">
    </div>
  </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="EPprice">EPprice</span>
      <input type="number" class="form-control" id="EPprice" name="EPprice"
             value="{{ old('EPprice') }}" step="0.01" min="0" aria-describedby="EPprice">
    </div>
  </div>

  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="DPprice">DPprice</span>
      <input type="number" class="form-control" id="DPprice" name="DPprice"
             value="{{ old('DPprice') }}" step="0.01" min="0" aria-describedby="DPprice">
    </div>
  </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="DUprice">DUprice</span>
      <input type="number" class="form-control" id="DUprice" name="DUprice"
             value="{{ old('DUprice') }}" step="0.01" min="0" aria-describedby="DUprice">
    </div>
  </div>

  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="RPprice">RPprice</span>
      <input type="number" class="form-control" id="RPprice" name="RPprice"
             value="{{ old('RPprice') }}" step="0.01" min="0" aria-describedby="RPprice">
    </div>
  </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-6 d-flex justify-content-center">
    <textarea class="form-control rounded-0" id="ItemDescription" name="ItemDescription" rows="3" 
              placeholder="Enter description" style="width: 350px;">{{ old('ItemDescription') }}</textarea>
  </div>

  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="PType">PType</span>
      <input type="text" class="form-control" id="PType" name="PType" value="{{ old('PType') }}" aria-describedby="PType">
    </div>
  </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="PTypeUrdu">PTypeUrdu</span>
      <input type="text" class="form-control" id="PTypeUrdu" name="PTypeUrdu" value="{{ old('PTypeUrdu') }}" aria-describedby="PTypeUrdu">
    </div>
  </div>

  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="UType">UType</span>
      <input type="text" class="form-control" id="UType" name="UType" value="{{ old('UType') }}" aria-describedby="UType">
    </div>
  </div>
</div>

<div class="row mb-3 justify-content-center">
  <div class="col-md-6 d-flex justify-content-center">
    <div class="input-group rounded-0" style="width: 350px;">
      <span class="input-group-text" id="UTypeUrdu">UTypeUrdu</span>
      <input type="text" class="form-control" id="UTypeUrdu" name="UTypeUrdu" value="{{ old('UTypeUrdu') }}" aria-describedby="UTypeUrdu">
    </div>
  </div>

  <div class="col-md-6 d-flex justify-content-center">
    <div style="width: 350px;">
      <div class="input-group rounded-0 mb-2">
        <span class="input-group-text" id="images">Item Images</span>
        <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*" capture="environment" aria-describedby="images">
      </div>

      <div class="border rounded-3 p-3 bg-light">
        <div class="d-flex flex-wrap gap-2 mb-2">
          <button type="button" class="btn btn-outline-primary btn-sm" id="chooseFilesBtn">
            <i class="fas fa-upload"></i> Upload Files
          </button>
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
  </div>
</div>

<!-- Actions remain as you had them -->
<div class="row">
  <div class="col-md-12 d-flex justify-content-end gap-2">
    <a href="{{ route('items.index') }}" class="btn btn-secondary me-md-2">
      <i class="fas fa-arrow-left"></i> Back to List
    </a>
    <button type="submit" class="btn btn-primary">
      <i class="fas fa-save"></i> Save Item
    </button>
    <button type="reset" class="btn btn-outline-secondary">
      <i class="fas fa-redo"></i> Reset
    </button>
  </div>
</div>

</form>

@push('scripts')
<script>
(function () {
  const itemCreateForm = document.getElementById('itemCreateForm');
  const openCameraBtn = document.getElementById('openCameraBtn');
  const capturePhotoBtn = document.getElementById('capturePhotoBtn');
  const stopCameraBtn = document.getElementById('stopCameraBtn');
  const chooseFilesBtn = document.getElementById('chooseFilesBtn');
  const cameraPreview = document.getElementById('cameraPreview');
  const cameraCanvas = document.getElementById('cameraCanvas');
  const cameraStatus = document.getElementById('cameraStatus');
  const capturedPhotos = document.getElementById('capturedPhotos');
  const imageInput = document.getElementById('images');

  if (!itemCreateForm || !openCameraBtn || !capturePhotoBtn || !stopCameraBtn || !chooseFilesBtn || !cameraPreview || !cameraCanvas || !cameraStatus || !capturedPhotos || !imageInput) {
    return;
  }

  let mediaStream = null;
  let capturedFiles = [];
  let capturedCount = 0;

  itemCreateForm.addEventListener('submit', async function (event) {
    if (!capturedFiles.length) {
      return;
    }

    event.preventDefault();

    const formData = new FormData(itemCreateForm);

    capturedFiles.forEach((file) => {
      formData.append('images[]', file, file.name);
    });

    try {
      const response = await fetch(itemCreateForm.action, {
        method: 'POST',
        body: formData,
      });

      if (response.redirected) {
        window.location.href = response.url;
        return;
      }

      if (!response.ok) {
        setStatus('Could not save the item with camera photos. Please try again.', 'danger');
        return;
      }

      window.location.reload();
    } catch (error) {
      console.error(error);
      setStatus('Network error while saving the item. Please try again.', 'danger');
    }
  });

  function syncCapturedFilesToInput() {
    const dataTransfer = new DataTransfer();

    Array.from(imageInput.files || []).forEach((existingFile) => {
      dataTransfer.items.add(existingFile);
    });

    capturedFiles.forEach((file) => {
      dataTransfer.items.add(file);
    });

    imageInput.files = dataTransfer.files;
  }

  function setStatus(message, type = 'muted') {
    cameraStatus.className = `small text-${type} mt-2`;
    cameraStatus.textContent = message;
  }

  function updateButtons(isCameraActive) {
    capturePhotoBtn.disabled = !isCameraActive;
    stopCameraBtn.disabled = !isCameraActive;
    openCameraBtn.disabled = isCameraActive;
  }

  function openFilePicker() {
    imageInput.click();
    setStatus('Choose image files from your device, or use the camera to capture a live photo.', 'muted');
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
      capturedFiles.push(file);
      syncCapturedFilesToInput();

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
  chooseFilesBtn.addEventListener('click', openFilePicker);
  imageInput.addEventListener('change', syncCapturedFilesToInput);

  window.addEventListener('beforeunload', stopCamera);
})();
</script>
@endpush

@endsection


