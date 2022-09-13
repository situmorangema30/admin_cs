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
                    <td>{{ $data->category }}</td>
                    <td>{{ $data->description }}</td>
                    <td class="d-flex justify-content-center">
                        <a href="/product-categories/{{ $data->id }}/edit"
                            class="btn btn-warning btn-circle btn-sm mx-1">
                            <i class="fas fa-pen"></i>
                        </a>
                        <form action="/product-categories/{{ $data->id }}/delete" method="post">
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
