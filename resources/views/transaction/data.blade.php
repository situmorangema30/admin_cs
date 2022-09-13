<x-dashboard.layout :name="$name" :menu="$menu">
    <div class="d-flex align-items-start">
        <form class="d-none d-sm-inline-block form-inline mb-3 mw-100 navbar-search"
            action="{{ url(request()->path()) }}">
            <div class="input-group">
                <input type="text" class="form-control border-0 small" placeholder="Search Transaction Id"
                    aria-label="Search" aria-describedby="basic-addon2" name="transaction_id"
                    value="@if (request()->has('transaction_id')) {{ request('transaction_id') }} @endif">

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
                <input type="text" class="form-control border-0 small" placeholder="Search Customer Name"
                    aria-label="Search" aria-describedby="basic-addon2" name="customer_name"
                    value="@if (request()->has('customer_name')) {{ request('customer_name') }} @endif">

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
                <input type="text" class="form-control border-0 small" placeholder="Search Customer Email"
                    aria-label="Search" aria-describedby="basic-addon2" name="customer_email"
                    value="@if (request()->has('customer_email')) {{ request('customer_email') }} @endif">

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
                <input type="date" class="form-control border-0 small" placeholder="Search Customer Email"
                    aria-label="Search" aria-describedby="basic-addon2" name="date">

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
                            @php
                                if (request()->has('status')) {
                                    $queryStatus = request('status') == 0 ? request()->url() . '?status=1' : (request('status') == 1 ? request()->url() . '?status=2' : request()->url() . '?status=0');
                                }
                            @endphp
                            <a href="@isset($queryStatus) {{ $queryStatus }} @endisset"
                                style="color:rgba(0,0,0,0.4)">
                                <i class="fas fa-filter"
                                    @if (request()->has('status')) style="color: {{ request('status') == 0 ? 'rgba(255,0,0,0.4)' : (request('status') == 1 ? 'rgba(255,255,0,0.4)' : 'rgba(0,255,0,0.4)') }}" @endif></i>
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
                    <td>{{ $data->transaction_id }}</td>
                    <td>{{ $data->customer_name }}</td>
                    <td>{{ $data->customer_email }}</td>
                    <td>{{ $data->customer_phone }}</td>
                    <td>{{ $data->sub_total }}</td>
                    <td>{{ $data->total }}</td>
                    <td>{{ $data->total_purchase }}</td>
                    <td>{{ $data->additional_request }}</td>
                    <td>{{ $data->payment_method }}</td>
                    <td>{{ $data->status }}</td>
                    <td>
                        <div class="d-flex justify-content-center">
                            @if ($data->status === 1)
                                <a href="/transactions/{{ $data->id }}/edit"
                                    class="btn btn-warning btn-circle btn-sm mx-1">
                                    <i class="fas fa-pen"></i>
                                </a>
                            @endif
                            <form action="/transactions/{{ $data->id }}/delete" method="post">
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
