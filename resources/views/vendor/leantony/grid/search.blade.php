<div class="col-md-{{ $colSize }}" style="display: none">
    <form method="GET" id="{{ $id }}" action="{{ $action }}"
          @foreach($dataAttributes as $k => $v)
          data-{{ $k }}={{ $v }}
            @endforeach
    >
        <div class="input-group mb-12">
            <input type="text" class="form-control" name="{{ $name }}"
                   placeholder="{{ $placeholder }}" value="{{ request($name) }}" required="required"
                   aria-label="search">
            <div class="input-group-append">
                <button class="btn btn-outline-primary" type="submit"><i class="fa fa-search"></i></button>
            </div>
        </div>
    </form>
</div>