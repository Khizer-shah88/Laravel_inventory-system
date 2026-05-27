<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('page_title', 'Laravel App')</title>

    <!-- CSRF Token for AJAX -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

@stack('styles')

</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand text-white" href="#">
    <i class="fa-solid fa-bolt"></i> Adnan Electric Store
  </a>

  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
          data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" 
          aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">

      {{-- If user_id == 2 → show only Boxes & Box Items --}}
      @if(session('user_id') == 2)

        <li class="nav-item">
          <a class="nav-link text-white" href="{{ route('box.index') }}">
            <i class="fa-solid fa-box"></i> Boxes
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="/box-items">
            <i class="fa-solid fa-search"></i> Search Box & Items
          </a>
        </li>

      @else
        {{-- All other users → full menu --}}
        <li class="nav-item active">
          <a class="nav-link text-white" href="/">
            <i class="fa-solid fa-house"></i> Home
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="/PurchaseInvoice">
            <i class="fa-solid fa-cart-plus"></i> Purchaser
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="/items">
            <i class="fa-solid fa-list"></i> Items
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="{{ route('box.index') }}">
            <i class="fa-solid fa-box"></i> Boxes
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="/box-items">
            <i class="fa-solid fa-search"></i> Search Box & Items
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="/SaleInvoice/create">
            <i class="fa-solid fa-user"></i> Cash Invoice
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="/cashbook">
            <i class="fa-solid fa-book"></i> Cash Book
          </a>
        </li>

        <li class="nav-item">
          <a class="nav-link text-white" href="#">
            <i class="fa-solid fa-file-lines"></i> Journal
          </a>
        </li>

        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" 
             data-bs-toggle="dropdown">
            <i class="fa-solid fa-chart-line"></i> Reports
          </a>

          <div class="dropdown-menu bg-dark">
            <a class="dropdown-item text-white" href="#">
              <i class="fa-solid fa-file"></i> Action
            </a>
            <a class="dropdown-item text-white" href="#">
              <i class="fa-solid fa-file"></i> Another Action
            </a>
            <a class="dropdown-item text-white" href="#">
              <i class="fa-solid fa-file"></i> Something Else
            </a>
          </div>
        </li>
      @endif

    </ul>
  </div>
</nav>

<!-- Main Content -->
<div class="container-fluid mt-4">
    @yield('content')
</div>

<!-- Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
