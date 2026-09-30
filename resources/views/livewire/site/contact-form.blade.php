<form class="contact-form" id="projectForm" wire:submit="submit">
<h3>{{ tval($labels['form_title'] ?? '') }}</h3>
<div class="row g-4">
<div class="col-sm-6"><label for="fullName">{{ tval($labels['name_label'] ?? '') }}</label><input autocomplete="name" @class(['form-control', 'is-invalid' => $errors->has('name')]) id="fullName" maxlength="100" name="name" placeholder="{{ tval($labels['name_placeholder'] ?? '') }}" required wire:model="name"/>@error('name')<small class="field-error">{{ $message }}</small>@enderror</div>
<div class="col-sm-6"><label for="phone">{{ tval($labels['mobile_label'] ?? '') }}</label><input autocomplete="tel" @class(['form-control', 'is-invalid' => $errors->has('phone')]) dir="ltr" id="phone" maxlength="25" name="phone" pattern="[+0-9 \(\)\-]{7,25}" placeholder="+966 5X XXX XXXX" required type="tel" wire:model="phone"/>@error('phone')<small class="field-error">{{ $message }}</small>@enderror</div>
<div class="col-12"><label for="contactEmail">{{ tval($labels['form_email_label'] ?? '') }}</label><input autocomplete="email" @class(['form-control', 'is-invalid' => $errors->has('email')]) dir="ltr" id="contactEmail" maxlength="254" name="email" placeholder="name@example.com" required type="email" wire:model="email"/>@error('email')<small class="field-error">{{ $message }}</small>@enderror</div>
<div class="col-12"><label for="serviceSelect">{{ tval($labels['service_label'] ?? '') }}</label><select @class(['form-select', 'is-invalid' => $errors->has('service')]) id="serviceSelect" name="service" required wire:model="service"><option value="">{{ tval($labels['service_placeholder'] ?? '') }}</option>@foreach ($services as $item)<option value="{{ $item->slug }}">{{ $item->title }}</option>@endforeach</select>@error('service')<small class="field-error">{{ $message }}</small>@enderror</div>
<div class="col-12"><label for="message">{{ tval($labels['message_label'] ?? '') }}</label><textarea @class(['form-control', 'is-invalid' => $errors->has('message')]) id="message" maxlength="2000" name="message" placeholder="{{ tval($labels['message_placeholder'] ?? '') }}" required rows="3" wire:model="message"></textarea>@error('message')<small class="field-error">{{ $message }}</small>@enderror</div>
</div>
<div aria-hidden="true" class="hp-field"><label for="cf-website">Website</label><input autocomplete="off" id="cf-website" tabindex="-1" type="text" wire:model="website"/></div>
<p class="form-note">{{ tval($labels['form_note'] ?? '') }}</p>
<button class="button button-dark" type="submit" wire:loading.attr="disabled" wire:target="submit"><span>{{ tval($labels['submit_text'] ?? '') }}</span><span class="arrow" wire:loading.remove wire:target="submit"><x-site.arrow /></span><span aria-hidden="true" class="btn-spinner" wire:loading wire:target="submit"></span></button>
<p aria-live="polite" class="form-status" id="formStatus" role="status">@if ($whatsappUrl){{ tval($labels['success_text'] ?? '') }}@endif</p>
@if ($whatsappUrl)
<a class="dark-link" href="{{ $whatsappUrl }}" id="whatsappFallback" rel="noopener noreferrer" target="_blank">{{ tval($labels['fallback_text'] ?? '') }}</a>
@endif
</form>
