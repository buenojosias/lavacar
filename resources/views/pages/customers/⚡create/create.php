<?php

use Livewire\Attributes\Title;
use Livewire\Component;

new #[Title('Cadastrar cliente')] class extends Component
{
    public $can_save = false;
    public $name = '';
    public $whatsapp = '';
    public $formattedWhatsapp = '';
    public $user_id = null;

    public function updatedWhatsapp()
    {
        $this->can_save = false;
        $this->resetValidation();
        $this->formattedWhatsapp = $this->formatWhatsapp();
        $existingGlobalCustomers = $this->findExistingGlobalCustomers();
        if ($existingGlobalCustomers) {
            if ($this->checkExistingCustomerInCompany($existingGlobalCustomers)) {
                $this->addError('whatsapp', 'Cliente já cadastrado(a) neste estabelecimento.');
                return;
            }
            
            if ($user = $this->checkIfUserExists($this->formattedWhatsapp)) {
                $this->user_id = $user->id;
                dump('Usuário existente. ID: ' . $user->id . ', Nome: ' . $user->name);
            }
        }
        $this->can_save = true;
    }

    public function formatWhatsapp(): string
    {
        $whatsapp = preg_replace('/\D/', '', $this->whatsapp);
        $formattedWhatsapp = '+55' . $whatsapp;
        return $formattedWhatsapp;
    }

    public function findExistingGlobalCustomers()
    {
        $whatsapp = $this->formattedWhatsapp;
        $existingGlobalCustomers = \App\Models\Customer::query()
            ->withoutGlobalScopes()
            ->select('id', 'company_id', 'user_id', 'name')
            ->with('user:id,name')
            ->where('whatsapp', $whatsapp)
            ->get();
        
        return $existingGlobalCustomers;
    }

    public function checkExistingCustomerInCompany($existingGlobalCustomers)
    {
        $ids = $existingGlobalCustomers->pluck('company_id');
        return in_array(auth()->user()->selected_company_id, $ids->toArray());
    }

    public function checkIfUserExists($whatsapp)
    {
        $user = \App\Models\User::where('whatsapp', $whatsapp)->first();
        return $user;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|min:6|max:180',
            'whatsapp' => 'required|string|regex:/^\(\d{2}\) \d{4,5}-\d{4}$/',
        ]);

        $data['user_id'] = $this->user_id ?? null;
        $data['company_id'] = auth()->user()->selected_company_id;
        $data['name'] = $this->name;
        $data['whatsapp'] = $this->formattedWhatsapp;
        $data['registered_by_user_id'] = auth()->id();

        $customer = \App\Models\Customer::create($data);
        if ($customer) {
            $this->reset();
            dd($customer);
        }
    }
};