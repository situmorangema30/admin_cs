<x-dashboard.layout :name="$name" :menu="$menu">
    <x-dashboard.heading_form :title="$title" />

    <!-- Content -->
    <div class="col-xl-12 col-lg-12 p-0">
        <form class="user row"
            action="{{ isset($product) ? url("/products/$product->id/update") : url('/products/store') }}" method="post">
            @csrf

            @isset($product)
                @method('put')
            @endisset

            @foreach ($formInputs as $formInput)
                @if ($formInput['type'] === 'text')
                    <div class="mb-3 col-md-12">
                        <label for="{{ $formInput['name'] }}" class="form-label">{{ $formInput['label'] }}</label>
                        {{-- prettier-ignore --}}
                        <input type="text" class="form-control" id="{{ $formInput['name'] }}" name="{{ $formInput['name'] }}" placeholder="{{ $formInput['label'] }}" value="{{ isset($product) ? $product[$formInput['name']] : old($formInput['name']) }}">

                        @error($formInput['name'])
                            <x-all.flash_message :message="$message" />
                        @enderror
                    </div>
                @elseif ($formInput['type'] === 'number')
                    <div class="mb-3 col-md-12">
                        <label for="{{ $formInput['name'] }}" class="form-label">{{ $formInput['label'] }}</label>
                        {{-- prettier-ignore --}}
                            <input type="number" class="form-control" id="{{ $formInput['name'] }}" name="{{ $formInput['name'] }}" placeholder="{{ $formInput['label'] }}" value="{{ isset($product) ? $product[$formInput['name']] : old($formInput['name']) }}">

                        @error($formInput['name'])
                            <x-all.flash_message :message="$message" />
                        @enderror
                    </div>
                @elseif($formInput['type'] === 'select')
                    <div class="mb-3 col-md-12">
                        <label for="{{ $formInput['name'] }}" class="form-label">{{ $formInput['label'] }}</label>
                        <select id="{{ $formInput['name'] }}" name="{{ $formInput['name'] }}" class="custom-select">
                            <option value="" @if (!isset($product)) selected @endif>Choose One</option>
                            @foreach ($formInput['options'] as $option)
                                <option value="{{ $option->id }}" @if (isset($product) && $option->id === $product->product_categories_id) selected @endif>
                                    {{ $option->category }}
                                </option>
                            @endforeach
                        </select>

                        @error($formInput['name'])
                            <x-all.flash_message :message="$message" />
                        @enderror
                    </div>
                @elseif($formInput['type'] === 'textarea')
                    <div class="mb-3 col-md-12">
                        <label for="{{ $formInput['name'] }}" class="form-label">{{ $formInput['label'] }}</label>
                        {{-- prettier-ignore --}}
                        <textarea class="form-control" id="{{ $formInput['name'] }}" rows="{{ ($formInput['name'] === 'description') ? 5 : 2}}" name="{{ $formInput['name'] }}">{{ isset($product) ? $product[$formInput['name']] : old($formInput['name']) }}</textarea>

                        @error($formInput['name'])
                            <x-all.flash_message :message="$message" />
                        @enderror
                    </div>
                @elseif($formInput['type'] === 'check')
                    <div class="mb-3 col-md-3 d-flex flex-column align-items-start justify-content-start">
                        <label class="form-label">{{ $formInput['label'] }}</label>
                        <div>
                            @for ($i = 0; $i < 2; $i++)
                                <div class="form-check form-check-inline">
                                    {{-- prettier-ignore --}}
                                    <input class="form-check-input" type="radio" name="{{ $formInput['name'] }}" id="{{ $formInput['name'] . $i }}" value="{{ $i }}" @if(isset($product) && $i === $product[$formInput['name']]) checked @endif @if($formInput['name'] === "status" && $i === 1) checked @endif>
                                    <label class="form-check-label" for="{{ $formInput['name'] . $i }}">
                                        {{ $i === 0 ? 'Tidak Aktif' : 'Aktif' }}
                                    </label>
                                </div>
                            @endfor
                        </div>

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
