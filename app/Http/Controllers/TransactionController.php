<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Resources\Account;
use App\Resources\Category;
use App\Resources\Ledger;
use Illuminate\Support\Facades\Input;

class TransactionController extends Controller
{
    /**
     * Show the application dashboard to the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ledger = new Ledger;

        $accounts = Account::all();

        $categories = Category::all();

        return view('transaction.index', compact('ledger', 'accounts', 'categories'))
            ->nest('transactions', 'transaction._list', [
                'transactions' => $ledger->transactions(),
                'actionable' => true
            ]);
    }

    /**
     * Show an individual transaction to the user.
     *
     * @param  Transaction $transaction
     *
     * @return \Illuminate\Http\Response
     */
    public function show($transaction)
    {
        return response()->json($transaction);
    }

    /**
     * Create and store a new transaction.
     *
     * @return Redirect
     */
    public function store(StoreTransactionRequest $request)
    {
        if (Transaction::create(Input::all())) {
            return redirect()->back()->with('alerts.success', trans('crud.transactions.created'));
        }
        return redirect()->back()->with('alerts.danger', trans('crud.transactions.error'));
    }

    /**
     * Update an existing transaction with new data.
     *
     * @param  Transaction $transaction
     *
     * @return Redirect
     */
    public function update(UpdateTransactionRequest $request, $transaction)
    {
        if ($transaction->update(Input::all())) {
            return redirect()->back()->with('alerts.success', trans('crud.transactions.updated'));
        }
        return redirect()->back()->with('alerts.danger', trans('crud.transactions.error'));
    }

    /**
     * Delete a transaction by ID.
     *
     * @param  Transaction $transaction
     *
     * @return Redirect
     */
    public function destroy($transaction)
    {
        if ($transaction->delete()) {
            return redirect(route('transactions.index'))->with('alerts.success', trans('crud.transactions.deleted'));
        } else {
            return redirect()->back()->with('alerts.danger', trans('crud.transactions.error'));
        }
    }
}
