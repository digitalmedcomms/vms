@php
    $field['value'] = old_empty_or_null($field['name'], '') ?? $field['value'] ?? $field['default'] ?? '[]';
    if (is_array($field['value'])) {
        $field['value'] = json_encode($field['value']);
    }
    $field_id = 'field_'.Str::slug($field['name']);
@endphp

@include('crud::fields.inc.wrapper_start')
    <label>{!! $field['label'] !!}</label>
    @include('crud::fields.inc.translatable_icon')

    <div id="{{ $field_id }}_container" class="dynamic-contacts-container">
        <table class="table table-sm table-bordered mb-2">
            <thead class="bg-light text-center text-uppercase small font-weight-bold">
                <tr>
                    <th>Name</th>
                    <th>Number</th>
                    <th>Email</th>
                    <th style="width: 40px;"></th>
                </tr>
            </thead>
            <tbody class="contacts-body">
                {{-- Rows added by JS --}}
            </tbody>
        </table>
        <button type="button" class="btn btn-sm btn-outline-primary add-contact-btn">
            <i class="la la-plus"></i> Add Contact Person
        </button>
    </div>

    {{-- Hidden input to store the JSON string --}}
    <input type="hidden" name="{{ $field['name'] }}" class="contacts-json-input" value="{{ $field['value'] }}">

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

{{-- FIELD EXTRA CSS --}}
@push('crud_fields_styles')
<style>
    .dynamic-contacts-container th { letter-spacing: 0.5px; color: #666; }
    .remove-contact-btn { color: #dc3545; cursor: pointer; transition: color 0.2s; font-size: 1.1rem; }
    .remove-contact-btn:hover { color: #bd2130; }
    .dynamic-contacts-container .form-control-sm { border-radius: 4px; }
</style>
@endpush

{{-- FIELD EXTRA JS --}}
@push('crud_fields_scripts')
<script type="text/javascript">
    if (typeof bpDynamicContactsInit !== 'function') {
        function bpDynamicContactsInit(containerId) {
            const wrapper = document.getElementById(containerId);
            if (!wrapper) return;

            const body = wrapper.querySelector('.contacts-body');
            const input = wrapper.closest('.form-group').querySelector('.contacts-json-input');
            const addBtn = wrapper.querySelector('.add-contact-btn');
            
            let contacts = [];
            try {
                contacts = JSON.parse(input.value || '[]');
            } catch (e) {
                contacts = [];
            }

            function updateInput() {
                const rows = body.querySelectorAll('tr');
                const newContacts = [];
                rows.forEach(row => {
                    const name = row.querySelector('.contact-name').value.trim();
                    const number = row.querySelector('.contact-number').value.trim();
                    const email = row.querySelector('.contact-email').value.trim();
                    if (name || number || email) {
                        newContacts.push({ name, number, email });
                    }
                });
                input.value = JSON.stringify(newContacts);
            }

            function addRow(contact = { name: '', number: '', email: '' }) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td><input type="text" class="form-control form-control-sm contact-name" value="${contact.name || ''}" placeholder="Full Name"></td>
                    <td><input type="text" class="form-control form-control-sm contact-number" value="${contact.number || ''}" placeholder="Phone Number"></td>
                    <td><input type="email" class="form-control form-control-sm contact-email" value="${contact.email || ''}" placeholder="Email Address"></td>
                    <td class="text-center align-middle"><i class="la la-trash remove-contact-btn" title="Remove"></i></td>
                `;
                
                tr.querySelector('.remove-contact-btn').onclick = function() {
                    tr.remove();
                    updateInput();
                };

                tr.querySelectorAll('input').forEach(el => {
                    el.oninput = updateInput;
                });

                body.appendChild(tr);
            }

            // Load existing
            if (contacts.length > 0) {
                contacts.forEach(c => addRow(c));
            } else {
                addRow();
            }

            addBtn.onclick = function() {
                addRow();
                updateInput();
            };
        }
    }

    // Initialize the field
    (function() {
        const id = '{{ $field_id }}_container';
        // Use a small timeout to ensure DOM is ready if script is pushed early
        setTimeout(() => bpDynamicContactsInit(id), 10);
    })();
</script>
@endpush
