<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function create()
    {
        return view('voucher.form', [
            'name' => 'voucher',
            'menu' => 'create',
            'title' => 'Create Voucher',
            'formAction' => 'store',
            'formInputs' => [
                [
                    'type' => 'text',
                    'name' => 'code',
                    'label' => 'Code',
                ],
                [
                    'type' => 'checkVoucher',
                    'name' => 'type',
                    'label' => 'Type',
                ],
                [
                    'type' => 'number',
                    'name' => 'disc_value',
                    'label' => 'Disc Value',
                ],
                [
                    'type' => 'date',
                    'name' => 'start_date',
                    'label' => 'Start Date',
                ],
                [
                    'type' => 'date',
                    'name' => 'end_date',
                    'label' => 'End Date',
                ],
                [
                    'type' => 'check',
                    'name' => 'status',
                    'label' => 'Status',
                ],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|max:50',
            'type' => 'required|max:2|between:1,2|integer|numeric',
            'disc_value' => 'required|integer|numeric',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
            'status' => 'required|max:1|between:0,1|integer|numeric',
        ]);

        $voucher = Voucher::create($validated);

        return redirect('/vouchers');
    }

    public function data()
    {
        return view('voucher.data', [
            'name' => 'voucher',
            'menu' => 'data',
            'title' => 'Table Voucher',
            'table' => [
                'title' => ['code', 'type', 'disc_value', 'start_date', 'end_date', 'status'],
                'size' => [2, 1, 2, 2, 2, 1, 2],
                'data' => Voucher::all(),

            ],
        ]);
    }

    public function edit(Voucher $voucher)
    {
        return view('voucher.form', [
            'name' => 'voucher',
            'menu' => 'edit',
            'title' => 'Edit Voucher',
            'formAction' => 'update',
            'formInputs' => [
                [
                    'type' => 'text',
                    'name' => 'code',
                    'label' => 'Code',
                ],
                [
                    'type' => 'checkVoucher',
                    'name' => 'type',
                    'label' => 'Type',
                ],
                [
                    'type' => 'number',
                    'name' => 'disc_value',
                    'label' => 'Disc Value',
                ],
                [
                    'type' => 'date',
                    'name' => 'start_date',
                    'label' => 'Start Date',
                ],
                [
                    'type' => 'date',
                    'name' => 'end_date',
                    'label' => 'End Date',
                ],
                [
                    'type' => 'check',
                    'name' => 'status',
                    'label' => 'Status',
                ],
            ],
            'voucher' => $voucher,
        ]);
    }

    public function update(Request $request, Voucher $voucher)
    {
        $validated = $request->validate([
            'code' => 'required|max:50',
            'type' => 'required|max:1|between:1,2|integer|numeric',
            'disc_value' => 'required|numeric',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date',
            'status' => 'required|max:1|between:0,1|integer|numeric',
        ]);

        Voucher::where('id', $voucher->id)->update($validated);

        return redirect('/vouchers');
    }

    public function delete(Request $request, Voucher $voucher)
    {
        Voucher::destroy($voucher->id);

        return redirect('/vouchers');
    }
}
