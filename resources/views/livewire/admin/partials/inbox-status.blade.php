@php($class = ['new' => 'is-danger', 'read' => 'is-info', 'reviewed' => 'is-info', 'replied' => 'is-success', 'shortlisted' => 'is-success', 'archived' => 'is-muted', 'rejected' => 'is-muted'][$status] ?? 'is-muted')
<span class="a-badge {{ $class }}">{{ __('admin.'.$group.'.statuses.'.$status) }}</span>
