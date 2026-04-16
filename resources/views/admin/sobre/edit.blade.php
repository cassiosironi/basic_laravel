@include('admin.partials.head')
@include('admin.partials.menu')

<div class="main-content my-4">

@include('admin.partials.notify') 

  <h1 class="h4 mb-3">Editar Sobre #<?php echo $sobre->id; ?></h1>

    <div class="glass-card">
      <div class="card-body">

        <form action="{{ route('admin.sobre.update') }}" method="post" enctype="multipart/form-data">
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