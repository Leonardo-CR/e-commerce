<?php

namespace App\Livewire;

use Livewire\Component;

class AddressManager extends Component
{
    public $street, $colony, $city, $number, $state, $zip;
    public $showForm = false;

    protected $rules = [
        'street' => 'required|string|max:255',
        'colony' => 'required|string|max:255',
        'city'   => 'required|string|max:255',
        'number' => 'required|string|max:50',
        'state'  => 'required|string|max:255',
        'zip'    => 'required|string|max:10',
    ];

    public function toggleForm()
    {
        $this->showForm = !$this->showForm;
        if (!$this->showForm) $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['street', 'colony', 'city', 'number', 'state', 'zip']);
    }

    public function store()
    {
        $this->validate();

        $user = auth()->user();

        // If it's the first address, make it default
        $isFirst = $user->addresses()->where('eliminated', false)->count() === 0;

        \App\Models\Address::create([
            'street' => $this->street,
            'colony' => $this->colony,
            'city'   => $this->city,
            'number' => $this->number,
            'state'  => $this->state,
            'zip'    => $this->zip,
            'user_id'=> $user->id,
            'is_default' => $isFirst
        ]);

        $this->resetForm();
        $this->showForm = false;
        session()->flash('message', '¡Dirección guardada con éxito!');
    }

    public function setDefault($idAddress)
    {
        $user = auth()->user();
        
        // Reset others
        \App\Models\Address::where('user_id', $user->id)->update(['is_default' => false]);
        
        // Set new default
        \App\Models\Address::where('idAddress', $idAddress)
            ->where('user_id', $user->id)
            ->update(['is_default' => true]);
            
        session()->flash('message', 'Dirección predeterminada actualizada.');
    }

    public function delete($idAddress)
    {
        $address = \App\Models\Address::where('idAddress', $idAddress)
            ->where('user_id', auth()->id())
            ->first();

        if ($address) {
            $address->update(['eliminated' => true]);
            
            // If we deleted the default, pick another one
            if ($address->is_default) {
                $next = \App\Models\Address::where('user_id', auth()->id())
                    ->where('eliminated', false)
                    ->first();
                if ($next) $next->update(['is_default' => true]);
            }
        }
        
        session()->flash('message', 'Dirección eliminada.');
    }

    public function render()
    {
        $addresses = \App\Models\Address::where('user_id', auth()->id())
            ->where('eliminated', false)
            ->orderBy('is_default', 'desc')
            ->get();

        return view('livewire.address-manager', [
            'addresses' => $addresses
        ]);
    }
}
