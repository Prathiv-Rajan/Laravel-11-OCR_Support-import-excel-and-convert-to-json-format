<!DOCTYPE html>
<html>
<head>
    <title>Laravel 11 Import Excel to Database and Download in Json</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>
<body>
      
<div class="container mt-5">
    <div class="card">
        <h3 class="card-header p-3"><i class="fa fa-star"></i> Laravel 11 Import Excel to Database and Download in Json</h3>
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
  
            <form action="{{ route('data.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
  
                <input type="file" name="file" class="form-control mb-3">

                <button type="submit" class="btn btn-success"><i class="fa fa-file"></i> Import User Data</button>
            </form>
        </div>
    </div>
    
    <!-- Table to display the data -->
    <div class="card mt-5">
        <div class="card-header">
            <h4 class="mb-0">Data Table</h4>
        </div>
        <div class="card-body">
            <button id="download-json" class="btn btn-primary mb-3">
                <i class="fa fa-download"></i> Download JSON
            </button>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Description</th>
                        <th>Label</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($diagnoses as $diagnosis)
                        <tr>
                            <td>{{ $diagnosis->Code }}</td>
                            <td>{{ $diagnosis->Description }}</td>
                            <td>{{ $diagnosis->Label }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    // Convert PHP data to JSON and assign it to a JavaScript variable
    var diagnoses = @json($diagnoses);

    // Handle JSON download
    document.getElementById('download-json').addEventListener('click', function() {
        var jsonData = JSON.stringify(diagnoses, null, 2); // Convert data to JSON and format with indentation
        var blob = new Blob([jsonData], { type: 'application/json' });
        var url = URL.createObjectURL(blob);
        var a = document.createElement('a');
        a.href = url;
        a.download = 'diagnoses.json'; // Set the filename for the download
        a.click();
        URL.revokeObjectURL(url);
    });
</script>

</body>
</html>
