<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Company;
use Validator,
    Carbon,
    DB;

class CompanyController extends Controller
{
    public function index(Request $request) {

        if(!isSuperAdmin()){
            return view('errors.403');
        }
        $query = Company::query();
        $companies = $query->orderBy('id', 'desc')->paginate(25);
        $companies->paginationSummery = getPaginationSummery($companies->total(), $companies->perPage(), $companies->currentPage());
        return view('companies.list', compact('companies'));
    }

    public function create(Request $request) {
        return view('companies.create');
    }

    public function edit(Request $request) {
        $company = Company::whereId($request->id)->first();
        return response()->json($company);
    }

    public function view($id) {
        return view('companies.view');
    }

    public function delete(Request $request) {
        
    }

    public function save(Request $request) {
        $rules = [
            'name' => 'required|max:255',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 400, 'error' => $validator->errors()]);
        } else {
            if (empty($request->id) || $request->id == 0) {
                //let's add
                $company = new Company();
                $company->created_by = $request->user()->id;
                $company->created_at = Carbon::now();
            } else {
                //let's edit
                $company = Company::find($request->id);
                $company->updated_by = $request->user()->id;
                $company->updated_at = Carbon::now();
            }

            $company->name = trim($request->name);
            $company->address = trim($request->address);
            $company->description = trim($request->description);

            if ($company->save()) {
                return response()->json(['status' => 200, 'type' => 'success', 'message' => 'Company has been successfully saved.']);
            } else {
                return response()->json(['status' => 404, 'type' => 'error', 'message' => 'Company has not successfully saved.']);
            }
        }
    }
}
