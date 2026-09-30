<?php

namespace App\Livewire\Site;

use App\Models\ContactMessage;
use App\Notifications\NewContactMessage;
use App\Services\SiteRepository;
use App\Support\AdminNotifier;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ContactForm extends Component
{
    /** Section labels (form texts) — locked so they cannot be tampered with. */
    #[Locked]
    public array $labels = [];

    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $service = '';

    public string $message = '';

    /** Honeypot: real visitors never fill it. */
    public string $website = '';

    public ?string $whatsappUrl = null;

    public function mount(array $labels = []): void
    {
        $this->labels = $labels;

        $requested = (string) request()->query('service');
        if ($this->services()->contains('slug', $requested)) {
            $this->service = $requested;
        }
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^[+0-9 ()\-]{7,25}$/'],
            'email' => ['nullable', 'email', 'max:254'],
            'service' => ['required', Rule::in($this->services()->pluck('slug')->all())],
            'message' => ['required', 'string', 'min:5', 'max:2000'],
        ];
    }

    protected function validationAttributes(): array
    {
        return [
            'name' => tval($this->labels['name_label'] ?? ''),
            'phone' => tval($this->labels['mobile_label'] ?? ''),
            'email' => tval($this->labels['form_email_label'] ?? ''),
            'service' => tval($this->labels['service_label'] ?? ''),
            'message' => tval($this->labels['message_label'] ?? ''),
        ];
    }

    public function submit(): void
    {
        if ($this->website !== '') {
            return;
        }

        $key = 'contact-form:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('message', __('site.too_many_attempts'));

            return;
        }

        $data = $this->validate();
        RateLimiter::hit($key, 600);

        $service = $this->services()->firstWhere('slug', $data['service']);

        $contact = ContactMessage::create([
            'name' => trim($data['name']),
            'phone' => trim($data['phone']),
            'email' => $data['email'] ?: null,
            'service_id' => $service?->id,
            'message' => trim($data['message']),
            'locale' => app()->getLocale(),
            'ip' => request()->ip(),
        ]);

        AdminNotifier::send(new NewContactMessage($contact));

        $text = fill_template(setting_t('general.whatsapp_contact_template'), [
            'name' => $contact->name,
            'phone' => $contact->phone,
            'email' => $contact->email,
            'service' => $service?->title,
            'message' => $contact->message,
        ]);

        $this->whatsappUrl = wa_url($text);
        $this->reset('name', 'phone', 'email', 'message');

        $this->dispatch('fam-lead', type: 'contact', url: $this->whatsappUrl, open: (bool) ($this->labels['open_whatsapp'] ?? true));
    }

    protected function services()
    {
        return app(SiteRepository::class)->services();
    }

    public function render()
    {
        return view('livewire.site.contact-form', ['services' => $this->services()]);
    }
}
