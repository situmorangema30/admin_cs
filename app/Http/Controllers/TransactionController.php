<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Transaction;

use App\Models\VoucherUsage;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;

class TransactionController extends Controller
{
    public function create()
    {
        return view('transaction.form', [
            'name' => 'transaction',
            'menu' => 'create',
            'title' => 'Create Transaction',
            'formAction' => 'store',
            'formInputs' => [
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'customer_email',
                    'label' => 'Customer Email',
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'customer_phone',
                    'label' => 'Customer Phone',
                ],
                [
                    'type' => 'hidden',
                    'width' => 12,
                    'name' => 'sub_total',
                    'label' => 'Sub Total',
                    'readonly' => true,
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'total_before_discount',
                    'label' => 'Total Before Discount',
                    'readonly' => true,
                ],
                [
                    'type' => 'hidden',
                    'width' => 12,
                    'name' => 'total',
                    'label' => 'Total',
                    'readonly' => true,
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'total_purchase',
                    'label' => 'Total Purchase',
                    'readonly' => true,
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'additional_request',
                    'label' => 'Additional Request',
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'payment_method',
                    'label' => 'Payment Method',
                ],
                [
                    'type' => 'check',
                    'width' => 12,
                    'name' => 'status',
                    'label' => 'Status',
                ],
            ],
            'vouchers' => Voucher::all(),
        ]);
    }

    public function store(Request $request)
    {
        $counter = 1;
        $transaction_id = 'TR' . date('Ymd');

        function changeToThreeDigit($num)
        {
            $num = strval($num);
            $len = strlen($num);

            if ($len === 1) {
                return '00' . $num;
            } else if ($len === 2) {
                return '0' . $num;
            }
            return $num;
        }

        while (true) {
            $num = changeToThreeDigit($counter);

            if (Transaction::where('transaction_id', $transaction_id . $num)->count() < 1) {
                $transaction_id = $transaction_id . $num;
                break;
            }

            $counter++;
        }

        $transactionsRules = $request->validate([
            'customer_name' => 'required|max:200',
            'customer_email' => 'required|max:100',
            'customer_phone' => 'nullable|max:45',
            'sub_total' => 'required|numeric',
            'total' => 'required|numeric',
            'total_purchase' => 'required|numeric',
            'additional_request' => 'nullable',
            'payment_method' => 'required',
            'status' => 'required|max:1|between:0,2|numeric',
        ]);

        $transactionsRules['transaction_id'] = $transaction_id;

        $transaction = Transaction::create($transactionsRules);

        $id = Transaction::where('transaction_id', $transaction_id)->first()->id;

        for ($i = 0; $i < count($request->product_id); $i++) {
            if ($request->qty[$i] !== "0") {
                TransactionDetail::create([
                    'transactions_id' => $id,
                    'products_id' => $request->product_id[$i],
                    'qty' => $request->qty[$i],
                    'price_satuan' => $request->price_satuan[$i],
                    'price_total' => $request->price_total[$i],
                    'price_purchase_satuan' => $request->price_purchase_satuan[$i],
                    'price_purchase_total' => $request->price_purchase_total[$i],
                ]);
            }
        }

        if ($request->voucher_id !== "0" && $request->voucher_id !== null) {
            VoucherUsage::create([
                'transactions_id' => $id,
                'vouchers_id' => $request->voucher_id,
                'discounted_value' => Voucher::where('id', $request->voucher_id)->get()->first()->disc_value,
            ]);
        }

        return redirect('/transactions');
    }

    public function data()
    {
        $data = Transaction::all();

        if (request()->has('transaction_id')) {
            $data = Transaction::where('transaction_id', 'LIKE', "%" . request('transaction_id') . "%")->get();
        } else if (request()->has('customer_name')) {
            $data = Transaction::where('customer_name', 'LIKE', "%" . request('customer_name') . "%")->get();
        } else if (request()->has('customer_email')) {
            $data = Transaction::where('customer_email', 'LIKE', "%" . request('customer_email') . "%")->get();
        } else if (request()->has('status')) {
            $data = Transaction::where('status', 'LIKE', request('status'))->get();
        } else if (request()->has('date')) {
            $queryDate = join("", explode('-', request('date')));
            $data = Transaction::where('transaction_id', 'LIKE', "%$queryDate%")->get();
        }

        return view('transaction.data', [
            'name' => 'transaction',
            'menu' => 'data',
            'title' => 'Table Transaction',
            'table' => [
                'title' => ['Transaction Id', 'Customer Name', 'Customer Email', 'Customer Phone', 'Sub Total', 'Total', "Total Purchase", 'Additional Request', 'Payment Method', 'Status'],
                'size' => [1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1,],
                'data' => $data,

            ],
        ]);
    }

    public function edit(Transaction $transaction)
    {
        return view('transaction.form', [
            'name' => 'transaction',
            'menu' => 'edit',
            'title' => 'Edit Transaction',
            'formAction' => 'update',
            'formInputs' => [
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'customer_name',
                    'label' => 'Customer Name',
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'customer_email',
                    'label' => 'Customer Email',
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'customer_phone',
                    'label' => 'Customer Phone',
                ],
                [
                    'type' => 'hidden',
                    'width' => 12,
                    'name' => 'sub_total',
                    'label' => 'Sub Total',
                    'readonly' => true,
                ],
                [
                    'type' => 'hidden',
                    'width' => 12,
                    'name' => 'total',
                    'label' => 'Total',
                    'readonly' => true,
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'total_before_discount',
                    'label' => 'Total Before Discount',
                    'readonly' => true,
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'total_purchase',
                    'label' => 'Total Purchase',
                    'readonly' => true,
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'additional_request',
                    'label' => 'Additional Request',
                ],
                [
                    'type' => 'text',
                    'width' => 12,
                    'name' => 'payment_method',
                    'label' => 'Payment Method',
                ],
                [
                    'type' => 'check',
                    'width' => 12,
                    'name' => 'status',
                    'label' => 'Status',
                ],
            ],
            'vouchers' => Voucher::all(),
            'transactions' => $transaction,
            'transaction_details' => TransactionDetail::where('transactions_id', $transaction->id)->get(),
        ]);
    }

    public function update(Request $request, Transaction $transaction)
    {
        $validated = $request->validate([
            'customer_name' => 'required|max:200',
            'customer_email' => 'required|max:100',
            'customer_phone' => 'nullable|max:45',
            'sub_total' => 'required|numeric',
            'total' => 'required|numeric',
            'additional_request' => 'nullable',
            'payment_method' => 'required',
            'status' => 'required|max:2|between:0,2|numeric',
        ]);

        Transaction::where('id', $transaction->id)->update($validated);

        if ($request->voucher_id !== "0") {
            if ($transaction->voucherUsage->first() === null) {
                VoucherUsage::create([
                    'transactions_id' => $transaction->id,
                    'vouchers_id' => $request->voucher_id,
                    'discounted_value' => Voucher::where('id', $request->voucher_id)->get()->first()->disc_value,
                ]);
            } else {
                VoucherUsage::where('id', $transaction->voucherUsage->first()->id)
                    ->update([
                        'vouchers_id' => $request->voucher_id,
                        'discounted_value' => Voucher::where('id', $request->voucher_id)->get()->first()->disc_value,
                    ]);
            }
        } else {
            VoucherUsage::destroy($transaction->voucherUsage->first()->id);
        }

        return redirect('/transactions');
    }

    public function delete(Request $request, Transaction $transaction)
    {
        Transaction::destroy($transaction->id);

        return redirect('/transactions');
    }
}
