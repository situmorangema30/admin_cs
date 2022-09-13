<x-dashboard.layout :name="$name" :menu="$menu">

    <x-dashboard.table :title="$title">
        <colgroup>
            @foreach ($table['size'] as $size)
                <col class="col-md-{{ $size }}">
            @endforeach
        </colgroup>
        <thead>
            <tr>
                @foreach ($table['title'] as $title)
                    <th>{{ $title }}</th>
                @endforeach

                <th class="text-center">Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($table['data'] as $data)
                <tr>
                    <td>{{ $data->code }}</td>
                    <td>{{ $data->type }}</td>
                    <td>{{ $data->disc_value }}</td>
                    <td>{{ $data->start_date }}</td>
                    <td>{{ $data->end_date }}</td>
                    <td>{{ $data->status }}</td>
                    <td class="d-flex justify-content-center">
                        <a href="/vouchers/{{ $data->id }}/edit" class="btn btn-warning btn-circle btn-sm mx-1">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form action="/vouchers/{{ $data->id }}/delete" method="post">
                            @csrf

                            @method('delete')

                            <button class="btn btn-danger btn-circle btn-sm mx-1">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </x-dashboard.table>

</x-dashboard.layout>
