@php
    $existingDocs    = $crud->getCurrentEntry()?->documents ?? [];
    $removedDocsJson = old('remove_documents', '[]');
    $field_id        = 'field_vendor_documents';
    $todayDisplay    = now()->format('M d, Y');   // for display only
@endphp

@include('crud::fields.inc.wrapper_start')
    <label><i class="la la-paperclip mr-1"></i> Documents</label>
    @include('crud::fields.inc.translatable_icon')

    <div id="{{ $field_id }}_container" class="vendor-docs-container">

        <input type="hidden" name="remove_documents" id="{{ $field_id }}_remove_input" value="{{ $removedDocsJson }}">

        <table class="table table-sm table-bordered mb-2">
            <thead class="bg-light text-center text-uppercase small font-weight-bold">
                <tr>
                    <th>File</th>
                    <th style="width:130px;">Date</th>
                    <th style="width:40px;"></th>
                </tr>
            </thead>
            <tbody id="{{ $field_id }}_body">

                {{-- Existing saved documents (edit mode) --}}
                @foreach($existingDocs as $idx => $doc)
                    <tr class="existing-doc-row" id="{{ $field_id }}_existing_{{ $idx }}">
                        <td class="align-middle">
                            @php
                                $ext  = strtolower(pathinfo($doc['original_name'] ?? '', PATHINFO_EXTENSION));
                                $icon = in_array($ext, ['jpg','jpeg','png','gif']) ? 'la-file-image'
                                      : ($ext === 'pdf'                            ? 'la-file-pdf'
                                      : (in_array($ext, ['doc','docx'])            ? 'la-file-word'
                                      : (in_array($ext, ['xls','xlsx'])            ? 'la-file-excel'
                                      : ($ext === 'zip'                            ? 'la-file-archive' : 'la-file'))));
                            @endphp
                            <i class="la {{ $icon }} text-primary mr-1"></i>
                            <a href="{{ Storage::disk('public')->url($doc['path']) }}" target="_blank" title="Open file">
                                {{ $doc['original_name'] ?? $doc['stored_name'] ?? 'File' }}
                            </a>
                        </td>
                        <td class="text-center align-middle small text-muted">
                            {{ !empty($doc['uploaded_at']) ? \Carbon\Carbon::parse($doc['uploaded_at'])->format('M d, Y') : '—' }}
                        </td>
                        <td class="text-center align-middle">
                            <i class="la la-trash remove-existing-doc-icon"
                               data-index="{{ $idx }}"
                               title="Remove"
                               style="color:#dc3545;cursor:pointer;font-size:1.1rem;transition:color .2s;"></i>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>

        <button type="button" id="{{ $field_id }}_add_btn" class="btn btn-sm btn-outline-primary">
            <i class="la la-plus"></i> Add Document
        </button>
    </div>

    @if(isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
@include('crud::fields.inc.wrapper_end')

<style>
    .vendor-docs-container th { letter-spacing:.5px; color:#666; }
    .remove-doc-icon { color:#dc3545; cursor:pointer; transition:color .2s; font-size:1.1rem; }
    .remove-doc-icon:hover { color:#bd2130; }
    .existing-doc-row.marked-for-removal td { opacity:.4; text-decoration:line-through; }
    .existing-doc-row.marked-for-removal { background:#fff5f5; }
    .doc-date-cell { background:#f8f9fc; color:#6c757d; font-size:.82rem; text-align:center; padding:6px 4px; border-radius:4px; }
</style>

<script type="text/javascript">
(function () {
    var fieldId     = '{{ $field_id }}';
    var body        = document.getElementById(fieldId + '_body');
    var addBtn      = document.getElementById(fieldId + '_add_btn');
    var removeInput = document.getElementById(fieldId + '_remove_input');
    var todayDisplay = '{{ $todayDisplay }}';  // display only, never submitted

    if (!body || !addBtn || !removeInput) { return; }

    var toRemove = [];
    try { toRemove = JSON.parse(removeInput.value || '[]'); } catch(e) { toRemove = []; }

    /* ── Add a new upload row ── */
    function addRow() {
        var tr = document.createElement('tr');

        // ── File picker cell ──
        var tdFile    = document.createElement('td');
        var fileInput = document.createElement('input');
        fileInput.type      = 'file';
        fileInput.name      = 'vendor_documents[]';
        fileInput.className = 'form-control form-control-sm';
        fileInput.accept    = '.pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.zip';
        tdFile.appendChild(fileInput);

        // ── Date cell (read-only display, not a form input) ──
        var tdDate = document.createElement('td');
        tdDate.className = 'align-middle';
        var dateSpan = document.createElement('span');
        dateSpan.className   = 'doc-date-cell d-block';
        dateSpan.textContent = todayDisplay;
        tdDate.appendChild(dateSpan);

        // ── Remove cell ──
        var tdRemove  = document.createElement('td');
        tdRemove.className = 'text-center align-middle';
        var trashIcon = document.createElement('i');
        trashIcon.className = 'la la-trash remove-doc-icon';
        trashIcon.title     = 'Remove';
        trashIcon.addEventListener('click', function () {
            body.removeChild(tr);
        });
        tdRemove.appendChild(trashIcon);

        tr.appendChild(tdFile);
        tr.appendChild(tdDate);
        tr.appendChild(tdRemove);
        body.appendChild(tr);
    }

    /* ── Wire remove on existing rows ── */
    body.querySelectorAll('.remove-existing-doc-icon').forEach(function (icon) {
        var idx = parseInt(icon.dataset.index);
        var row = document.getElementById(fieldId + '_existing_' + idx);

        if (toRemove.indexOf(idx) !== -1 && row) {
            row.classList.add('marked-for-removal');
            icon.className   = 'la la-undo remove-doc-icon';
            icon.title       = 'Undo removal';
            icon.style.color = '#6c757d';
        }

        icon.addEventListener('click', function () {
            var pos = toRemove.indexOf(idx);
            if (pos === -1) {
                toRemove.push(idx);
                if (row) row.classList.add('marked-for-removal');
                icon.className   = 'la la-undo remove-doc-icon';
                icon.title       = 'Undo removal';
                icon.style.color = '#6c757d';
            } else {
                toRemove.splice(pos, 1);
                if (row) row.classList.remove('marked-for-removal');
                icon.className   = 'la la-trash remove-doc-icon';
                icon.title       = 'Remove';
                icon.style.color = '#dc3545';
            }
            removeInput.value = JSON.stringify(toRemove);
        });
    });

    /* ── Wire Add button ── */
    addBtn.addEventListener('click', addRow);
})();
</script>
