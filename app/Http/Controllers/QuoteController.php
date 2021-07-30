<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->user()->cannot('viewAny', Quote::class)) {
            abort(403);
        }
        return Quote::paginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:150|min:5',
            'email' => 'required|email|max:191',
            'phone' => 'string|max:50|min:6',
            'name' => 'required|string|max:120|min:3',
            'content' => 'required|string|min:10'
        ]);
        Quote::create($data);
        return response('', 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if ($request->user()->cannot('viewAny', Quote::class)) {
            abort(403);
        }
        return Quote::findOrFail($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        if ($request->user()->cannot('delete', Quote::class)) {
            abort(403);
        }
        $quote = Quote::findOrFail($id);
        $quote->delete();
    }
}
