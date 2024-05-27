<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        // Mendapatkan jumlah item per halaman dari URL, default 10 jika tidak disediakan
        $perPage = $request->input('per_page', 10);

        // Query rekam medis dengan fitur pencarian dan penomoran halaman
        $medicalRecords = MedicalRecord::query()
            ->when($search, function ($query, $search) {
                $query->where('diagnosis', 'like', "%$search%")
                    ->orWhere('prescription', 'like', "%$search%");
            })
            ->paginate($perPage);

        return response()->json($medicalRecords, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'diagnosis' => 'required',
            'prescription' => 'required',
        ]);

        $medicalRecord = MedicalRecord::create($request->all());
        return response()->json($medicalRecord, 201);
    }

    public function show($id)
    {
        $medicalRecord = MedicalRecord::find($id);
        if (!$medicalRecord) {
            return response()->json(['error' => 'Medical Record not found'], 404);
        }
        return response()->json($medicalRecord, 200);
    }

    public function update(Request $request, $id)
    {
        $medicalRecord = MedicalRecord::find($id);
        if (!$medicalRecord) {
            return response()->json(['error' => 'Medical Record not found'], 404);
        }

        $request->validate([
            'patient_id' => 'nullable',
            'doctor_id' => 'nullable',
            'diagnosis' => 'nullable',
            'prescription' => 'nullable',
        ]);

        $medicalRecord->update($request->all());
        return response()->json($medicalRecord, 200);
    }

    public function destroy($id)
    {
        $medicalRecord = MedicalRecord::find($id);
        if (!$medicalRecord) {
            return response()->json(['error' => 'Medical Record not found'], 404);
        }

        $medicalRecord->delete();
        return response()->json(['message' => 'Medical Record deleted successfully'], 200);
    }
}
