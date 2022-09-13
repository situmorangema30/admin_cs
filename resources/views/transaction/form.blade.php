<x-dashboard.layout :name="$name" :menu="$menu">
    <x-dashboard.heading_form :title="$title" />

    <!-- Content -->
    <div class="col-xl-12 col-lg-12 p-0">
        {{-- prettier-ignore --}}
        <form class="user row @isset($transactions) edit-transaction @endisset" action="{{ isset($transactions) ? url("/transactions/$transactions->id/update") : url('/transactions/store') }}" method="post" data-id="@isset($transactions){{ $transactions->id }}@endisset">
        @csrf

        @isset($transactions)
            @method('put')
        @endisset

        @if ($menu === 'create')
            <div class="mb-3
            col-md-3">
                <select id="products_id_select" class="custom-select">
                </select>
            </div>

            <div class="mb-3 col-md-2">
                <input type="number" class="form-control" id="add-product-qty" placeholder="Quantity">
            </div>

            <div class="mb-3 col-md-3">
                <button type="button" class="btn btn-primary" onclick="addProduct()">Add Product</button>
            </div>
        @endif

        <div class="mb-3 col-md-12">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <colgroup>
                        <col class="col-md-4">
                        @if ($menu === 'edit')
                            <col class="col-md-4">
                            <col class="col-md-4">
                        @endif
                        @if ($menu === 'create')
                            <col class="col-md-3">
                            <col class="col-md-3">
                            <col class="col-md-2">
                        @endif
                    </colgroup>
                    <thead>
                        <tr>
                            <th class="text-center">Products Name</th>
                            <th class="text-center">Quantity</th>
                            <th class="text-center">Sub Total</th>
                            @if ($menu === 'create')
                                <th class="text-center">Action</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody id="table-products-tbody">
                        @isset($transaction_details)
                            @foreach ($transaction_details as $product)
                                <tr>
                                    <td>
                                        {{-- prettier-ignore --}}
                                        <input type="hidden" class="form-control" id="transactions_id" name="transactions_id[]" value="{{ $product->id }}">
                                        {{-- prettier-ignore --}}
                                        <input type="hidden" class="form-control" id="product_id" name="product_id[]" value="{{ $product->product->id }}">
                                        {{-- prettier-ignore --}}
                                        <input type="text" class="form-control" id="product_name" name="product_name[]" value="{{ $product->product->name }}" readonly>
                                    </td>
                                    <td>
                                        {{-- prettier-ignore --}}
                                        <input type="number" class="form-control" id="qty" name="qty[]" onchange="updatePrice()" onkeydown="updatePrice()" value="{{ $product->qty }}" >
                                    </td>
                                    <td>
                                        {{-- prettier-ignore --}}
                                        <input type="hidden" class="form-control" id="price_satuan" name="price_satuan[]" value="{{ $product->price_satuan }}">
                                        {{-- prettier-ignore --}}
                                        <input type="hidden" class="form-control" id="price_total" name="price_total[]" value="{{ $product->price_total }}">
                                        {{-- prettier-ignore --}}
                                        <input type="hidden" class="form-control" id="price_purchase_satuan" name="price_purchase_satuan[]" value="{{ $product->price_purchase_satuan }}">
                                        {{-- prettier-ignore --}}
                                        <input type="text" class="form-control" id="price_purchase_total" name="price_purchase_total[]" value="{{ $product->price_purchase_total }}" readonly>
                                    </td>
                                </tr>
                            @endforeach
                        @endisset
                        <tr data-keterangan="no">
                            <td class="text-center font-weight-bold" colspan="2">Total</td>
                            <td id="add-product-total" class="text-center font-weight-bold">0</td>
                            @if (!isset($transaction_details))
                                <td></td>
                            @endisset
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mb-3 col-md-4">
        <label for="voucher_id" class="form-label">Voucher</label>
        <select id="voucher_id" class="custom-select" name="voucher_id" onchange="updateTotalPrice()"
            @if ($menu === 'create') disabled @endif>
        </select>
    </div>

    @foreach ($formInputs as $formInput)
        @if ($formInput['type'] === 'text')
            <div class="mb-3 col-md-{{ $formInput['width'] }}">
                <label for="{{ $formInput['name'] }}" class="form-label">{{ $formInput['label'] }}</label>
                {{-- prettier-ignore --}}
                        <input type="text" class="form-control" id="{{ $formInput['name'] }}" name="{{ $formInput['name'] }}" placeholder="{{ $formInput['label'] }}" value="{{ isset($transactions) ? $transactions[$formInput['name']] : old($formInput['name']) }}" @isset($formInput['readonly']) readonly @endisset>

                @error($formInput['name'])
                    <x-all.flash_message :message="$message" />
                @enderror
            </div>
        @elseif ($formInput['type'] === 'hidden')
            {{-- prettier-ignore --}}
                    <input type="hidden" class="form-control" id="{{ $formInput['name'] }}" name="{{ $formInput['name'] }}" placeholder="{{ $formInput['label'] }}" value="{{ isset($transactions) ? $transactions[$formInput['name']] : old($formInput['name']) }}" @isset($formInput['readonly']) readonly @endisset>
        @elseif($formInput['type'] === 'check')
            <div
                class="mb-3 col-md-{{ $formInput['width'] }} d-flex flex-column align-items-start justify-content-start">
                <label class="form-label">{{ $formInput['label'] }}</label>
                <div>
                    @for ($i = 0; $i < 3; $i++)
                        <div class="form-check form-check-inline">
                            {{-- prettier-ignore --}}
                                    <input class="form-check-input" type="radio" name="{{ $formInput['name'] }}" id="{{ $formInput['name'] . $i }}" value="{{ $i }}" @if ((isset($transactions) && $i === $transactions[$formInput['name']])) checked @endif>
                            <label class="form-check-label" for="{{ $formInput['name'] . $i }}">
                                {{ $i !== 0 ? ($i === 1 ? 'Pending' : 'Done / Paid') : 'Cancelled' }}
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

    <button type="submit" class="btn btn-success btn-user btn-block">
        {{ $title }}
    </button>
    </form>
</div>

@if ($menu === 'edit')
    <script src="{{ asset('/js/edit-transaction.js') }}"></script>
@else
    <script src="{{ asset('/js/create-transaction.js') }}"></script>
@endif
</x-dashboard.layout>
