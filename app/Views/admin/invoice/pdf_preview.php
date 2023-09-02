<html>
<head>
    <title>PDF Preview</title>
</head>
<body>
    <iframe src="data:application/pdf;base64,<?= base64_encode($pdfString) ?>"
            type="application/pdf"
            width="100%"
            height="1000px"></iframe>
</body>
</html>

<?php if (isset($pdfString)) {  ?>
    
    <style>
        .zoomed-iframe {
            width: 100%; /* Set the iframe width */
            height: 100vh; /* Set the iframe height */
            transform-origin: 0 0; /* Set the zoom origin to the top-left corner */
            transform: scale(1); /* Default scale (no zoom) */
            transition: transform 0.3s; /* Add a smooth transition effect */
        }

        .zoomed-iframe.zoom-in {
            transform: scale(1.5); /* Zoom in by 150% */
        }
    </style>
    
    
  <?php }  ?>