<x-dashboard.layout :name="$name" :menu="$menu">
    <x-dashboard.heading_form :title="$title" />

    <!-- Content -->
    <div class="col-xl-12 col-lg-12 p-0">
        <form class="user row"
            action="{{ isset($voucher) ? url("/vouchers/$voucher->id/update") : url('/vouchers/store') }}" method="post">
            @csrf

            @isset($voucher)
                @method('put')
            @endisset

            @foreach ($formInputs as $formInput)
                @if ($formInput['type'] === 'text')
                    <div class="mb-3 col-md-12">
                        <label for="{{ $formInput['name'] }}" class="form-label">{{ $formInput['label'] }}</label>
                        {{-- prettier-ignore --}}
                        <input type="text" class="form-control" id="{{ $formInput['name'] }}" name="{{ $formInput['name'] }}" placeholder="{{ $formInput['label'] }}" value="{{ isset($voucher) ? $voucher[$formInput['name']] : old($formInput['name']) }}">

                        @error($formInput['name'])
                            <x-all.flash_message :message="$message" />
                        @enderror
                    </div>
                @elseif ($formInput['type'] === 'number')
                    <div class="mb-3 col-md-12">
                        <label for="{{ $formInput['name'] }}" class="form-label">{{ $formInput['label'] }}</label>
                        {{-- prettier-ignore --}}
                        <input type="number" class="form-control" id="{{ $formInput['name'] }}" name="{{ $formInput['name'] }}" placeholder="{{ $formInput['label'] }}" value="{{ isset($voucher) ? $voucher[$formInput['name']] : old($formInput['name']) }}" min="0" max="100">

                        @error($formInput['name'])
                            <x-all.flash_message :message="$message" />
                        @enderror
                    </div>
                @elseif($formInput['type'] === 'date')
                    <div class="mb-3 col-md-6">
                        <label for="{{ $formInput['name'] }}" class="form-label">{{ $formInput['label'] }}</label>
                        {{-- prettier-ignore --}}
                        <input type="date" class="form-control" id="{{ $formInput['name'] }}" name="{{ $formInput['name'] }}" placeholder="{{ $formInput['label'] }}" value="{{ isset($voucher) ? $voucher[$formInput['name']] : old($formInput['name']) }}">

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
                                    <input class="form-check-input" type="radio" name="{{ $formInput['name'] }}" id="{{ $formInput['name'] . $i }}" value="{{ $i }}" @if ((isset($voucher) && $i === $voucher[$formInput['name']])) checked @endif  @if($formInput['name'] == 'status') checked @endif>
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
                @elseif($formInput['type'] === 'checkVoucher')
                    <div class="mb-3 col-md-12 d-flex flex-column align-items-start justify-content-start">
                        <label class="form-label">{{ $formInput['label'] }}</label>
                        <div>
                            @for ($i = 1; $i < 3; $i++)
                                <div class="form-check form-check-inline">
                                    {{-- prettier-ignore --}}
                                    <input class="form-check-input" type="radio" name="{{ $formInput['name'] }}" id="{{ $formInput['name'] . $i }}" value="{{ $i }}" @if ((isset($voucher) && $i === $voucher[$formInput['name']])) checked @endif onclick="updateDiscValue({{ $i }})">
                                    <label class="form-check-label" for="{{ $formInput['name'] . $i }}">
                                        {{ $i === 1 ? 'Flat Discount' : 'Percent discount' }}
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

    <script>
        let discValueForm = document.querySelector('#disc_value')

        const updateDiscValue = (value) => {

            if (value === 1) {
                discValueForm.removeAttribute('max')
            } else {
                discValueForm.setAttribute('max', 100)
            }
        }
    </script>

</x-dashboard.layout>
