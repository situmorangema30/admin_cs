@props(['title'])

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary">{{ $title }}</h6>

        <a href="{{ url(request()->url()) }}/create" class="btn btn-primary">
            <i class="fas fa-fw fa-plus"></i>
            Create
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                {{ $slot }}
            </table>
        </div>
    </div>
</div>
