<form class="career-form" id="careerForm" wire:submit="submit">
<div class="career-form-grid">
<label><span>{{ tval($labels['name_label'] ?? '') }}</span><input autocomplete="name" @class(['form-control', 'is-invalid' => $errors->has('name')]) maxlength="100" name="name" required wire:model="name"/>@error('name')<small class="field-error">{{ $message }}</small>@enderror</label>
<label><span>{{ tval($labels['phone_label'] ?? '') }}</span><input autocomplete="tel" @class(['form-control', 'is-invalid' => $errors->has('phone')]) dir="ltr" maxlength="25" name="phone" required type="tel" wire:model="phone"/>@error('phone')<small class="field-error">{{ $message }}</small>@enderror</label>
<label><span>{{ tval($labels['email_label'] ?? '') }}</span><input autocomplete="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) dir="ltr" maxlength="160" name="email" required type="email" wire:model="email"/>@error('email')<small class="field-error">{{ $message }}</small>@enderror</label>
<label><span>{{ tval($labels['job_label'] ?? '') }}</span><select @class(['form-select', 'is-invalid' => $errors->has('job')]) id="careerJob" name="job" required wire:model="job">@foreach ($jobs as $item)<option value="{{ $item->id }}">{{ $item->title }}</option>@endforeach</select>@error('job')<small class="field-error">{{ $message }}</small>@enderror</label>
<label class="full"><span>{{ tval($labels['experience_label'] ?? '') }}</span><input @class(['form-control', 'is-invalid' => $errors->has('experience')]) max="50" min="0" name="experience" required type="number" wire:model="experience"/>@error('experience')<small class="field-error">{{ $message }}</small>@enderror</label>
<label class="full"><span>{{ tval($labels['summary_label'] ?? '') }}</span><textarea @class(['form-control', 'is-invalid' => $errors->has('summary')]) maxlength="1000" name="summary" required rows="4" wire:model="summary"></textarea>@error('summary')<small class="field-error">{{ $message }}</small>@enderror</label>
</div>
<div aria-hidden="true" class="hp-field"><label for="jf-website">Website</label><input autocomplete="off" id="jf-website" tabindex="-1" type="text" wire:model="website"/></div>
<button class="button button-gold" type="submit" wire:loading.attr="disabled" wire:target="submit"><span>{{ tval($labels['submit_text'] ?? '') }}</span><span aria-hidden="true" class="arrow" wire:loading.remove wire:target="submit"><x-site.arrow /></span><span aria-hidden="true" class="btn-spinner" wire:loading wire:target="submit"></span></button>
<p aria-live="polite" class="form-status" id="careerFormStatus" role="status">@if ($whatsappUrl){{ tval($labels['success_text'] ?? '') }}@endif</p>
@if ($whatsappUrl)
<a class="dark-link" href="{{ $whatsappUrl }}" rel="noopener noreferrer" target="_blank">WhatsApp</a>
@endif
</form>
