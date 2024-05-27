<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('q');

        // Mendapatkan jumlah item per halaman dari URL, default 10 jika tidak disediakan
        $perPage = $request->input('per_page', 10);

        // Query janji temu dengan fitur pencarian dan penomoran halaman
        $appointments = Appointment::query()
            ->when($search, function ($query, $search) {
                $query->where('date', 'like', "%$search%")
                    ->orWhere('time', 'like', "%$search%");
            })
            ->paginate($perPage);

        return response()->json($appointments, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required',
            'doctor_id' => 'required',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $appointment = Appointment::create($request->all());
        return response()->json($appointment, 201);
    }

    public function show($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }
        return response()->json($appointment, 200);
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }

        $request->validate([
            'patient_id' => 'nullable',
            'doctor_id' => 'nullable',
            'date' => 'nullable',
            'time' => 'nullable',
        ]);

        $appointment->update($request->all());
        return response()->json($appointment, 200);
    }

    public function destroy($id)
    {
        $appointment = Appointment::find($id);
        if (!$appointment) {
            return response()->json(['error' => 'Appointment not found'], 404);
        }

        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted successfully'], 200);
    }
}
