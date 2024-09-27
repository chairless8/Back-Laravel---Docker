<?php

namespace App\Repositories;

use App\Models\Contact;

class ContactRepository implements ContactRepositoryInterface
{
    public function getAll(array $filters = [])
    {
        $query = Contact::with(['phones', 'emails', 'addresses']);

        // Aplicar filtros si existen
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%")
                  ->orWhereHas('phones', function ($q2) use ($search) {
                      $q2->where('phone_number', 'like', "%{$search}%");
                  })
                  ->orWhereHas('emails', function ($q2) use ($search) {
                      $q2->where('email', 'like', "%{$search}%");
                  })
                  ->orWhereHas('addresses', function ($q2) use ($search) {
                      $q2->where('address_line', 'like', "%{$search}%")
                         ->orWhere('city', 'like', "%{$search}%")
                         ->orWhere('state', 'like', "%{$search}%")
                         ->orWhere('country', 'like', "%{$search}%");
                  });
            });
        }

        return $query->paginate(15);
    }

    public function findById($id)
    {
        return Contact::with(['phones', 'emails', 'addresses'])->findOrFail($id);
    }

    public function create(array $data)
    {
        // Crear el contacto principal
        $contactData = $data;
        unset($contactData['phones'], $contactData['emails'], $contactData['addresses']);

        $contact = Contact::create($contactData);

        // Guardar relaciones
        $this->saveRelations($contact, $data);

        return $contact->load(['phones', 'emails', 'addresses']);
    }

    public function update($id,  array $data)
    {
        $contact = Contact::findOrFail($id);
    
        // Actualizar el contacto principal
        $contactData = $data;
        unset($contactData['phones'], $contactData['emails'], $contactData['addresses'], $contactData['phones_to_delete'], $contactData['emails_to_delete'], $contactData['addresses_to_delete']);
        $contact->update($contactData);
    
        // Actualizar relaciones
        $this->updateRelations($contact, $data);
    
        return $contact->load(['phones', 'emails', 'addresses']);
    }    

    public function delete($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return true;
    }

    private function saveRelations(Contact $contact, array $data)
    {
        // Guardar teléfonos
        if (isset($data['phones'])) {
            foreach ($data['phones'] as $phoneData) {
                $contact->phones()->create([
                    'phone_number' => $phoneData['phone_number'],
                ]);
            }
        }

        // Guardar emails
        if (isset($data['emails'])) {
            foreach ($data['emails'] as $emailData) {
                $contact->emails()->create([
                    'email' => $emailData['email'],
                ]);
            }
        }

        // Guardar direcciones
        if (isset($data['addresses'])) {
            foreach ($data['addresses'] as $addressData) {
                $contact->addresses()->create($addressData);
            }
        }
    }

    private function updateRelations(Contact $contact, array $data)
    {
        // Eliminar teléfonos marcados para eliminación
        if (isset($data['phones_to_delete'])) {
            $contact->phones()->whereIn('id', $data['phones_to_delete'])->delete();
        }

        // Crear o actualizar teléfonos
        if (isset($data['phones'])) {
            foreach ($data['phones'] as $phoneData) {
                $contact->phones()->updateOrCreate(
                    ['id' => $phoneData['id'] ?? null],
                    ['phone_number' => $phoneData['phone_number']]
                );
            }
        }

        // Eliminar emails marcados para eliminación
        if (isset($data['emails_to_delete'])) {
            $contact->emails()->whereIn('id', $data['emails_to_delete'])->delete();
        }

        // Crear o actualizar emails
        if (isset($data['emails'])) {
            foreach ($data['emails'] as $emailData) {
                $contact->emails()->updateOrCreate(
                    ['id' => $emailData['id'] ?? null],
                    ['email' => $emailData['email']]
                );
            }
        }

        // Eliminar direcciones marcadas para eliminación
        if (isset($data['addresses_to_delete'])) {
            $contact->addresses()->whereIn('id', $data['addresses_to_delete'])->delete();
        }

        // Crear o actualizar direcciones
        if (isset($data['addresses'])) {
            foreach ($data['addresses'] as $addressData) {
                $contact->addresses()->updateOrCreate(
                    ['id' => $addressData['id'] ?? null],
                    $addressData
                );
            }
        }
    }
}
