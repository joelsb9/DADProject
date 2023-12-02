<?php

namespace App\Http\Controllers\api;

use App\Models\Vcard;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use App\Http\Resources\TransactionResource;
use App\Http\Requests\StoreTransactionRequest;
Use App\Http\Requests\UpdateTransactionRequest;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index()
    {
        return TransactionResource::collection(Transaction::withTrashed()->get());
    }

    public function show(Transaction $transaction)
    {
        return new TransactionResource($transaction);
    }

    public function store(StoreTransactionRequest $request)
    {
        $dataToSave = $request->validated();

        $transaction = new Transaction();
        $transaction->fill($dataToSave);

        $transaction_new = new Transaction();
        //Se for uma transação de vCard cria uma nova transação no cartao destino
        if($dataToSave['payment_type'] === 'VCARD') {
            //Define time at the same time as the original transaction
            $transaction_new->date = Carbon::now()->toDateString();
            $transaction_new->datetime = Carbon::now();
            $transaction->date = Carbon::now()->toDateString();
            $transaction->datetime = Carbon::now();

            $transaction_new->vcard = $dataToSave['payment_reference'];
            $transaction_new->type = 'C';
            $transaction_new->value = $dataToSave['value'];

            $vcard = Vcard::where('phone_number', $dataToSave['payment_reference'])->first();
            $transaction_new->old_balance = $vcard->balance;
            $transaction_new->new_balance = $vcard->balance - ($dataToSave['type'] === 'C' ? -$dataToSave['value'] : $dataToSave['value']);

            $transaction_new->payment_type = $dataToSave['payment_type'];
            $transaction_new->payment_reference = $dataToSave['vcard'];
            $transaction_new->pair_vcard = $dataToSave['vcard'];
            $transaction_new->pair_transaction = $transaction->id;
            if(isset($dataToSave['category_id'])){
                $transaction_new->category_id = $dataToSave['category_id'];
            }

            $transaction_new->save();
            $transaction->pair_transaction = $transaction_new->id;
            $transaction->pair_vcard = $dataToSave['payment_reference'];
        }
        else{
            $transaction->date = Carbon::now()->toDateString();
            $transaction->datetime = Carbon::now();
        }

        // $transaction->vcard = $dataToSave['vcard'];
        // $transaction->type = $dataToSave['type'];
        // $transaction->value = $dataToSave['value'];
        // $transaction->payment_type = $dataToSave['payment_type'];
        // $transaction->description = $dataToSave['description'];
        // $transaction->category_id = $dataToSave['category_id'];
        // $transaction->payment_reference = $dataToSave['payment_reference'];

        // Calculate old_balance and new_balance
        $vcard = Vcard::where('phone_number', $dataToSave['vcard'])->first();
        $transaction->old_balance = $vcard->balance;
        $transaction->new_balance = $vcard->balance - ($dataToSave['type'] === 'C' ? -$dataToSave['value'] : $dataToSave['value']);

        $transaction->save();
        return new TransactionResource($transaction);
    }

    public function update(UpdateTransactionRequest $request, Transaction $transaction)
    {
        $dataToSave = $request->validated();

        $transaction->fill($dataToSave);

        $transaction->save();
        return new TransactionResource($transaction);
    }
    public function delete(Transaction $transaction)
    {
        // Soft delete the transaction
        $transaction->delete();

        return response()->json(['message' => 'Transaction deleted successfully']);
    }

    public function restore($transactionId)
    {
        // Restore a soft-deleted transaction
        $transaction = Transaction::withTrashed()->find($transactionId);
        $transaction->restore();

        return response()->json(['message' => 'Transaction restored successfully']);
    }
    public function getTransactionsForVcard(Vcard $vcard)
    {
        // Retrieve all transactions associated with the given vCard
        $transactions = $vcard->transactions()->get();

        return TransactionResource::collection($transactions);
    }
}
