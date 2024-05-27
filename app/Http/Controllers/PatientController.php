<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        // Mendapatkan jumlah item per halaman dari URL, default 10 jika tidak disediakan
        $perPage = $request->input('per_page', 10);

        // Query pasien dengan fitur pencarian dan penomoran halaman
        $patients = Patient::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('gender', 'like', "%$search%")
                    ->orWhere('dob', 'like', "%$search%")
                    ->orWhere('address', 'like', "%$search%");
            })
            ->paginate($perPage);

        return response()->json($patients, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'gender' => 'required',
            'dob' => 'required|date',
            'address' => 'required',
        ]);

        $patient = Patient::create($request->all());
        return response()->json($patient, 201);
    }

    public function show($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }
        return response()->json($patient, 200);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $request->validate([
            'name' => 'nullable',
            'gender' => 'nullable',
            'dob' => 'nullable',
            'address' => 'nullable',
        ]);

        $patient->update($request->all());
        return response()->json($patient, 200);
    }

    public function destroy($id)
    {
        $patient = Patient::find($id);
        if (!$patient) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        $patient->delete();
        return response()->json(['message' => 'Patient deleted successfully'], 200);
    }
}
