<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Sheet Data</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; font-family: 'Segoe UI', Tahoma, sans-serif; }
        h2 { font-weight: 600; }
        .card { border-radius: 12px; box-shadow: 0 3px 8px rgba(0,0,0,0.05); }
        .card-header { font-weight: 500; font-size: 16px; }
        .table-responsive { max-height: 65vh; overflow-y: auto; }
        .table thead th { position: sticky; top: 0; z-index: 2; }
        th, td { white-space: nowrap; }
        td[contenteditable="true"] { background: #fff8e1; border: 1px dashed #ffcc80; }
        .btn { border-radius: 6px; }
        #add-row-btn, #save-new-rows-btn { margin-right: 8px; }
    </style>
</head>
<body>
    <div class="container-fluid mt-4">
        <h2 class="mb-4 text-primary"><i class="fab fa-google"></i> Google Sheet Data</h2>

        <!-- Flash & Validation Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li><i class="fas fa-times-circle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Fetch Form -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <i class="fas fa-link"></i> Fetch Google Sheet
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('google.sheet.fetch') }}" class="row g-3">
                    @csrf
                    <div class="col-md-8">
                        <input type="url" name="sheet_link" id="sheet_link"
                               class="form-control" placeholder="Paste Google Sheet URL" required>
                    </div>
                    <div class="col-md-4 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-sync"></i> Fetch
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <span><i class="fas fa-table"></i> Fetched Data</span>
            </div>

            <div class="card-body table-responsive p-0">
                @if($data->isEmpty())
                    <p class="text-muted p-3">No data found. Fetch a Google Sheet first.</p>
                @else
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Row #</th>
                            @foreach(array_keys(json_decode($data->first()->data, true)) as $col)
                                <th>{{ $col }}</th>
                            @endforeach
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="sheet-table-body">
                        @foreach($data as $row)
                            @php $decoded = json_decode($row->data, true); @endphp
                            <tr id="row-{{ $row->id }}" data-id="{{ $row->id }}">
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->sheet_row_number }}</td>
                                @foreach($decoded as $key => $val)
                                    <td contenteditable="true" data-key="{{ $key }}">{{ $val }}</td>
                                @endforeach
                                <td>
                                    <button class="btn btn-sm btn-success save-btn" data-id="{{ $row->id }}">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const tableBody = document.getElementById("sheet-table-body");

        // Auto-add row function
        function addBlankRow() {
            let colKeys = [];
            let firstRow = tableBody.querySelector("tr");
            if (firstRow) {
                firstRow.querySelectorAll("td[contenteditable=true]").forEach(cell => {
                    colKeys.push(cell.dataset.key);
                });
            }

            let newRow = document.createElement("tr");
            newRow.setAttribute("data-id", "new");

            let cells = `<td>New</td><td>—</td>`;
            colKeys.forEach(k => {
                cells += `<td contenteditable="true" data-key="${k}"></td>`;
            });
            cells += `
                <td>
                    <button class="btn btn-sm btn-success save-btn" data-id="new">
                        <i class="fas fa-save"></i> Save
                    </button>
                </td>
            `;

            newRow.innerHTML = cells;
            tableBody.appendChild(newRow);

            attachSaveHandler(newRow.querySelector(".save-btn"));
        }

        if (!tableBody.querySelector('tr[data-id="new"]')) {
            addBlankRow();
        }

        function attachSaveHandler(btn) {
            btn.addEventListener("click", function () {
                let saveBtn = this;
                let id = saveBtn.dataset.id;
                let row = saveBtn.closest("tr");

                // Disable button + show spinner
                saveBtn.disabled = true;
                let originalHTML = saveBtn.innerHTML;
                saveBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Saving...`;

                let updatedData = {};
                row.querySelectorAll("td[contenteditable=true]").forEach(cell => {
                    updatedData[cell.dataset.key] = cell.innerText.trim();
                });

                if (id === "new") {
                    fetch(`/google-sheet/store`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ rows: [updatedData] }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            let rowData = data.rows[0];

                            // Replace row content
                            let html = `
                                <td>${rowData.id}</td>
                                <td>${rowData.sheet_row_number}</td>
                            `;
                            Object.entries(rowData.data).forEach(([key, val]) => {
                                html += `<td contenteditable="true" data-key="${key}">${val}</td>`;
                            });
                            html += `
                                <td>
                                    <button class="btn btn-sm btn-success save-btn" data-id="${rowData.id}">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                </td>
                            `;

                            row.setAttribute("id", "row-" + rowData.id);
                            row.setAttribute("data-id", rowData.id);
                            row.innerHTML = html;

                            attachSaveHandler(row.querySelector(".save-btn"));
                            showMessage("✅ New row saved!", "success");

                            addBlankRow();
                        } else {
                            showMessage("❌ Failed to insert row", "danger");
                        }
                    })
                    .catch(() => showMessage("⚠️ Server error!", "danger"))
                    .finally(() => {
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = originalHTML;
                    });

                } else {
                    fetch(`/google-sheet/update/${id}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ data: updatedData, _method: "PATCH" }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showMessage(" Row updated!", "success");
                        } else {
                            showMessage(" Update failed: " + (data.message || ""), "danger");
                        }
                    })
                    .catch(() => showMessage(" Server error!", "danger"))
                    .finally(() => {
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = originalHTML;
                    });

                }
            });
        }


        document.querySelectorAll(".save-btn").forEach(btn => attachSaveHandler(btn));
    });

    // Flash message
    function showMessage(message, type) {
        let alertBox = document.createElement("div");
        alertBox.className = `alert alert-${type} alert-dismissible fade show mt-2`;
        alertBox.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector(".container-fluid").prepend(alertBox);
    }
</script>

</body>
</html>
