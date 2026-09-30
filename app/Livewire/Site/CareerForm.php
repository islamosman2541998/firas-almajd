<?php

namespace App\Livewire\Site;

use App\Models\JobApplication;
use App\Notifications\NewJobApplication;
use App\Services\SiteRepository;
use App\Support\AdminNotifier;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;

class CareerForm extends Component
{
    #[Locked]
    public array $labels = [];

    public string $name = '';

    public string $phone = '';

    public string $email = '';

    public string $job = '';

    public string $experience = '';

    public string $summary = '';

    public string $website = '';

    public ?string $whatsappUrl = null;

    public function mount(array $labels = []): void
    {
        $this->labels = $labels;
        $this->job = (string) ($this->jobs()->first()?->id ?? '');
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'phone' => ['required', 'string', 'regex:/^[+0-9 ()\-]{7,25}$/'],
            'email' => ['required', 'email', 'max:160'],
            'job' => ['required', Rule::in($this->jobs()->pluck('id')->map(fn ($id) => (string) $id)->all())],
            'experience' => ['required', 'integer', 'min:0', 'max:50'],
            'summary' => ['required', 'string', 'min:10', 'max:1000'],
        ];
    }

    protected function validationAttributes(): array
    {
        return collect(['name' => 'name_label', 'phone' => 'phone_label', 'email' => 'email_label', 'job' => 'job_label', 'experience' => 'experience_label', 'summary' => 'summary_label'])
            ->map(fn ($label) => tval($this->labels[$label] ?? ''))
            ->all();
    }

    public function submit(): void
    {
        if ($this->website !== '') {
            return;
        }

        $key = 'career-form:'.request()->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('summary', __('site.too_many_attempts'));

            return;
        }

        $data = $this->validate();
        RateLimiter::hit($key, 600);

        $job = $this->jobs()->firstWhere('id', (int) $data['job']);

        $application = JobApplication::create([
            'career_job_id' => $job?->id,
            'name' => trim($data['name']),
            'phone' => trim($data['phone']),
            'email' => trim($data['email']),
            'experience' => (int) $data['experience'],
            'summary' => trim($data['summary']),
            'locale' => app()->getLocale(),
            'ip' => request()->ip(),
        ]);

        AdminNotifier::send(new NewJobApplication($application));

        $text = fill_template(setting_t('general.whatsapp_career_template'), [
            'job' => $job?->title,
            'name' => $application->name,
            'phone' => $application->phone,
            'email' => $application->email,
            'experience' => $application->experience,
            'summary' => $application->summary,
        ]);

        $this->whatsappUrl = wa_url($text);
        $this->reset('name', 'phone', 'email', 'experience', 'summary');

        $this->dispatch('fam-lead', type: 'application', url: $this->whatsappUrl, open: (bool) ($this->labels['open_whatsapp'] ?? true));
    }

    protected function jobs()
    {
        return app(SiteRepository::class)->jobs();
    }

    public function render()
    {
        return view('livewire.site.career-form', ['jobs' => $this->jobs()]);
    }
}
