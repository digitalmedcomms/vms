@php
    $field['value'] = old_empty_or_null($field['name'], '') ?? $field['value'] ?? $field['default'] ?? '[]';
    if (is_array($field['value'])) {
        $field['value'] = json_encode($field['value']);
    }
    $field_id = 'field_'.Str::slug($field['name']);
    $field_value = $field['value'] ?: '[]';
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
            <tbody id="{{ $field_id }}_body">
                {{-- Rows added by JS --}}
            </tbody>
        </table>
        <button type="button" id="{{ $field_id }}_add_btn" class="btn btn-sm btn-outline-primary">
            <i class="la la-plus"></i> Add Contact Person
        </button>

        {{-- Hidden input to store the JSON string --}}
        <input type="hidden" id="{{ $field_id }}_input" name="{{ $field['name'] }}" value="{{ $field_value }}">
    </div>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

{{-- CSS --}}
@push('crud_fields_styles')
<style>
    .dynamic-contacts-container th { letter-spacing: 0.5px; color: #666; }
    .remove-contact-btn { color: #dc3545; cursor: pointer; transition: color 0.2s; font-size: 1.1rem; }
    .remove-contact-btn:hover { color: #bd2130; }
    .dynamic-contacts-container .form-control-sm { border-radius: 4px; }
</style>
@endpush

{{-- JS — inline so it always runs immediately after the HTML is rendered --}}
<script type="text/javascript">
(function() {
    var fieldId = '{{ $field_id }}';
    var body    = document.getElementById(fieldId + '_body');
    var input   = document.getElementById(fieldId + '_input');
    var addBtn  = document.getElementById(fieldId + '_add_btn');

    if (!body || !input || !addBtn) { return; }

    function escAttr(str) {
        return String(str || '').replace(/&/g,'&amp;').replace(/"/g,'&quot;');
    }

    function updateInput() {
        var rows   = body.querySelectorAll('tr');
        var result = [];
        rows.forEach(function(row) {
            var name   = row.querySelector('.contact-name').value.trim();
            var number = row.querySelector('.contact-number').value.trim();
            var email  = row.querySelector('.contact-email').value.trim();
            if (name || number || email) {
                result.push({ name: name, number: number, email: email });
            }
        });
        input.value = JSON.stringify(result);
        validateContacts(result);
    }

    function validateContacts(contacts) {
        var hint = document.getElementById(fieldId + '_hint');
        if (!hint) {
            hint = document.createElement('p');
            hint.id        = fieldId + '_hint';
            hint.className = 'text-danger small mt-1 mb-0';
            input.parentNode.insertBefore(hint, input.nextSibling);
        }

        if (contacts.length === 0) {
            hint.textContent = 'At least one contact person is required.';
            return;
        }

        var hasNumberOrEmail = contacts.some(function(c) {
            return (c.number && c.number.trim()) || (c.email && c.email.trim());
        });

        hint.textContent = hasNumberOrEmail
            ? ''
            : 'At least one contact must have a phone number or email address.';
    }

    function addRow(contact) {
        contact = contact || {};
        var tr = document.createElement('tr');
        tr.innerHTML =
            '<td><input type="text" class="form-control form-control-sm contact-name" value="' + escAttr(contact.name) + '" placeholder="Full Name"></td>' +
            '<td><input type="text" class="form-control form-control-sm contact-number" value="' + escAttr(contact.number) + '" placeholder="Phone Number"></td>' +
            '<td><input type="email" class="form-control form-control-sm contact-email" value="' + escAttr(contact.email) + '" placeholder="Email Address"></td>' +
            '<td class="text-center align-middle"><i class="la la-trash remove-contact-btn" title="Remove"></i></td>';

        tr.querySelector('.remove-contact-btn').addEventListener('click', function() {
            tr.parentNode.removeChild(tr);
            updateInput();
        });
        tr.querySelectorAll('input').forEach(function(el) {
            el.addEventListener('input', updateInput);
        });

        body.appendChild(tr);
    }

    var contacts = [];
    try { contacts = JSON.parse(input.value || '[]'); } catch(e) { contacts = []; }

    if (contacts.length > 0) {
        contacts.forEach(function(c) { addRow(c); });
    } else {
        addRow();
    }

    addBtn.addEventListener('click', function() {
        addRow();
    });
})();
</script>
