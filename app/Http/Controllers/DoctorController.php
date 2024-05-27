<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        $perPage = $request->input('per_page', 10);

        $doctors = Doctor::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('specialty', 'like', "%$search%")
                    ->orWhere('contact', 'like', "%$search%");
            })
            ->paginate($perPage);

        return response()->json($doctors, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'specialty' => 'required',
            'contact' => 'required',
        ]);

        $doctor = Doctor::create($request->all());
        return response()->json($doctor, 201);
    }

    public function show(Doctor $doctor)
    {
        return response()->json([
            'status' => true,
            'message' => 'Data Doctor',
            'data' => $doctor
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        $request->validate([
            'name' => 'nullable',
            'specialty' => 'nullable',
            'contact' => 'nullable',
        ]);

        $doctor->update($request->all());
        return response()->json($doctor, 200);
    }

    public function destroy($id)
    {
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        $doctor->delete();
        return response()->json(['message' => 'Doctor deleted successfully'], 200);
    }
}
