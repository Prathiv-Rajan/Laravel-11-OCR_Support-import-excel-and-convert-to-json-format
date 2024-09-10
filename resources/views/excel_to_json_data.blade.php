<!DOCTYPE html>
<html>
<head>
    <title>Laravel Import Excel to JSON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <style>
        #json-container {
            position: relative;
        }
        #download-form {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        #json-result {
            white-space: pre-wrap; /* Preserve whitespace and line breaks */
            font-family: monospace; /* Ensure proper formatting */
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="card">
        <h3 class="card-header p-3"><i class="fa fa-star"></i> Convert Excel to JSON Example</h3>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Whoops!</strong> There were some problems with your input.<br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="excel-form" action="{{ url('/get-excel-data-json') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control mb-3">
                <button type="submit" class="btn btn-success"><i class="fa fa-file"></i> Upload and Get JSON</button>
            </form>

            <div id="json-container" class="mt-5">
                <form id="download-form" method="POST" action="{{ url('/download-json') }}" style="display:none;">
                    @csrf
                    <input type="hidden" name="json_data" id="json-data-input">
                    <input type="checkbox" name="pretty_print" id="json-data-input">
                    <span>JSON_PRETTY_PRINT</span>
                    <br>
                    <button type="submit" id="download-btn" class="btn btn-primary"><i class="fa fa-download"></i> Download JSON</button>
                </form>
                <div id="json-result"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#excel-form').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Display the JSON result with pretty-printing
                $('#json-result').html('<pre>' + JSON.stringify(response, null, 2) + '</pre>');
                $('#json-data-input').val(JSON.stringify(response));
                $('#download-form').show();
            },
            error: function() {
                $('#json-result').html('<p class="text-danger">An error occurred while processing the request.</p>');
            }
        });
    });
</script>
</body>
</html>
