@include('admin.partials.head')
@include('admin.partials.menu')
@include('admin.partials.notify')

<div class="container my-4">

  <h1 class="h4 mb-3">Novo Banner</h1>

  <div class="card shadow-sm">
    <div class="card-body">

      <form action="/admin/banners/store" method="post" class="needs-validation" novalidate enctype="multipart/form-data">
        @csrf
        @include('admin.banners.form')
      </form>

      @if ($errors->any())
        <div class="alert alert-danger mt-3">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li><?php echo $error; ?></li>
            @endforeach
          </ul>
        </div>
      @endif

    </div>
  </div>

</div>

@include('admin.partials.footer')
@include('admin.partials.scripts')
