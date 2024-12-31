<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report a Problem</title>
    <style>
        #cancel {
            margin-right: 20px;
            color: gray;
        }

        /* Drag and Drop Style */
        .drag-drop-zone {
            border: 2px dashed #6c757d;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            color: #6c757d;
            transition: background-color 0.3s ease;
            position: relative;
            background-color: #f8f9fa;
        }

        .drag-drop-zone p {
            margin: 0;
            font-size: 16px;
            color: #6c757d;
        }

        .drag-drop-zone:hover {
            background-color: #e9ecef;
        }

        .drag-drop-zone.dragover {
            background-color: #dee2e6;
            border-color: #adb5bd;
        }

        .upload-icon {
            font-size: 40px;
            color: #6c757d;
            margin-bottom: 10px;
        }

        /* Modal Style */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fff;
            margin: 15% auto;
            padding: 20px;
            border-radius: 5px;
            width: 80%;
            text-align: center;
        }

        .modal-close {
            color: red;
            float: right;
            font-size: 24px;
        }
    </style>
</head>
<body>

    <div class="row">
    <div class="col-xl-12 col-xxl-12 col-xs-12 d-flex">
        <div class="w-100">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col mt-0">

<h1 class="h3 mb-3">Report a Problem</h1>
<p>We will help you as soon as possible as you describe the problem in the message box below.</p>

<!-- Form to report a problem -->
<form id="reportProblemForm" action="#" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label>Message: </label><p></p>
        <textarea class="form-control" id="message" name="message" rows="4" placeholder="Describe the problem..."></textarea>
    </div>

    <div class="mb-3">
        <label for="problem_file" class="form-label">Attach a file (optional):</label>
        <div id="drop_zone" class="drag-drop-zone">
            <p>Drop files to attach or click here to upload</p>
            <input type="file" id="problem_file" name="problem_file" class="form-control-file" accept=".jpg, .jpeg, .png, .pdf" style="display: none;">
        </div>
    </div>

    <div class="form-group mt-3" style="text-align:right">
        <a href="#" id="cancel">Cancel</a>
        <button type="submit" class="btn btn-primary">Report</button>
    </div>
</form>

<!-- Success Modal -->
<div id="reportSuccessModal" class="modal">
    <div class="modal-content">
        <span class="modal-close" id="closeModal">&times;</span>
        <p>Report sent successfully! We will get back to you shortly.</p>
    </div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<script>
// Drag and Drop functionality
document.addEventListener('DOMContentLoaded', function () {
    const dropZone = document.getElementById('drop_zone');
    const fileInput = document.getElementById('problem_file');

    // Trigger file input when the drop zone is clicked
    dropZone.addEventListener('click', function () {
        fileInput.click();
    });

    dropZone.addEventListener('dragover', function (e) {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });

    dropZone.addEventListener('dragleave', function (e) {
        e.preventDefault();
        dropZone.classList.remove('dragover');
    });

    dropZone.addEventListener('drop', function (e) {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            dropZone.innerHTML = `<i class="fas fa-file"></i><p>File selected: ${files[0].name}</p>`;
        }
    });

    fileInput.addEventListener('change', function () {
        if (fileInput.files.length > 0) {
            dropZone.innerHTML = `<i class="fas fa-file"></i><p>File selected: ${fileInput.files[0].name}</p>`;
        }
    });

    // Submit the form via AJAX
    document.getElementById('reportProblemForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('<?php echo base_url(); ?>/admin/report/send', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('reportSuccessModal').style.display = 'block';
            }
        })
        .catch(error => console.error('Error:', error));
    });

    // Close modal
    document.getElementById('closeModal').addEventListener('click', function () {
        document.getElementById('reportSuccessModal').style.display = 'none';
    });
});
</script>
</body>
</html>
