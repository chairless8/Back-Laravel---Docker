<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ContactRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;

class ContactController extends Controller
{
    protected $contactRepository;

    public function __construct(ContactRepositoryInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only(['search']);
        $contacts = $this->contactRepository->getAll($filters);
        return response()->json($contacts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request)
    {
        // Validar los datos
        $data = $request->validated();
        $contact = $this->contactRepository->create($data);
        return response()->json($contact, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contact = $this->contactRepository->findById($id);
        return response()->json($contact);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactRequest $request, $id)
    {
        // Validar los datos
        $data = $request->validated();
        $contact = $this->contactRepository->update($id, $data);
        return response()->json($contact);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleteContact = $this->contactRepository->delete($id);
        if ($deleteContact) {
            return response()->json(['message' => 'Contact deleted']);
        } else {
            return response()->json(['message' => 'Failed to delete contact'], 500);
        }
    }
}
