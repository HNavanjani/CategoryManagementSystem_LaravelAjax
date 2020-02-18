<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Company;
use App\Http\Resources\CompanyResource;
//use App\Http\Rules\ValidatePhoneNumber;
use App\Rules\ValidatePhoneNumber;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = Company::all();
        $coms = CompanyResource::collection($companies);
        $response = ['type'=>'success','data'=>$coms];
        //return response()-> json($response);
        return $this->apiResponse($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        /*
        $request->validate([
            'name' => 'required|unique'
            'email' => 'required'
            'address' => 'required'
            'phone_number' => 'required'
        ])
        */

        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:companies|max:100',
            'email' => 'required|email',
            'phone_number' => ['max:100', new ValidatePhoneNumber()]
        ]);

        if ($validator->fails()) {
            //dd($validator->errors());

            $response = ['type'=>'errors','data'=>$validator->errors()];
            //return response()->json($response);
            return $this->apiResponse($response);
        }

        $obj = Company::create($request -> all());

        //$company = Company::create($request -> all());
        $company = new CompanyResource($obj);

        $response = ['type'=>'success','data'=>$company];
        //return response()->json($response);
        return $this->apiResponse($response);

        //dd($company);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
