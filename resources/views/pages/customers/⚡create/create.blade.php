<div>
    <div class="page-header">
        <h2>Cadastrar cliente</h2>
    </div>
    @cannot('isAdmin')
        <form wire:submit.prevent="save" class="space-y-4">
            <x-ts-input label="Nome" wire:model="name" required />
            <x-ts-input label="WhatsApp" wire:model.blur="whatsapp" x-mask="(99) 99999-9999" required />

            <x-ts-button type="submit" text="Salvar" :disabled="!$can_save" />
        </form>
    @else
        Ação permitida apenas a gestores dos estabelecimentos.
    @endcannot
</div>
