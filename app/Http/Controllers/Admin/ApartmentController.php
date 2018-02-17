<?php

namespace App\Http\Controllers\Admin;

use App\Apartment;
use App\Models\Auth\User\User;
use Illuminate\Http\Request;

class ApartmentController
{
    public function index(Request $request)
    {
        $apartments = Apartment::with('houses')->where('owner_id','!=','')->paginate(10);
        return view('admin.apartments', ['apartments' => $apartments]);
    }

    public function repeat(Apartment $apartment, Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.createApartments');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $apartment = new Apartment([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
            'owner_id' => '1',
            'location' => $request->get('location'),
        ]);

        $apartment->save();

        return redirect('/admin/apartments');
    }

    /**
     * Display the specified resource.
     *
     * @param Apartment $apartment
     * @return \Illuminate\Http\Response
     */
    public function show(Apartment $apartment)
    {
        return view('admin.apartments.show', ['apartment' => $apartment]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $apartment
     * @return \Illuminate\Http\Response
     */
    public function edit($apartment)
    {
        $apartment = Apartment::with('houses')->whereIn('id',$apartment);

        return view('admin.apartments.edit', compact('apartment','id'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
ID	Name 	Description 	Location	Actions
1	Siwaka	Hostel	Keri Road	￼
2	cxzc	xczxc	xczxczxczxc	￼
3	xc\	\x	\xc	￼

     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $apartment = Apartment::find($id);
        $apartment->name = $request->get('name');
        $apartment->description = $request->get('description');
        $apartment->location = $request->get('location');
        $apartment->save();

        return redirect('/admin/apartments');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $apartment = Apartment::find($id);
        $apartment->delete();

        return redirect('/admin/apartments');
    }
}