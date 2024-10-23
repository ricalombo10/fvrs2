<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\RecordViolation;
use App\Models\Violator;
use App\Models\Referral;
use App\Models\History;
use Illuminate\Http\Request;

class RecordViolationController extends Controller
{
    // Method to show the create violation form
    public function recordviolation()
    {
        // Fetch all referrals to be displayed in the violation form
        $referrals = Referral::all();

        // Pass the referrals to the view
        return view('violation.create', compact('referrals')); // Adjust the view name if necessary
    }


    public function edits($id)
    {
        // Fetch all referrals to be displayed in the violation form
        $referrals = Referral::findOrFail($id);

        // Pass the referrals to the view
        return view('violation.create', compact('referrals')); // Adjust the view name if necessary
    }


    // Method to store the violation record
   public function store(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'violation' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'date_of_violation' => 'required|date',
        'time_of_violation' => 'required|',
        'violators.*.violator' => 'required|string|max:255',
        'violators.*.sex' => 'required|string|in:Male,Female',
        'violators.*.address' => 'nullable|string|max:255',
    ]);

    // Create the record violation
    $recordViolation = RecordViolation::create([
        'violation' => $request->violation,
        'location' => $request->location,
        'date_of_violation' => $request->date_of_violation,
        'time_of_violation' => $request->time_of_violation,
    ]);

    // Save violators with the associated record violation ID
    foreach ($request->violators as $violatorData) {

        if (!empty($violatorData['violator'])) {
            $recordViolation->violators()->create(array_merge($violatorData, [
                'record_violations_id' => $recordViolation->id // Link the violator to the violation record
            ]));
        }
    }

    // Redirect to the list of violations with a success message
    return redirect()->route('violation.list')->with('success', 'Violation recorded successfully!');
}



    public function listviolation()
{
    // Fetch all violations with their associated violators
    $violations = RecordViolation::with('violators')->get();

    return view('violation.list', compact('violations'));

}


public function edit($id)
{
    $violation = RecordViolation::with('violators')->findOrFail($id);
    return view('violation.edit', compact('violation'));
}

public function update(Request $request, $id)
{
    $violation = RecordViolation::findOrFail($id);
    $violation->update($request->only(['violation', 'location', 'date_of_violation', 'time_of_violation']));

    // Update or create violators
    if ($request->has('violators')) {
        foreach ($request->violators as $violatorData) {
            // If there's an ID, update the violator
            if (isset($violatorData['id'])) {
                $violator = Violator::findOrFail($violatorData['id']);
                $violator->update($violatorData);
            } else {
                // Create new violator if no ID is set (this part is optional based on your requirements)
                $violation->violators()->create($violatorData);
            }
        }
    }

    return redirect()->route('violation.list')->with('success', 'Record updated successfully');
}

public function search(Request $request)
{
    $query = $request->input('query');

    // Fetch violators with related record violations based on the search query
    $violators = Violator::with('recordViolation')
        ->where('first_name', 'LIKE', "%{$query}%")
        ->orWhere('last_name', 'LIKE', "%{$query}%")
        ->orWhere('address', 'LIKE', "%{$query}%")
        ->get();

    return view('violation.search_results', compact('violators'));
}

public function finish($id)
{
    // Find the violation by ID
    $recordViolation = RecordViolation::with('violators')->findOrFail($id);

    // Check if there are violators associated with this record
    foreach ($recordViolation->violators as $violator) {
        // Create a history record for each violator
        History::create([
            'violation' => $recordViolation->violation,
            'location' => $recordViolation->location,
            'date_of_violation' => $recordViolation->date_of_violation,
            'time_of_violation' => $recordViolation->time_of_violation,
           'violator'=> $violator->violator,
            'sex' => $violator->sex,
            'address' => $violator->address,
        ]);
    }

    // Delete the violation
    $recordViolation->delete();

    // Redirect back with a success message
    return redirect()->back()->with('success', 'Violation moved to history successfully.');
}

public function list()
    {
        // Fetch all violations
        $violations = RecordViolation::with('violators')->get();

        // Pass the violations to the view
        return view('violation.list', compact('violations'));
    }

    public function history()
{
    // Assuming you have a History model that corresponds to your history table
    $history = History::all(); // You can customize this as needed

    return view('admin.history', compact('history')); // Make sure to change 'admin.history' to your actual view
}


public function showBarangaysWithViolations()
{
    // Fetch distinct barangays with violations
    $barangays = Violator::select('address')->distinct()->get();

    return view('violation.barangays', compact('barangays'));
}
}