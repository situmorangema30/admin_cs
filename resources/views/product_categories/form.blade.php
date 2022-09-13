<x-dashboard.layout :name="$name" :menu="$menu">
    <x-dashboard.heading_form :title="$title" />

    <!-- Content -->
    <div class="col-xl-12 col-lg-12 p-0">
        <form class="user"
            action="{{ isset($productCategory) ? url("/product-categories/$productCategory->id/update") : url('/product-categories/store') }}"
            method="post">
            @csrf

            @isset($productCategory)
                @method('put')
            @endisset

            @foreach ($formInputs as $formInput)
                @if ($formInput['type'] === 'text')
                    <div class="mb-3 col-md-12">
                        <label for="{{ $formInput['name'] }}" class="form-label">{{ $formInput['label'] }}</label>
                        {{-- prettier-ignore --}}
                        <input type="text" class="form-control" id="{{ $formInput['name'] }}" name="{{ $formInput['name'] }}" placeholder="{{ $formInput['label'] }}" value="{{ isset($productCategory) ? $productCategory[$formInput['name']] : old($formInput['name']) }}">

                        @error($formInput['name'])
                            <x-all.flash_message :message="$message" />
                        @enderror
                    </div>
                @elseif($formInput['type'] === 'textarea')
                    <div class="mb-3 col-md-12">
                        <label for="{{ $formInput['name'] }}" class="form-label">{{ $formInput['label'] }}</label>
                        {{-- prettier-ignore --}}
                        <textarea class="form-control" id="{{ $formInput['name'] }}" rows="{{ ($formInput['name'] === 'description') ? 5 : 2}}" name="{{ $formInput['name'] }}">{{ isset($productCategory) ? $productCategory[$formInput['name']] : old($formInput['name']) }}</textarea>

                        @error($formInput['name'])
                            <x-all.flash_message :message="$message" />
                        @enderror
                    </div>
                @endif
            @endforeach

            <button type="submit" class="btn btn-primary btn-user btn-block">
                {{ $title }}
            </button>
        </form>
    </div>

</x-dashboard.layout>
