<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::orderBy('time', 'desc')->get();
        $response = [
            'message' => 'Success',
            'data' => $transactions,
        ];
        if (response()->json($response)->status() === 200) {
            return response()->json($response);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData =  $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
        ]);
        if (!$validatedData) {
            return response()->json([
                'message' => 'Validation failed',
                'data' => $validatedData,
            ], 400);
        }
        
        try {
            $transaction = Transaction::create($validatedData);
            $response = [
                'message' => 'Success',
                'data' => $transaction,
            ];
            if (response()->json($response)->status() === 200) {
                return response()->json($response);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $transaction = Transaction::find($id);
        $response = [
            'message' => 'Success',
            'data' => $transaction,
        ];
        if (response()->json($response)->status() === 200) {
            return response()->json($response);
        }else{
            return response()->json([
                'message' => 'Error',
                'data' => 'Transaction not found',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
        
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found',
            ], 404);
        }
        $validatedData =  $request->validate([
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric',
            'type' => 'required|in:income,expense',
        ]);
        if (!$validatedData) {
            return response()->json([
                'message' => 'Validation failed',
                'data' => $validatedData,
            ], 400);
        }
        try {
            $transaction->update($validatedData);
            $response = [
                'message' => 'Success',
                'data' => $transaction,
            ];
            if (response()->json($response)->status() === 200) {
                return response()->json($response);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'data' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::find($id);
        if (!$transaction) {
            return response()->json([
                'message' => 'Transaction not found',
            ], 404);
        }
        try {
            $transaction->delete();
            $response = [
                'message' => 'Success deleted',
            ];
            if (response()->json($response)->status() === 200) {
                return response()->json($response);
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error',
                'data' => $e->getMessage(),
            ], 500);
        }
    }
}
