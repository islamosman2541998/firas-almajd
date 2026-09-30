<select class="form-select" wire:model.live="status" aria-label="{{ __('admin.common.status') }}">
    <option value="">{{ __('admin.common.all_statuses') }}</option>
    <option value="active">{{ __('admin.common.active') }}</option>
    <option value="inactive">{{ __('admin.common.inactive') }}</option>
</select>
