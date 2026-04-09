@include('admin.partials.head')
@include('admin.partials.menu')
@include('admin.partials.notify')

<div class="container my-4">

  <h1 class="h4 mb-3">Editar Sobre #<?php echo $sobre->id; ?></h1>

  <div class="card shadow-sm">
    <div class="card-body">

      <form action="{{ route('admin.sobre.update') }}" method="post">
        @csrf
        @include('admin.sobre.form')
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