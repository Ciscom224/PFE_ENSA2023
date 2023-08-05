<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Patient;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $patients = Patient::all();
        return response()->json([
            'patients' => $patients,
            'status' => 1
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validation = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birth_day' => ['required', 'date', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . Patient::class],
        ]);

        if ($validation) {
            $patient = new Patient;
            $patient->id = htmlspecialchars($request->ID);
            $patient->email = htmlspecialchars($request->email);
            $patient->first_name = htmlspecialchars($request->first_name);
            $patient->last_name = htmlspecialchars($request->last_name);
            $patient->regDate = Carbon::now();
            $patient->birth_day = htmlspecialchars($request->birth_day);
            $patient->adress = htmlspecialchars($request->address);
            $patient->city = htmlspecialchars($request->city);
            $patient->phone = htmlspecialchars($request->phone);
            $patient->gender = htmlspecialchars($request->gender);
            $patient->blood_group = htmlspecialchars($request->blood_group);
            $patient->allergies = htmlspecialchars(implode(',', $request->allergies));
            $patient->profil_image = 'default';
            $patient->save();

            return response()->json([
                'status' => 1,
                'message' => "Patient ajoute !!!"
            ]);
        } else {
            return response()->json([
                'status' => 0,
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($search)
    {

        $search = $search == "none" ? "" : $search;

        $patients = Patient::when(!empty($search), function ($query) use ($search) {
            $query->where('id', 'LIKE', '%' . $search . '%')
                ->orWhere('first_name', 'LIKE', '%' . $search . '%')
                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                ->orWhere('blood_group', 'LIKE', '%' . strtoupper($search) . '%')
                ->orWhere('adress', 'LIKE', '%' . $search . '%')
                ->orWhere('gender', 'LIKE', '%' .strtoupper( $search) . '%')
                ->orWhere('city', 'LIKE', '%' . $search . '%');
        })
            ->select("id as patient_id", "email","first_name", "last_name", "phone", "gender", "blood_group", "birth_day", "adress","city")
            ->get();
        return response()->json([
            "patients" => $patients,
            "count"=>count($patients),
            "message" => $search
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //

        $patient=Patient::findOrFail($id);
        $patient->first_name=htmlspecialchars($request->first_name);
        $patient->last_name=htmlspecialchars($request->last_name);
        $patient->birth_day=htmlspecialchars($request->birth_day);
        $patient->adress=htmlspecialchars($request->adress);
        $patient->city=htmlspecialchars($request->city);
        $patient->blood_group=htmlspecialchars($request->blood_group);
        $patient->save();

        return response()->json([
            'message' => "Mise a jour du patient : ".$id." reussi...",
            'status' => 1,
        ]);
    }

    public function getPatient(string $id)
    {
        $data = DB::table('patients')->where('patient_id', 'LIKE', $id)->first();
        return response()->json([
            'patient' => $data
        ]);
    }
    public function saveQForm(Request $request)
    {
        return response()->json(["all" => $request->all()]);
        $header = $request->input('header');
        $content = $request->input('content');

        // Generate PDF using puppeteer or other libraries
        $pdf = PDF::loadView('pdf.qForm', ['header' => $header, 'content' => $content]);

        // Save PDF to storage/app/public or other suitable location
        $pdfPath = storage_path('app/public/documents/document.pdf');
        File::put($pdfPath, $pdf->output());

        return response()->json(['message' => 'PDF enregistré avec succès !'], 200);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Patient::where('id', $id)->delete();
        return response()->json([
            'message' => "Patient supprimé success..."
        ]);
    }
}
