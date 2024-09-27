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
        $contact = Contact::create($data);

        // Guardar relaciones
        $this->saveRelations($contact, $data);

        return $contact->load(['phones', 'emails', 'addresses']);
    }

    public function update($id, array $data)
    {
      $contact = Contact::findOrFail($id);
      $contact->update($data);

      // Actualizar relaciones
      if (isset($data['phones'])) {
        $contact->phones()->delete(); // O puedes actualizar los existentes sin borrarlos si es necesario
        foreach ($data['phones'] as $phone) {
            $contact->phones()->create(['phone_number' => $phone]);
        }
      }
  
      if (isset($data['emails'])) {
          $contact->emails()->delete();
          foreach ($data['emails'] as $email) {
              $contact->emails()->create(['email' => $email]);
          }
      }
  
      if (isset($data['addresses'])) {
          $contact->addresses()->delete();
          foreach ($data['addresses'] as $address) {
              $contact->addresses()->create($address);
          }
      }

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
        if (isset($data['phones'])) {
            foreach ($data['phones'] as $phone) {
                $contact->phones()->create(['phone_number' => $phone]);
            }
        }

        if (isset($data['emails'])) {
            foreach ($data['emails'] as $email) {
                $contact->emails()->create(['email' => $email]);
            }
        }

        if (isset($data['addresses'])) {
            foreach ($data['addresses'] as $address) {
                $contact->addresses()->create($address);
            }
        }
    }
}
