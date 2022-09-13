<x-dashboard.layout :name="$name" :menu="$menu">

    <div class="d-flex align-items-start">
        <form class="d-none d-sm-inline-block form-inline mb-3 mw-100 navbar-search"
            action="{{ url(request()->path()) }}">
            <div class="input-group">
                <input type="text" class="form-control border-0 small" placeholder="Search for name" aria-label="Search"
                    aria-describedby="basic-addon2" name="name"
                    value="@if (request()->has('name')) {{ request('name') }} @endif">

                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>

        <form class="d-none d-sm-inline-block form-inline mx-2 mb-3 mw-100 navbar-search"
            action="{{ url(request()->path()) }}">
            <div class="input-group">
                <input type="text" class="form-control border-0 small" placeholder="Search for code"
                    aria-label="Search" aria-describedby="basic-addon2" name="code"
                    value="@if (request()->has('code')) {{ request('code') }} @endif">

                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>

        <a href="{{ request()->url() }}" class="btn btn-danger">Reset</a>
    </div>

    <x-dashboard.table :title="$title">
        <colgroup>
            @foreach ($table['size'] as $size)
                <col class="col-md-{{ $size }}">
            @endforeach
        </colgroup>
        <thead>
            <tr>
                @foreach ($table['title'] as $title)
                    <th>{{ $title }}@if ($title == 'Status')
                            <a href="{{ request()->has('status') && request('status') != 0 ? request()->url() . '?status=' . 0 : request()->url() . '?status=' . 1 }}"
                                style="color:rgba(0,0,0,0.4)">
                                <i class="fas fa-filter"
                                    @if (request()->has('status')) style="color: {{ request('status') == 0 ? 'rgba(255,0,0,0.4)' : 'rgba(0,255,0,0.4)' }}" @endif></i>
                            </a>
                        @endif
                    </th>
                @endforeach

                <th class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($table['data'] as $data)
                <tr>
                    <td>{{ $data->name }}</td>
                    <td>{{ $data->code }}</td>
                    <td>{{ $data->productCategory->category }}</td>
                    <td>{{ $data->price }}</td>
                    <td>{{ $data->purchase_price }}</td>
                    <td>{{ $data->status }}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <a href="/products/{{ $data->id }}/edit"
                                class="btn btn-warning btn-circle btn-sm mx-1">
                                <i class="fas fa-pen"></i>
                            </a>
                            <form action="/products/{{ $data->id }}/delete" method="post">
                                @csrf

                                @method('delete')

                                <button class="btn btn-danger btn-circle btn-sm mx-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-dashboard.table>

</x-dashboard.layout>
