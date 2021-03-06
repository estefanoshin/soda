<section id="productos">
<div class="card-columns">
    @foreach ( $productos as $producto )
<div class="card-deck-wrapper">
        <div class="card" style="width: 18rem">
        @if (Storage::disk('local')->has($producto->img))
        <div id="img-container">
            <img id="prod-img" class="card-img-top" src="{{ route('image', ['filename' => $producto->img]) }}" alt="Card image cap">
        </div>
        @endif
          <div class="card-body">
            <h5 class="card-title">{{ $producto->articulo }}</h5>
            <h5 class="card-title">{{ $producto->descripcion }}</h5>
            <p class="card-text">{{ $producto->tipo_tela}}</p>
            <p class="card-text">{{ $producto->colores}}</p>
            <p class="card-text">{{ $producto->talles }}</p>

            @if (Route::has('login'))
            @auth
            <p class="card-text">${{ $producto->precio }}</p>
            @else
            @endauth
            @endif

            @if (Route::has('login'))
            @auth
            <a href="{{ route('edit', ['edit_id' => $producto->id_art]) }}" class="btn btn-dark">Modificar</a>
            <a href="{{ route('destroy', ['destroy_id' => $producto->id_art]) }}" class="btn btn-dark">Borrar</a>
            @else
            @endauth
            @endif
          </div>
        </div>
    </div>
    @endforeach
</div>
</section>