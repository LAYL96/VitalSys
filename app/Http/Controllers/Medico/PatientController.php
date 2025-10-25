<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::orderBy('id', 'desc')->paginate(10);
        return view('medico.patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('medico.patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dpi' => 'required|string|max:20|unique:patients,dpi',
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'birthdate' => 'nullable|date',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        Patient::create($validated);

        return redirect()->route('medico.patients.index')->with('success', 'Paciente registrado correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return view('medico.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('medico.patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'dpi' => 'required|string|max:20|unique:patients,dpi,' . $patient->id,
            'name' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'birthdate' => 'nullable|date',
            'phone' => 'nullable|string|max:15',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
        ]);

        $patient->update($validated);

        return redirect()->route('medico.patients.index')->with('success', 'Datos del paciente actualizados correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('medico.patients.index')->with('success', 'Paciente eliminado correctamente.');
    }
}
